<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DriversLicenseTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  Unauthorized users cannot access the create driverse license api endpoint.
     */
    public function unauthorized_users_cannot_access_the_create_drivers_license_api()
    {
        $this->json('POST', '/api/dashboard/create-drivers-license')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  An authorized user receives a 422 error when providing no data to the endpoint
     */
    public function an_authorized_user_receives_422_error_when_proving_no_data_to_the_endpoint()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', '/api/dashboard/create-drivers-license')
            ->assertStatus(422);
    }

    /**
     *  @test
     *  An authorized user can submit a drivers license
     */
    public function an_authorized_user_can_submit_a_drivers_license()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $data = $this->validLicenseData();

        $this->json('POST', '/api/dashboard/create-drivers-license', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('drivers_licenses', [
            'user_id' => $user->id,
            'number' => $data['license_number'],
            'state' => $data['state'],
            'issued' => $data['date_issued'],
            'expiration' => $data['expiration_date'],
            'dob' => $data['birthdate'],
            'street' => $data['street'],
            'city' => $data['city'],
            'zip' => $data['zip']
        ]);
    }

    /**
     *  @test
     *  File types other than images are not allowed
     */
    public function file_types_other_than_images_are_not_allowed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('fake.pdf', 500000, 'application/pdf');

        $data = $this->validLicenseData(['license_image' => $pdf]);

        $this->json('POST', '/api/dashboard/create-drivers-license', $data)
            ->assertStatus(422);
    }

    /**
     *  @test
     *  Invalid state name returns 422 validation error
     */
    public function invalid_state_name_returns_422_validation_error()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $data = $this->validLicenseData(['state' => 'Fake State']);

        $this->json('POST', '/api/dashboard/create-drivers-license', $data)
            ->assertStatus(422);
    }

    /**
     *  @test
     *  A drivers license can be updated
     */
    public function a_drivers_license_can_be_updated()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $data = $this->validLicenseData();

        $this->json('POST', '/api/dashboard/create-drivers-license', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('drivers_licenses', [
            'user_id' => $user->id,
            'number' => $data['license_number'],
            'street' => $data['street'],
            'city' => $data['city'],
            'zip' => $data['zip']
        ]);

        $updatedData = $this->validLicenseData([
            'license_number' => 'il-3281-213-18837',
            'street' => '987 New Dr',
            'city' => 'Newburg',
            'zip' => '11111'
        ]);

        $this->json('POST', '/api/dashboard/create-drivers-license', $updatedData)
            ->assertStatus(201);

        $this->assertDatabaseHas('drivers_licenses', [
            'user_id' => $user->id,
            'number' => $updatedData['license_number'],
            'street' => $updatedData['street'],
            'city' => $updatedData['city'],
            'zip' => $updatedData['zip']
        ]);
    }
}
