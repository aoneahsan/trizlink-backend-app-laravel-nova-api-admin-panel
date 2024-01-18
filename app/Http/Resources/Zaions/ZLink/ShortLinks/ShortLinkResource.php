<?php

namespace App\Http\Resources\Zaions\ZLink\ShortLinks;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortLinkResource extends JsonResource
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
            'type' => $this->type,
            'target' => $this->target,
            'featureImg' => $this->featureImg,
            'title' => $this->title,
            'description' => $this->description,
            'pixelIds' => $this->pixelIds,
            'utmTagInfo' => $this->utmTagInfo,
            'shortUrlDomain' => $this->shortUrlDomain,
            'shortUrlPath' => $this->shortUrlPath,
            'privateUrlPath' => $this->privateUrlPath,
            'folderId' => $this->folderId,
            'notes' => $this->notes,
            'tags' => $this->tags,
            'status' => $this->status,
            'abTestingRotatorLinks' => $this->abTestingRotatorLinks,
            'geoLocationRotatorLinks' => $this->geoLocationRotatorLinks,
            'linkExpirationInfo' => $this->linkExpirationInfo,
            'password' => $this->password,
            'favicon' => $this->favicon,
            'isFavorite' => $this->isFavorite,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans()
        ];
    }
}
