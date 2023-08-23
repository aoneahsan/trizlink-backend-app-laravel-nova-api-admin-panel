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
            'phonenumber' => $this->phonenumber,
            'description' => $this->description,
            'website' => $this->website,
            'language' => $this->language,
            'countrycode' => $this->countrycode,
            'country' => $this->country,
            'address' => $this->address,
            'city' => $this->city,
            'profilePitcher' => $this->profilePitcher,
            'avatar' => $this->avatar,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'email_verified_at' => $this->email_verified_at,
            'lastSeen' => $this->lastSeen
        ];
    }
}
