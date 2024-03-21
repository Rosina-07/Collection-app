<?php

namespace Tests\Feature;

use App\Models\Car;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class CarTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     */
    public function test_get_cars_success(): void
    {
        Car::factory()->create();

        $response = $this->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                        ->whereType('message', 'string')
                    ->has('data',1, function (AssertableJson $json) {
                        $json->hasAll(['id', 'model', 'year', 'mileage', 'cute'])
                            ->whereAllType([
                                'id' => 'integer',
                                'model' => 'string',
                                'year' => 'integer',
                                'mileage' => 'integer',
                                'cute' => 'integer'
                            ]);
                    });
            });
    }

    public function test_get_singleCar_success() {
        Car::factory()->create();
        $response = $this->getJson('/api/cars/1');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                        ->whereType('message', 'string')
                    ->has('data', function (AssertableJson $json) {
                        $json->hasAll(['id', 'model', 'year', 'mileage', 'cute'])
                            ->whereAllType([
                                'id' => 'integer',
                                'model' => 'string',
                                'year' => 'integer',
                                'mileage' => 'integer',
                                'cute' => 'integer'
                            ]);
                    });
            });
    }
}
