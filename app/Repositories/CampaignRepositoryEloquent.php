<?php

namespace App\Repositories;


use App\Models\Campaign;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CampaignRepositoryEloquent implements CampaignRepository
{

    /**
     * Return all campaigns
     * @return Collection
     */
    public function fetchCampaigns(): Collection
    {
        return Campaign::with('images')->latest()->get();
    }

    /**
     * Creates a campaign
     * @param array $attributes
     * @return Campaign
     */
    public function createCampaign(array $attributes): Campaign
    {
        [$images] = $this->retrieveImageAttributes($attributes);
        /** @var Campaign $campaign */
        $campaign = Campaign::query()->create($attributes);
        $imagesData = $this->saveImages($campaign, $images);
        Image::query()->insert($imagesData);
        return $campaign->load('images');
    }

    /**
     * Updates a campaign
     * @param Campaign $campaign
     * @param $attributes
     * @return Campaign
     */
    public function updateCampaign(Campaign $campaign, $attributes): Campaign
    {

        [$images, $imagesToRemove] = $this->retrieveImageAttributes($attributes);
        $campaign->update($attributes);
        $this->removeImages($campaign, $imagesToRemove);
        if (count($images)) {
            $imagesData = $this->saveImages($campaign, $images);
            Image::query()->insert($imagesData);
        }
        return $campaign->fresh('images');
    }

    /**
     * Extracts image attributes
     * @param array $attributes
     * @return array
     */
    private function retrieveImageAttributes(array &$attributes): array
    {
        $images = $imagesToRemove = [];
        if (isset($attributes['images'])) {
            $images = $attributes['images'];
            unset($attributes['images']);
        }
        if (isset($attributes['imagesToRemove'])) {
            $imagesToRemove = $attributes['imagesToRemove'];
            unset($attributes['imagesToRemove']);
        }
        return [$images, $imagesToRemove];
    }

    /**
     * Saves images
     * @param UploadedFile[] $images
     */
    private function saveImages(Campaign $campaign, array $images): array
    {
        $paths = [];
        $id = $campaign->getAttribute('id');
        $folder = "creatives";
        foreach ($images as $image) {
            $name = $image->hashName();
            $path = "$folder/$name";
            $image->storeAs($folder, $name, 'public');
            $paths[] = [
                'campaign_id' => $id,
                'path' => $path
            ];
        }
        return $paths;
    }

    /**
     * Deletes images
     * @param Campaign $campaign
     * @param array $ids
     */
    private function removeImages(Campaign $campaign, array $ids)
    {
        if (empty($ids)) return;
        $paths = $campaign->images()
            ->select('path')->whereIn('id', $ids)
            ->toBase()
            ->pluck('path')
            ->map(function($path) {
                return "public/$path";
            })
            ->toArray();
        Storage::delete($paths);
        $campaign->images()->whereIn('id', $ids)->delete();
    }
}
