<?php

namespace App\Http\Resources\Zaions\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            'id' => $this->uniqueId,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'phoneNumber' => $this->phoneNumber,
            'profileImage' => $this->profileImage,
            'description' => $this->description,
            'website' => $this->website,
            'language' => $this->language,
            'countrycode' => $this->countrycode,
            'country' => $this->country,
            'address' => $this->address,
            'city' => $this->city,
            'profilePitcher' => $this->profilePitcher,
            'avatar' => $this->avatar,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
            'email_verified_at' => $this->email_verified_at,
            'lastSeenAt' => $this->lastSeenAt ? $this->lastSeenAt : null,
            'lastSeenAtFormatted' => $this->lastSeenAt ? $this->lastSeenAt->diffForHumans(): null,
        ]; 
    }
}
