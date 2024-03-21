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
                    ->has('data', 1, function (AssertableJson $json) {
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

    public function test_car_search()
    {
        $car = Car::factory()->create();
        $car->model = 'bmw';
        $car->save();

        $car2 = Car::factory()->create();
        $car2->model = 'Lamborghini';
        $car2->save();

        $response = $this->getJson('/api/cars?search=bmw');

         $response->assertStatus(200)
             ->assertJson(function (AssertableJson $json) {
                 $json->hasAll(['message', 'data'])
                     ->whereType('message', 'string')
                 ->has('data', 1, function (AssertableJson $json) {
                     $json->hasAll(['id', 'model', 'year', 'mileage', 'cute'])
                         ->whereAllType([
                             'id' => 'integer',
                             'model' => 'string',
                             'year' => 'integer',
                             'mileage' => 'integer',
                             'cute' => 'integer'
                         ])->where('model', 'bmw');
                 });
         });

    }

    public function test_get_singleCar_success()
    {
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

    public function test_create_car_invalid()
    {
        $response = $this->postJson('/api/cars', []);

        $response->assertInvalid(['model', 'year']);
    }

    public function test_create_car_valid()
    {
        $response = $this->postJson('/api/cars', [
            'model' => 'new model',
            'year' => 2002,
            'mileage' => 20000,
            'cute' => 1
        ]);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])->whereType('message', 'string');
            });
        $this->assertDatabaseHas('cars', [
            'model' => 'new model',
            'year' => 2002,
            'mileage' => 20000,
            'cute' => 1
        ]);
    }

    public function test_update_car_invalidID()
    {
        $response = $this->putJson('/api/cars/1', [
            'model' => 'new model',
            'year' => 2002,
            'mileage' => 20000,
            'cute' => 1
        ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])->whereType('message', 'string');
            });
    }

    public function test_update_car_invalidData()
    {
        Car::factory()->create();
        $response = $this->putJson('/api/cars/1', []);
        $response->assertInvalid(['model', 'year']);
    }
    public function test_update_car_success()
    {
        Car::factory()->create();

        $response = $this->putJson('/api/cars/1', [
            'model' => 'new model',
            'year' => 2002,
            'mileage' => 20000,
            'cute' => 1
        ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])->whereType('message', 'string');
            });
        $this->assertDatabaseHas('cars', [
            'model' => 'new model'
        ]);
    }

    public function test_delete_car_success()
    {
        $car = Car::factory()->create();
        $car->model = 'missing';
        $car->save();

        $response = $this->deleteJson('/api/cars/1');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message'])
                    ->whereType('message', 'string');
            });

        $this->assertDatabaseMissing('cars', [
            'model' => $car->model
        ]);
    }


}
