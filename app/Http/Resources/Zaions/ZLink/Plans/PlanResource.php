<?php

namespace App\Http\Resources\Zaions\ZLink\Plans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'name' => $this->name,
            'displayName' => $this->displayName,
            'monthlyPrice' => $this->monthlyPrice,
            'annualPrice' => $this->annualPrice,
            'monthlyDiscountedPrice' => $this->monthlyDiscountedPrice,
            'annualDiscountedPrice' => $this->annualDiscountedPrice,
            'currency' => $this->currency,
            'description' => $this->description,
            'featureListTitle' => $this->featureListTitle,
            'isMostPopular' => $this->isMostPopular,
            'isAnnualOnly' => $this->isAnnualOnly,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'features' => $this->features
            // 'createdAt' => $this->created_at,
            // 'updatedAt' => $this->updated_at
        ];
    }
}
