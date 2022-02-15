<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentUser as CommentUserResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'zmeneny_nazov_id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at->timezone,
            'updated_at_class' => get_class($this->updated_at),
            'carbon_date' => Carbon::now(),
            'carbon_date2' => (string) Carbon::now(),
            'user_id' => $this->user->id,
            'user_napriamo' => $this->user,
            'user_cez_resource' => new CommentUserResource($this->user),
            'user_cez_resource_len_ked_sa_loaduje' => new CommentUserResource($this->whenLoaded('user')), // 'user' je meno metody z modelu Comment ktora obsluhuje relationship
        ];
    }
}
