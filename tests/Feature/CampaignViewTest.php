<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CampaignViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userCanViewCampaigns()
    {
        Campaign::factory(10)
            ->has(Image::factory()->count(3))
            ->create();
        $this->getJson('/api/campaigns')
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 10);
                $json->has('data.0.images', 3);
            });
    }
}
