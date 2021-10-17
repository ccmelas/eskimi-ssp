<?php

namespace App\Repositories;

use App\Models\Campaign;
use Illuminate\Support\Collection;

interface CampaignRepository
{
    public function fetchCampaigns(): Collection;
    public function createCampaign(array $attributes): Campaign;
    public function updateCampaign(Campaign $campaign, $attributes): Campaign;
}
