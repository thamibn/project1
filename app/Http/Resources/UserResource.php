<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => "https://i.pravatar.cc",
            'roles' => $this->roles->pluck("name")->toArray(),
            'permissions' => $this->getAllPermissions()->pluck("name")->toArray()
        ];
    }
}
