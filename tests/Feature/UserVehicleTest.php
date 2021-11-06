<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImages;
use Barryvdh\Debugbar\Twig\Extension\Dump;
use Database\Seeders\Testing\TestingVehicleMakeModelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Trait\UserTrait;
use Tests\Trait\UserVehicleTrait;

class UserVehicleTest extends TestCase
{
    use RefreshDatabase, UserTrait, UserVehicleTrait;

    /**
     *  @test
     *  Unauthenticated users cannot access the users vehicle index api endpoint
     */
    public function unauthenticated_users_cannot_access_the_users_vehicle_index_api_route()
    {
        $this->json('GET', '/api/dashboard/index-users-vehicles')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A paginated index of the users vehicles is displayed
     */
    public function a_paginated_index_of_users_vehicle_is_displayed_in_the_user_vehicle_index()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'active',
                        'created_at',
                        'featured_image',
                        'make',
                        'model',
                        'year',
                        'price_day'
                    ]
                ],
                'links' => [
                    'first', 'last', 'prev', 'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url', 'label', 'active'
                        ]
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    /**
     *  @test
     *  Setting active to true in the request shows active vehicles
     */
    public function settting_active_to_true_shows_active_vehicles_in_the_user_vehicle_index_json_response()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $vehicle = $user->vehicles->first();

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles', [
            'active' => 'true'
        ]);

        $response->assertJsonFragment([
            'model' => $vehicle->vehicleModel->model,
            'year' => $vehicle->year,
            'price_day' => $vehicle->price_day
        ]);
    }

    /**
     *  @test
     *  Inactive vehicles are not seen in the user vehicle index
     */
    public function inactive_vehicles_are_not_seen_in_the_user_vehicle_index()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $user->vehicles->each(function($vehicle) {
            $vehicle->update([
                'active' => 0
            ]);
        });

        $vehicle = $user->vehicles->first();

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles', [
            'active' => 'true'
        ]);

        $response->assertJsonMissing([
            'model' => $vehicle->vehicleModel->model,
            'year' => $vehicle->year,
            'price_day' => $vehicle->price_day
        ]);
    }


    /**
     *  @test
     *  A user can only view their own vehicles in the index
     */
    public function a_user_can_only_view_their_own_vehicles_in_the_user_vehicle_index()
    {
        $this->createSmallDatabase();

        $users = User::where('host', 1)->get();

        $userOne = $users[0];
        $userTwo = $users[1];
        $userThree = $users[2];

        $this->actingAs($userOne);

        $userOneVehicle = $userOne->vehicles->first();
        $userTwoVehicle = $userTwo->vehicles->first();
        $userThreeVehicle = $userThree->vehicles->first();

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles', [
            'active' => 'true'
        ]);

        $response->assertJsonFragment([
            'id' => $userOneVehicle->id,
        ])->assertJsonMissing([
            'id' => $userTwoVehicle->id
        ])->assertJsonMissing([
            'id' => $userThreeVehicle->id
        ]);
    }

    /**
     *  @test
     *  Unauthenticated users cannot access the new vehicle api endpoint
     */
    public function unauthenticated_users_cannot_access_the_new_vehicle_api_route()
    {
        $this->json('POST', '/api/dashboard/create-users-vehicles')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A user must be a host to create a vehicle
     */
    public function a_user_must_be_a_host_to_create_a_vehicle()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create();

        $this->actingAs($user);

        $data = $this->validNewVehicleData();

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(403)
            ->assertSee('You must be a host to create a vehicle');
    }

    /**
     *  @test
     *  An empty request results in 422 validation error
     */
    public function an_empty_request_results_in_422_to_the_new_vehicle_api_route()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', '/api/dashboard/create-users-vehicles', [])
            ->assertStatus(422);
    }

    /**
     *  @test
     *  Image and featured image are required to create a vehicle
     */
    public function image_and_featured_image_are_required_to_create_vehicle()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        $data = $this->validNewVehicleData([
            'images' => [],
            'featured_id' => ''
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(422)
            ->assertSee('The images field is required.')
            ->assertSee('Please select a featured image');
    }

    /**
     *  @test
     *  File types other than images are rejected when creating vehilces
     */
    public function file_types_other_than_images_are_rejected_when_creating_vehicles()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        // Create a fake pdf
        $file = UploadedFile::fake()->create('test-file.pdf', 8000, 'application/pdf');

        $data = $this->validNewVehicleData([
            'images' => [$file],
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(422)
            ->assertSee('File must be an image');
    }

    /**
     *  @test
     *  A request with a feature id that doesn't match an image results in 422 error
     */
    public function request_with_invalid_image_id_results_in_422_error()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        $data = $this->validNewVehicleData([
            'featured_id' => 'FAKE123'
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(422)
            ->assertSee('Invalid featured image');
    }

    /**
     *  @test
     *  When valid data is submitted a new vehicle is created
     */
    public function when_valid_data_is_submitted_a_new_vehicle_is_created()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        Storage::fake('public');

        $data = $this->validNewVehicleData();

        $this->assertDatabaseMissing('vehicles', [
            'plate_num' => $data['plate'],
            'price_day' => $data['price']
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('vehicles', [
            'plate_num' => $data['plate'],
            'price_day' => $data['price']
        ]);
    }

    /**
     *  @test
     *  Images are saved to disk when vehicle is created
     */
    public function images_are_saved_to_disk_when_new_vehicle_is_created()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        Storage::fake('public');

        $data = $this->validNewVehicleData();

        // There should be no vehicles
        $this->assertEmpty(Vehicle::get());

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(201);

        // The vehicle we created should have an image stored associated with it
        $vehicleImage = Vehicle::first()
            ->vehicleImages
            ->first()
            ->image;

        $this->assertNotEmpty($vehicleImage);

        $imagePath = remove_storage_file_path($vehicleImage);

        Storage::disk('public')->assertExists($imagePath);
    }

    /**
     *  @test
     *  A featured image is set when a vehicle is created
     */
    public function a_featured_image_is_set_when_creating_a_new_vehicle()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        Storage::fake('public');

        $data = $this->validNewVehicleData();

        // There should be no vehicles
        $this->assertEmpty(Vehicle::get());

        $response = $this->json('POST', '/api/dashboard/create-users-vehicles', $data);

        $featuredImageUrl = Vehicle::first()->featured_image;

        $this->assertNotEmpty($featuredImageUrl);

        $path = remove_storage_file_path($featuredImageUrl);

        Storage::disk('public')->assertExists($path);
    }

    /**
     *  @test
     *  Unauthenticated users cannot access the users vehicle update api endpoint
     */
    public function unauthenticated_users_cannot_access_the_users_vehicle_update_api_route()
    {
        $this->json('POST', '/api/dashboard/update-users-vehicles/1')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A user cannot update a vehicle that they do not own
     */
    public function a_user_cannot_update_a_vehicle_they_do_not_own()
    {
        $this->createSmallDatabase();

        $users = User::where('host', 1)->get();

        $userOne = $users[0];
        $userTwo = $users[1];

        $this->actingAs($userOne);

        $vehicle = $userTwo->vehicles->first();

        $data = $this->validUpdateVehicleDataNoImages();

        $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", 
            $data
        )->assertStatus(403)
        ->assertSee('You do not own this vehicle');
    }

    /**
     *  @test
     *  If there are no images in the request and no images associated with the vehicle a 403 is returned
     */
    public function if_there_are_no_images_in_the_request_and_the_vehicle_has_no_images_403_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $data = $this->validUpdateVehicleDataNoImages();

        $vehicle = $user->vehicles->first();

        $vehicle->vehicleImages->each(function($image) {
            $image->delete();
        });

        $response = $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data);

        // The vehicle is disabled when there are no images
        $this->assertEquals(Vehicle::find($vehicle->id)->active, 0);

        $response->assertStatus(404)
            ->assertSee('Please provide images for your vehicle');
    }

    /**
     *  @test
     *  Passing empty data into the vehicle update route results in 422 error
     */
    public function empty_request_to_vehicle_update_route_results_in_422_error()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $data = $this->validUpdateVehicleDataNoImages([
            'price' => '',
            'description' => ''
        ]);

        $vehicle = $user->vehicles->first();

        $response = $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data);

        $response->assertStatus(422);
    }

    /**
     *  @test
     *  A vehicle status price and description can be updated
     */
    public function a_vehicles_status_price_and_description_can_be_updated()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $data = $this->validUpdateVehicleDataNoImages([
            'active' => 'false',
            'price' => 999,
            'description' => 'UPDATED UPDATED UPDATED'
        ]);

        $vehicle = $user->vehicles->first();

        $this->assertEquals($vehicle->active, 1);

        $response = $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data)
        ->assertStatus(201);

        $updatedVehicle = Vehicle::find($vehicle->id);

        $this->assertEquals($updatedVehicle->price_day, 999);
        $this->assertEquals($updatedVehicle->description, 'UPDATED UPDATED UPDATED');
        $this->assertEquals($updatedVehicle->active, 0);
    }

    /**
     *  @test
     *  New images can be added to a vehicle
     */
    public function new_images_can_be_added_to_a_vehicle()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $newImage = UploadedFile::fake()->image('test-file.jpg', 1200, 800)->size(1000);

        $data = $this->validUpdateVehicleDataNoImages([
            'images' => [$newImage]
        ]);

        $vehicle = $user->vehicles->first();

        $imageCount = $vehicle->vehicleImages->count();

        $response = $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data)
        ->assertStatus(201);

        $updatedVehicleImageCount = Vehicle::find($vehicle->id)->vehicleImages->count();

        $this->assertEquals($imageCount + 1, $updatedVehicleImageCount);
    }

    /**
     *  @test 
     *  More than 12 images for a vehicle results in a 422 error
     */
    public function attempting_to_have_more_than_12_images_for_a_vehicle_results_in_validation_422_error()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $newImage = UploadedFile::fake()->image('test-file.jpg', 1200, 800)->size(1000);

        $data = $this->validUpdateVehicleDataNoImages([
            'images' => [$newImage, $newImage, $newImage, $newImage, $newImage, $newImage, $newImage, $newImage,
            $newImage, $newImage, $newImage, $newImage]
        ]);

        $vehicle = $user->vehicles->first();

        $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data)
        ->assertStatus(422)
        ->assertSee('Maximum of 12 images allowed, please remove some.');
    }

    /**
     *  @test
     *  An existing image can be set to the featured image
     */
    public function an_existing_image_can_be_set_to_the_featured_image()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        Storage::fake('public');

        $vehicle = $user->vehicles->first();

        // Clear existing images and set a new one
        $vehicle->vehicleImages->each(function($image) {
            $image->delete();
        });

        $newImage = UploadedFile::fake()->image('test-file.jpg', 1200, 800)->size(1000);

        $data = $this->validUpdateVehicleDataNoImages([
            'images' => [$newImage]
        ]);

        $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data)
        ->assertStatus(201);

        // Get the updated vehicle and set a new featured image
        $vehicleOld = Vehicle::find($vehicle->id);

        $newData = $this->validUpdateVehicleDataNoImages([
            'featured_id' => $vehicleOld->vehicleImages->first()->image
        ]);

        $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicleOld->id}", $newData)
        ->assertStatus(201);

        // Get the updated vehicle featured image, compare it to the old one then
        // check to make sure it exists on the disk
        $vehicleNewFeaturedImage = Vehicle::find($vehicle->id)->featured_image;

        $this->assertNotEquals($vehicleOld->featured_image, $vehicleNewFeaturedImage);

        $path = remove_storage_file_path($vehicleNewFeaturedImage);

        Storage::disk('public')->assertExists($path);
    }

    /**
     *  @test
     *  An existing image can be deleted.
     */
    public function an_existing_vehicle_image_can_be_deleted()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        Storage::fake('public');

        $vehicle = $user->vehicles->first();

        $newImage = UploadedFile::fake()->image('test-file.jpg', 1200, 800)->size(1000);

        $data = $this->validUpdateVehicleDataNoImages([
            'images' => [$newImage]
        ]);

        $this->json(
            'POST', 
            "/api/dashboard/update-users-vehicles/{$vehicle->id}", $data)
        ->assertStatus(201);

        $newestVehicleImage = Vehicle::find($vehicle->id)->vehicleImages->last();

        $response = $this->json('DELETE', "/api/dashboard/delete-vehicle-image", [
            'image' => $newestVehicleImage->image
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('vehicle_images', [
            'id' => $newestVehicleImage->id
        ]);

        $path = remove_storage_file_path($newestVehicleImage->image);

        Storage::disk('public')->assertMissing($path);
    }
}