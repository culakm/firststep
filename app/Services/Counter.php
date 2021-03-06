<?php

namespace App\Services;


// tieto su pouzite pre dependency injedtion

use App\Contracts\CounterContract;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class Counter implements CounterContract
{
    private $cache;
    private $session;
    private $timeout;
    private $supportsTags;
    
    // parametre su predavane ako dependency injection v app/Providers/AppServiceProvider.php
    public function __construct(Cache $cache, Session $session, int $timeout)
    {
        $this->cache = $cache;
        $this->session = $session;
        $this->timeout = $timeout;
        // pretoze cache tagy podporuje len redis, musime zistit ci cache ma metodu tags
        $this->supportsTags = method_exists($cache, 'tags');
    }

    
    public function increment (string $key, array $tags = null): int
    {
        // jedinecne session_id
        $session_id = $this->session->getId();
        // pocet userov
        $counter_key = "{$key}_counter";
        // info o useroch ktory navstivili stranky
        $users_key = "{$key}_users";

        // rozhodovanie o podpore tagov pre cache
        $cache = $this->supportsTags && $tags !== null 
            ? $this->cache->tags($tags) : $this->cache;

        //pole nacitane z cache : session_id => posledny navstiveny cas
        $users = $cache->get($users_key, []);

        // neexpirovany useri pre $users
        $users_update = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $last_visit_time) {
            // ak rozdiel medzi now() a poslednej navsetvy nejakeho usera je viac ako 1 minuta
            if ($now->diffInMinutes($last_visit_time) >= $this->timeout) {
                $difference--;
            } else {
                $users_update[$session] = $last_visit_time;
            }
        }


        if (
            // user este nie je na zozname ?
            !array_key_exists($session_id, $users)
            // user bol na zozname ale vyexpiroval
            || $now->diffInMinutes($users[$session_id]) >= $this->timeout
        ){
            $difference++;
        }

        // updatneme cas navstivenia pre usera
        $users_update[$session_id] = $now;
        // do cache dame cerstvy zoznam userov s poslednym casom navstivenia
        $cache->forever($users_key,$users_update);
        // updatneme pocet navstivenia
        if (!$cache->has($counter_key)){
            // kluc este neexistuje
            $cache->forever($counter_key,1);
        } else {
            $cache->increment($counter_key, $difference);
        }

        $counter = $cache->get($counter_key);

        return $counter;
    }
}