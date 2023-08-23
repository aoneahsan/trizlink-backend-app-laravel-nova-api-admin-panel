<?php

namespace App\Http\Resources\Zaions\ZLink\Common;

use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uniqueId,
            'title' => $this->title,
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'expireDate' => $this->expireDate ? Carbon::parse($this->expireDate, ZHelpers::getTimezone()) : null,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
