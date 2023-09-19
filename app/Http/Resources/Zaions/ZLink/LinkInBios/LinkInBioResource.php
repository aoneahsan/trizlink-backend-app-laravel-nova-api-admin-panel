<?php

namespace App\Http\Resources\Zaions\ZLink\LinkInBios;

use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkInBioResource extends JsonResource
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
            'theme' => $this->theme,
            'settings' => $this->settings,
            'poweredBy' => $this->poweredBy,

            'linkInBioTitle' => $this->linkInBioTitle,
            'featureImg' => $this->featureImg,
            'title' => $this->title,
            'description' => $this->description,
            'pixelIds' => $this->pixelIds,
            'utmTagInfo' => $this->utmTagInfo,
            'shortUrl' => $this->shortUrl,
            'folderId' => $this->folderId,
            'notes' => $this->notes,
            'tags' => $this->tags,
            'abTestingRotatorLinks' => $this->abTestingRotatorLinks,
            'geoLocationRotatorLinks' => $this->geoLocationRotatorLinks,
            'linkExpirationInfo' => $this->linkExpirationInfo,
            'password' => ZHelpers::zJsonDecode($this->password),
            'favicon' => $this->favicon,

            // 'blocks' => $this->blocks ? LinkInBioBlockResource::collection($this->blocks) : [],

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
