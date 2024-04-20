<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration with valid data.
     *
     * @return void
     */
    public function testUserRegistrationWithValidData()
    {
        $response = $this->postJson('/api/authentication/register', [
            'name' => 'John Doe',
            'email' => 'john@apextest.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['id', 'name', 'email', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test user registration with invalid data (e.g., missing email).
     *
     * @return void
     */
    public function testUserRegistrationWithInvalidData()
    {
        $response = $this->postJson('/api/authentication/register', [
            'name' => 'John Doe',
            // Missing email
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['email']
                 ]);
    }

    /**
     * Test user registration with duplicate email.
     *
     * @return void
     */
    public function testUserRegistrationWithDuplicateEmail()
    {
        $user = User::factory()->create(['email' => 'john@apextest.com']);

        $response = $this->postJson('/api/authentication/register', [
            'name' => 'John Doe',
            'email' => 'john@apextest.com', // Existing email
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['email']
                 ]);
    }

    /**
 * Test updating user profile with empty parameters.
 *
 * @return void
 */
/**
 * Test updating user profile with empty parameters.
 *
 * @return void
 */
public function testUpdateUserProfileWithEmptyParameters()
{
    // Create a user
    $user = User::factory()->create();

    // Attempt to update the user profile with empty parameters
    $response = $this->actingAs($user)
                     ->putJson('/api/profile/update', []);

                     // Log the response for inspection

    // Assert that the response indicates validation errors
    $response->assertStatus(401)
             ->assertJsonStructure([
                 'status',
                 'message',
                 'data'
             ]);
}


// /**
//  * Test updating user password with wrong old password.
//  *
//  * @return void
//  */
// public function testUpdateUserPasswordWithWrongOldPassword()
// {
//     // Create a user with a hashed password
//     $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

//     // Authenticate the user
//     $this->actingAs($user);

//     // Attempt to update the user password with wrong old password
//     $response = $this->putJson('/api/profile/password', [
//         'current_password' => 'wrongpassword',
//         'new_password' => 'newpassword',
//     ]);

//     // Assert that the response indicates incorrect current password
//     $response->assertStatus(400)
//              ->assertJson([
//                  'status' => 'error',
//                  'message' => 'Current password is incorrect',
//                  'data' => null
//              ]);
// }

    /**
 * Test updating user profile when user is not logged in.
 *
 * @return void
 */
public function testUpdateUserProfileWhenNotLoggedIn()
{
    $response = $this->putJson('/api/profile/update', [
        'name' => 'New Name',
        'email' => 'newemail@apextest.com',
    ]);

    $response->assertStatus(401)
             ->assertJson([
                 'status' => 'error',
                 'message' => 'Unauthenticated.',
                 'data' => null
             ]);
}



}
