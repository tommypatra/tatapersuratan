<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function append($key, $value)
    {
        $this->resource->{$key} = $value;
    }

    public function toArray($request)
    {
        return [
            // 'id' => $this->id,
            'name' => $this->name,
            'roles' => $this->roles,
            'email' => $this->email,
            'profil' => $this->profil,
        ];
    }
}
