<?php

namespace Tests\Feature\Http\Controllers\Api;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Type of testing function
     * "test" in function name
     * or php doc comment "@test" above the function
     */
    /** TESTING FUNCTION TYPE #1 */
    // public function test_store_data()
    // {
    //     //TODO: code inside here --Created by Kiddy
    // }

    /** TESTING FUNCTION TYPE #2 */
    // /**
    //  * @test
    //  */
    // public function it_stores_data()
    // {
    //     //TODO: code inside here --Created by Kiddy
    // }

    /**
     * @test
     */
    public function it_stores()
    {
        //Membuat objek user yang otomatis menambahkannya ke database.
        // $user = factory(User::class)->create(); // old way
        $user = User::factory()->create();

        //Acting as berfungsi sebagai autentikasi, jika kita menghilangkannya maka akan error.
        $response = $this->actingAs($user)
            //Hit post ke method store, fungsinya ya akan lari ke fungsi store.
            ->post(route('user.store'), [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => '12345678'
            ]);

        // $response->assertTrue(); // error, i think this is only for web requests, not json
        $response->assertStatus(201); // expected status code
    }

    /**
     * @test
     */
    public function it_stores_invalid_input()
    {
        //Membuat objek user yang otomatis menambahkannya ke database.
        // $user = factory(User::class)->create(); // old way
        $user = User::factory()->create();

        //Acting as berfungsi sebagai autentikasi, jika kita menghilangkannya maka akan error.
        $response = $this->actingAs($user)
            //Hit post ke method store, fungsinya ya akan lari ke fungsi store.
            ->post(route('user.store'), [
                'name' => '', // error required
                'email' => $this->faker->unique()->safeEmail(),
                'passwordddddd' => '12345678', // error field password name, required
            ]);

        /** METHODOLOGY #1 EASIEST */
        // $response->assertUnprocessable(); // expected json response with status code 422 (unprocessable entity)

        /** METHODOLOGY #2 ONLY FOR WEB REQUEST, NOT JSON */
        // For example, to assert that the name and email fields have validation error messages that were flashed to the session, you may invoke the assertSessionHasErrors method like so:
        // $response->assertSessionHasErrors(['name', 'password']);

        /** #2 BUT WITH JSON */
        $response->assertJsonValidationErrors(['name', 'password']);
    }
}
