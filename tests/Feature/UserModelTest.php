<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserModelTest extends TestCase
{
    use RefreshDatabase;

     /**
     * Test that you can create a new user instance and save it to the database.
     * Test that the attributes of the created user match the provided data.
     */
    public function testUserCreation()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@apex-api.com',
            'password' => 'password123',
            'roles' => ['user'], // Roles are stored as an array
        ];

        // Create a new user
        $user = User::create($userData);

        // Assert that the user exists in the database with the specified attributes
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        // Assert that the user's roles are correctly assigned
        $this->assertTrue($user->hasRole('user'));
        $this->assertFalse($user->isAdmin()); // Assuming 'user' role does not imply 'admin' role
    }

    /**
     * Test Password Hashing: Ensure that passwords are properly hashed before being stored in the database.
     *
     * @return void
     */
    public function testPasswordHashing()
    {
        // Create a new user with a plain text password
        $plainPassword = 'password123';
        $user = User::factory()->create(['password' => $plainPassword]);

        // Retrieve the user from the database
        $userFromDatabase = User::find($user->id);

        // Assert that the stored password is hashed
        $this->assertTrue(Hash::check($plainPassword, $userFromDatabase->password));
    }

    /**
     * Test Role Assignment: Test whether users can have multiple roles and whether roles are correctly assigned and removed.
     *
     * @return void
     */
    public function testRoleAssignment()
    {
        // Create a new user with no roles
        $user = User::factory()->create();

        // Assign a role to the user
        $user->assignRole('admin');

        // Assert that the user has the assigned role
        $this->assertTrue($user->hasRole('admin'));

        // Remove the role from the user
        $user->removeRole('admin');

        // Assert that the user no longer has the removed role
        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test User Updates: Test whether user details can be updated successfully, including name, email, password, and roles.
     *
     * @return void
     */
    public function testUserUpdates()
    {
        // Create a new user
        $user = User::factory()->create();

        // Update the user details
        $newName = 'Jane Doe';
        $newEmail = 'jane@example.com';
        $newPassword = 'newpassword';
        $user->update([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $newPassword,
        ]);

        // Retrieve the updated user from the database
        $updatedUser = User::find($user->id);

        // Assert that the user details have been updated correctly
        $this->assertEquals($newName, $updatedUser->name);
        $this->assertEquals($newEmail, $updatedUser->email);
        $this->assertTrue(Hash::check($newPassword, $updatedUser->password));
    }
}
