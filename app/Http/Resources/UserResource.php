<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullname,
            'url' => [
                // 'index' => route('users.show', ['user'=>$this->id]),
                // 'settings' => route('users.settings.profile', ['user'=>$this->id])
            ],
            'profile_picture' => $this->profile_picture(),
            'banner_picture' => $this->banner_picture(),
            'owned_projects' => ProjectResource::collection($this->owned_projects),
            'followed_projects' => $this->followed_projects->pluck('id'),
            'followed_users' => $this->followed_users->pluck('id'),
            'projects' => ProjectResource::collection($this->projects)
        ];
    }
}
