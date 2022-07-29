<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_check_access_requires_feature_name_parameter()
    {
        $response = $this->get('/feature?email=test@moneylion.com');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Missing required parameters']);
    }

    /**
     * @test
     */
    public function test_check_access_requires_email_parameter()
    {
        $featureName = Feature::TYPE_LOAN;
        $response = $this->get("/feature?featureName={$featureName}");

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Missing required parameters']);
    }

    /**
     * @test
     */
    public function test_check_access_email_exists()
    {
        $featureName = Feature::TYPE_LOAN;
        $response = $this->get("/feature?email=sometest@gmail.com&featureName={$featureName}");

        $response->assertStatus(400);
        $response->assertJson(['error' => 'User not found']);
    }

    /**
     * @test
     */
    public function test_check_access_feature_name_exists()
    {
        $response = $this->get('/feature?email=test@moneylion.com&featureName=pinata');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Feature not found']);
    }

    /**
     * @test
     */
    public function test_check_access_feature_access_enabled()
    {
        $featureName = Feature::TYPE_LOAN;
        $feature = Feature::firstWhere('name', $featureName);

        User::firstWhere('email', 'test@moneylion.com')->features()->updateExistingPivot($feature->id, [
            'is_enabled' => true
        ]);

        $response = $this->get("/feature?email=test@moneylion.com&featureName={$featureName}");

        $response->assertStatus(200);
        $response->assertJson(['canAccess' => true]);
    }

    /**
     * @test
     */
    public function test_check_access_feature_access_disabled()
    {
        $featureName = Feature::TYPE_LOAN;
        $feature = Feature::firstWhere('name', $featureName);

        User::firstWhere('email', 'test@moneylion.com')->features()->updateExistingPivot($feature->id, [
            'is_enabled' => false
        ]);

        $response = $this->get("/feature?email=test@moneylion.com&featureName={$featureName}");

        $response->assertStatus(200);
        $response->assertJson(['canAccess' => false]);
    }

    /**
     * @test
     */
    public function test_update_enabling_feature_for_user()
    {
        $featureName = Feature::TYPE_LOAN;
        $feature = Feature::firstWhere('name', $featureName);

        $user = User::firstWhere('email', 'test@moneylion.com');

        $user->features()->updateExistingPivot($feature->id, [
            'is_enabled' => false
        ]);

        $response = $this->post("/feature", [
            'featureName' => Feature::TYPE_LOAN,
            'email' => 'test@moneylion.com',
            'enable' => true
        ]);

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertEquals($user->features()->where('name', Feature::TYPE_LOAN)->wherePivot('is_enabled', 1)->exists(), true);
    }

    /**
     * @test
     */
    public function test_update_disabling_feature_for_user()
    {
        $featureName = Feature::TYPE_LOAN;
        $feature = Feature::firstWhere('name', $featureName);

        $user = User::firstWhere('email', 'test@moneylion.com');

        $user->features()->updateExistingPivot($feature->id, [
            'is_enabled' => true
        ]);

        $response = $this->post("/feature", [
            'featureName' => Feature::TYPE_LOAN,
            'email' => 'test@moneylion.com',
            'enable' => false
        ]);

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertEquals($user->features()->where('name', Feature::TYPE_LOAN)->wherePivot('is_enabled', 0)->exists(), true);
    }
}
