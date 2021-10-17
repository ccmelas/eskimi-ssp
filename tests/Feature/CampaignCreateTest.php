<?php

namespace Tests\Feature;

use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CampaignCreateTest extends TestCase
{
    use RefreshDatabase;

    private array $headers = [
        'Content-Type' => 'multipart/form-data',
        'Accept' => 'application/json',
    ];

    /** @test */
    public function canCreateCampaign()
    {
        $data = $this->getCampaignAttributes(['images' => [
            UploadedFile::fake()->image('one.jpg'),
            UploadedFile::fake()->image('two.jpg'),
            UploadedFile::fake()->image('three.jpg'),
        ]]);
        $this->post('api/campaigns', $data, $this->headers)
            ->assertStatus(201);
        $this->assertDatabaseHas('campaigns', [
            'name' => $data['name'],
            'from' => $data['from'] . " 00:00:00",
            'to' => $data['to'] . " 00:00:00",
            'daily_budget' => $data['daily_budget'],
        ]);
        $this->assertDatabaseCount('images', 3);
    }

    /** @test */
    public function cannotCreateCampaignWithInvalidDates()
    {
        // to is before from
        $this->post(
            'api/campaigns',
            $this->getCampaignAttributes([
                'images' => [UploadedFile::fake()->image('one.jpg')],
                'from' => now()->addDay(3)->format('Y-m-d'),
                'to' => now()->addDay(1)->format('Y-m-d'),
            ]),
            $this->headers)
            ->assertStatus(422);

        // Invalid format
        $this->post(
            'api/campaigns',
            $this->getCampaignAttributes([
                'images' => [UploadedFile::fake()->image('one.jpg')],
                'from' => now()->addDay(3)->format('d-m-Y'),
            ]),
            $this->headers)
            ->assertStatus(422);

        // from date is passed
        $this->post(
            'api/campaigns',
            $this->getCampaignAttributes([
                'images' => [UploadedFile::fake()->image('one.jpg')],
                'from' => now()->subDay(1)->format('Y-m-d'),
            ]),
            $this->headers)
            ->assertStatus(422);


        $this->assertDatabaseCount('campaigns', 0);
        $this->assertDatabaseCount('images', 0);
    }


    /** @test */
    public function cannotCreateCampaignWithoutName()
    {
        // to is before from
        $this->post(
            'api/campaigns',
            $this->getCampaignAttributes([
                'images' => [UploadedFile::fake()->image('one.jpg')],
                'name' => null,
            ]),
            $this->headers
        )->assertStatus(422);
        $this->assertDatabaseCount('campaigns', 0);
        $this->assertDatabaseCount('images', 0);
    }

    /** @test */
    public function cannotCreateCampaignWithoutImages()
    {
        // to is before from
        $this->post(
            'api/campaigns',
            $this->getCampaignAttributes(['images' => []]),
            $this->headers
        )->assertStatus(422);
        $this->assertDatabaseCount('campaigns', 0);
        $this->assertDatabaseCount('images', 0);
    }

    /** @test */
    public function cannotCreateCampaignWithoutDailyBudget()
    {
        // to is before from
        $this->post(
            'api/campaigns',
            $this->getCampaignAttributes([
                'images' => [UploadedFile::fake()->image('one.jpg')],
                'daily_budget' => null,
            ]),
            $this->headers
        )->assertStatus(422);
        $this->assertDatabaseCount('campaigns', 0);
        $this->assertDatabaseCount('images', 0);
    }

    /**
     * Get campaign attributes
     * @param array $overrides
     * @return array
     */
    private function getCampaignAttributes(array $overrides = []): array
    {
        $data = Campaign::factory()->make()->getAttributes();
        $data = array_merge($data, $overrides);
        $data['from'] = explode(' ', $data['from'])[0];
        $data['to'] = explode(' ', $data['to'])[0];
        return $data;
    }
}
