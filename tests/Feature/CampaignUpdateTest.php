<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CampaignUpdateTest extends TestCase
{
    use RefreshDatabase;

    private array $headers = [
        'Content-Type' => 'multipart/form-data',
        'Accept' => 'application/json',
    ];

    /** @test */
    public function canUpdateCampaignName()
    {
        $campaign = Campaign::factory(1)
            ->has(Image::factory()->count(3))
            ->create()->first();
        $id = $campaign->getAttribute('id');
        $this->post("api/campaigns/$id", [
            'name' => 'Update'
        ], $this->headers)
            ->assertStatus(200);
        $this->assertDatabaseHas('campaigns', [
            'name' => 'Update',
            'id' => $id,
        ]);
    }

    /** @test */
    public function canUpdateCampaignDailyBudget()
    {
        $campaign = Campaign::factory(1)
            ->has(Image::factory()->count(3))
            ->create()->first();
        $id = $campaign->getAttribute('id');
        $this->post("api/campaigns/$id", [
            'daily_budget' => 10.00
        ], $this->headers)
            ->assertStatus(200);
        $this->assertDatabaseHas('campaigns', [
            'daily_budget' => 10.00,
            'id' => $id,
        ]);
    }

    /** @test */
    public function canUpdateCampaignDates()
    {
        $campaign = Campaign::factory(1)
            ->has(Image::factory()->count(3))
            ->create()->first();
        $id = $campaign->getAttribute('id');
        $from = now()->addDay()->format('Y-m-d');
        $to = now()->addDays(2)->format('Y-m-d');
        $this->post("api/campaigns/$id", [
            'from' => $from,
            'to' => $to,
        ], $this->headers)->assertStatus(200);
        $this->assertDatabaseHas('campaigns', [
            'from' => $from . " 00:00:00",
            'to' => $to . " 00:00:00",
            'id' => $id,
        ]);
    }


    /** @test */
    public function mustProvideBothDateRangesForAnyUpdate()
    {
        $campaign = Campaign::factory(1)
            ->has(Image::factory()->count(3))
            ->create()->first();
        $id = $campaign->getAttribute('id');
        $date = now()->addDay(1)->format('Y-m-d');
        $this->post("api/campaigns/$id", [
            'from' => $date
        ], $this->headers)->assertStatus(422);
        $this->assertDatabaseMissing('campaigns', [
            'id' => $id,
            'from' => $date . " 00:00:00"
        ]);
        $this->post("api/campaigns/$id", [
            'to' => $date
        ], $this->headers)->assertStatus(422);
        $this->assertDatabaseMissing('campaigns', [
            'id' => $id,
            'to' => $date . " 00:00:00"
        ]);
    }


    /** @test */
    public function canAddCampaignCreatives()
    {
        $campaign = Campaign::factory(1)
            ->has(Image::factory()->count(3))
            ->create()->first();
        $id = $campaign->getAttribute('id');
        $this->post("api/campaigns/$id", [
            'images' => [UploadedFile::fake()->image('test.jpg')],
        ], $this->headers)->assertStatus(200);
        $this->assertDatabaseCount('images', 4);
    }

    /** @test */
    public function canRemoveCampaignCreatives()
    {
        $campaign = Campaign::factory(1)
            ->has(Image::factory()->count(3))
            ->create()->first();
        $id = $campaign->getAttribute('id');
        $this->post("api/campaigns/$id", [
            'imagesToRemove' => [1],
        ], $this->headers)->assertStatus(200);
        $this->assertDatabaseCount('images', 2);
    }
}
