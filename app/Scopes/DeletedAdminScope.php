<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeletedAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // admin bude vidiet aj deletovane polozky
        // Auth::check() - je user autentifikovany?
        // Auth::user()->is_admin - je user admin?
        if (Auth::check() && Auth::user()->is_admin){
            
            $builder->withTrashed();
            // toto je to iste ale da sa to pouzit ja pri query, nie je to dobre vyskusane
            //$builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope');
        }
    }
}