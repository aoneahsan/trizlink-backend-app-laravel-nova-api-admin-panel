<?php

namespace App\Http\Resources\Zaions\ZLink\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UtmTagResource extends JsonResource
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
            'templateName' => $this->templateName,
            'utmCampaign' => $this->utmCampaign,
            'utmMedium' => $this->utmMedium,
            'utmSource' => $this->utmSource,
            'utmTerm' => $this->utmTerm,
            'utmContent' => $this->utmContent,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans()
        ];
    }
}
