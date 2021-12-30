<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LatestScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy($model::CREATED_AT, 'desc'); // $model::CREATED_AT moze byt nazov hocijakeho strpca
    }
}