<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignCreateRequest;
use App\Http\Requests\CampaignUpdateRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Repositories\CampaignRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignController extends Controller
{
    private CampaignRepository $repository;

    public function __construct(CampaignRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns a list of campaigns
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        return CampaignResource::collection($this->repository->fetchCampaigns());
    }

    /**
     * Stores a campaign
     * @param CampaignCreateRequest $request
     * @return JsonResource
     */
    public function store(CampaignCreateRequest $request): JsonResource
    {
        return CampaignResource::make(
            $this->repository->createCampaign($request->validated())
        );
    }

    /**
     * Updates a campaign
     * @param CampaignUpdateRequest $request
     * @param Campaign $campaign
     * @return JsonResource
     */
    public function update(CampaignUpdateRequest $request, Campaign $campaign): JsonResource
    {
        return CampaignResource::make(
            $this->repository->updateCampaign($campaign, $request->validated())
        );
    }
}
