<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class EmployerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_employer_can_be_added_by_an_unauthenticated_user()
    {
        $employer = User::factory()->make([
            'password_confirmation' => '123123123123',
            'accept1' => true,
            'accept2' => true
        ]);

        $response = $this->post(route('employers.store'), $employer->toArray());
        $response->assertCreated();
        $response->assertJson([
            'message' => 'Sikerült a munkaadó létrehozása!'
        ]);
        $this->assertCount(1, User::where('email', $employer->email)->get());
        $this->assertDatabaseHas('users', ['email' => $employer->email]);
    }
    /** @test */
    public function all_fields_of_an_employer_are_required()
    {
        $response1 = $this->post(route('employers.store'), ['name' => '']);
        $response1->assertSessionHasErrors('name');

        $response2 = $this->post(route('employers.store'), ['address' => '']);
        $response2->assertSessionHasErrors('address');
        
        $response3 = $this->post(route('employers.store'), ['telephone' => '']);
        $response3->assertSessionHasErrors('telephone');
        
        $response4 = $this->post(route('employers.store'), ['email' => '']);
        $response4->assertSessionHasErrors('email');
        
        $response5 = $this->post(route('employers.store'), ['password' => '']);
        $response5->assertSessionHasErrors('password');
        
        $response6 = $this->post(route('employers.store'), ['accept1' => '']);
        $response6->assertSessionHasErrors('accept1');
        
        $response7 = $this->post(route('employers.store'), ['accept2' => '']);
        $response7->assertSessionHasErrors('accept2');
    }
    /** @test */
    public function an_employer_can_be_updated_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);

        $response = $this->put(route('employers.update', $employer->id), [
            'id' => $employer->id,
            'name' => 'Új Munkaadó',
            'address' => 'Cím',
            'telephone' => '+36-00-123-456',
        ]);
        $this->assertEquals('Új Munkaadó', User::first()->name);
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült a munkaadó módosítása!'
        ]);
    }
    /** @test */
    public function an_employer_can_be_deleted_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);

        $response = $this->delete(route('employers.destroy', $employer));
        $this->assertCount(0, User::all());
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült a munkaadó törlése!'
        ]);
        $this->assertDatabaseMissing('users', ['email' => $employer->email]);
    }
    /** @test */
    public function all_employers_can_be_get_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);

        $response = $this->get(route('employers.index'));
        $this->assertCount(1, User::all());
        $this->assertEquals($employer['email'], $response[0]['email']);
    }
    /** @test */
    public function an_employer_can_be_get_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);

        $response = $this->getJson(route('employers.show', $employer));
        $response->assertOk();
        $this->assertEquals($response['email'], $employer->email);
    }
    /** @test */
    public function an_authenticated_employer_can_get_the_jobadvertisements_created_by_the_employer()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);
        $jobadvertisement = $this->createJobadvertisement(['user_id' => $employer]);

        $response = $this->getJson(route('employers.jobadvertisements', $employer));
        $response->assertOk();
        $this->assertEquals($response[0]['title'], $jobadvertisement->title);
    }
}
