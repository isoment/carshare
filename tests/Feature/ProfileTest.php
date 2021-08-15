<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  Unauthorized users cannot access the update avatar api endpoint.
     */
    public function unauthorized_users_cannot_access_the_update_avatar_api_endpoint()
    {
        $this->json('POST', '/api/dashboard/update-avatar')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  An authorized user receives a 422 http response when no image is provided
     *  to update avatar.
     */
    public function an_authorized_user_receives_422_response_when_no_image_provided_to_update_avatar()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', '/api/dashboard/update-avatar')
            ->assertStatus(422);
    }

    /**
     *  @test
     *  The validation rejects non images on the update avatar endpoint.
     */
    public function validation_rejects_non_images_on_the_update_avatar_api_endpoint()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', '/api/dashboard/update-avatar', [
            'image' => 'String'
        ])->assertStatus(422);

        $this->json('POST', '/api/dashboard/update-avatar', [
            'image' => 63655
        ])->assertStatus(422);

        $this->json('POST', '/api/dashboard/update-avatar', [
            'image' => ['test', 'array']
        ])->assertStatus(422);

        $fakePDF = UploadedFile::fake()->create(
            'fake.pdf', '50000', 'application/pdf'
        );

        $this->json('POST', '/api/dashboard/update-avatar', [
            'image' => $fakePDF
        ])->assertStatus(422);
    }

    /**
     *  @test
     *  An image file is accepted and uploaded successfully.
     */
    public function an_image_file_is_accepted_and_uploaded_successfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $image = UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(200);

        $this->json('POST', '/api/dashboard/update-avatar', [
            'image' => $image
        ])->assertStatus(200);

        $this->assertTrue(Str::contains($user->profile->image, '.jpeg'));
    }

    /**
     *  @test
     *  Unauthorized users cannot access the update profile api endpoint.
     */
    public function unauthorized_users_cannot_access_the_update_profile_api_endpoint()
    {
        $this->json('PUT', '/api/dashboard/update-profile')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  An authorized user receives a 204 status when no data is provided to the
     *  profile update api endpoint
     */
    public function authorized_users_receive_204_status_when_no_data_is_passed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('PUT', '/api/dashboard/update-profile', [])
            ->assertStatus(204);
    }

    /**
     *  @test
     *  An authorized user can update their profile
     */
    public function an_authorized_user_can_update_their_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $dataArray = [
            'location' => 'Toronto, CA',
            'languages' => 'English, French',
            'work' => 'Software Engineer',
            'school' => 'Harvard',
            'about' => 'This is my profile, there are many profiles but this is mine.'
        ];

        $this->json('PUT', '/api/dashboard/update-profile', $dataArray)->assertStatus(204);

        $withUserId = array_merge($dataArray, ['user_id' => $user->id]);

        $this->assertDatabaseHas('profiles', $withUserId);
    }
}
