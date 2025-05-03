<?php

namespace Tests\Feature;

use App\Models\NewUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_insert(): void
    {
        $user = NewUser::factory()->make([
            'password' => 'Hansara'
        ]);

        $rs = $this->post('/create-user', [
            'e' => $user->email,
            'ps' => $user->password,
            'add' => $user->address,
            'n' => $user->name,
        ]);

        $rs->assertSeeText("User Created Successfully");

        $this->assertDatabaseHas('new_users', [
            'name' => $user['name'],
            // 'password' => bcrypt("Hansara"),
            'address' => $user['address'],
            'email' => $user['email'],
        ]);
    }

    public function test_login(): void
    {
        $user = NewUser::factory()->create([
            'password' => bcrypt("Hansara"),
            'status' => "Active",
        ]);

        $rs = $this->post('/submit-login', [
            'email' => $user->email,
            'password' => 'Hansara',
        ]);

        $rs->assertSee("User Found");
    }

    public function test_editUserLoading(): void
    {
        $user = NewUser::factory()->create([
            'password' => bcrypt("Hasnara"),
            'status' => "Active",
        ]);

        $rs = $this->get('/edit-user?id=' . $user->id);

        // Log::channel('myLog')->info(json_encode($rs));

        $rs->assertViewIs("editUser");
    }

    public function test_update(): void
    {
        $user = NewUser::factory()->create();
        $userData = NewUser::factory()->make();

        $rs = $this->post('/update-user', [
            'uid' => $user->id,
            'email' => $userData->email,
            'password' => $userData->password,
            'address' => $userData->address,
            'name' => $userData->name,
        ]);

        $rs->assertSeeText("User Updated Successful");

        $this->assertDatabaseHas('new_users', [
            'name' => $userData['name'],
            'password' => $userData->password,
            'address' => $userData['address'],
            'email' => $userData['email'],
        ]);
    }

    public function test_delete(): void
    {
        $user = NewUser::factory()->create([
            'status' => "Active",
        ]);

        $rs = $this->get('/delete-user?id=' . $user->id);

        // Log::channel('myLog')->info(json_encode($rs));

        $rs->assertSeeText("Deletion Successful");

        $this->assertDatabaseHas('new_users', [
            'id' => $user['id'],
            'status' => "Deactive",
        ]);
    }

    

    protected function tearDown(): void
    {
        NewUser::truncate();
        parent::tearDown();
    }
}
