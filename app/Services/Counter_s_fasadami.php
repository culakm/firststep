<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter_s_fasadami
{
    private $timeout;

    public function __constructor(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function increment (string $key, array $tags = null): int
    {
        // jedinecne session_id
        $session_id = session()->getId();
        // pocet userov
        $counter_key = "{$key}_counter";
        // info o useroch ktory navstivili stranky
        $users_key = "{$key}_users";

        //pole nacitane z cache : session_id => posledny navstiveny cas
        $users = Cache::tags(['blog_post'])->get($users_key, []);

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
        Cache::forever($users_key,$users_update);
        // updatneme pocet navstivenia
        if (!Cache::tags(['blog_post'])->has($counter_key)){
            // kluc este neexistuje
            Cache::tags(['blog_post'])->forever($counter_key,1);
        } else {
            Cache::tags(['blog_post'])->increment($counter_key, $difference);
        }

        $counter = Cache::tags(['blog_post'])->get($counter_key);

        return $counter;
    }
}