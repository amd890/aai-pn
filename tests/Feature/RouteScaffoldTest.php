<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteScaffoldTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_front_landing_page_returns_successful_status(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_member_verification_public_route(): void
    {
        $response = $this->get('/membership/verify');
        $response->assertStatus(200);
    }

    public function test_api_status_endpoint_returns_valid_json(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
        ]);
        $response->assertJson([
            'status' => 'ok',
        ]);
    }
}
