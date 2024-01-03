<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'size' => round($this->size / 1024 / 1024, 2),
            'type' => $this->type,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'user' => new UserAppResource($this->user),
        ];
    }
}
