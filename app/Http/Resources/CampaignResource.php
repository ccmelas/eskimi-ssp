<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $base = [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'from' => $this->resource->from->format('Y-m-d'),
            'to' => $this->resource->to->format('Y-m-d'),
            'daily_budget' => $this->resource->daily_budget,
            'total_budget' => $this->resource->total_budget,
        ];
        if ($this->resource->relationLoaded('images')) {
            $base['images'] = ImageResource::collection($this->resource->images);
        }
        return $base;
    }
}
