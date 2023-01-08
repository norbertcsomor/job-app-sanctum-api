<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Cv;
use App\Models\Jobadvertisement;
use App\Models\Jobapplication;
use App\Models\Jobseeker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JobseekerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_jobseeker_can_be_added_by_an_unauthenticated_user()
    {
        $jobseeker = User::factory()->make([
            'password_confirmation' => '123123123123',
            'accept1' => true,
            'accept2' => true
        ]);

        $response = $this->post(route('jobseekers.store'), $jobseeker->toArray());
        $response->assertCreated();
        $response->assertJson([
            'message' => 'Sikerült az álláskereső létrehozása!'
        ]);
        $this->assertCount(1, User::where('email', $jobseeker->email)->get());
        $this->assertDatabaseHas('users', ['email' => $jobseeker->email]);
    }
    /** @test */
    public function all_fields_of_a_jobseeker_are_required()
    {
        $response1 = $this->post(route('jobseekers.store'), ['name' => '']);
        $response1->assertSessionHasErrors('name');

        $response2 = $this->post(route('jobseekers.store'), ['address' => '']);
        $response2->assertSessionHasErrors('address');

        $response3 = $this->post(route('jobseekers.store'), ['telephone' => '']);
        $response3->assertSessionHasErrors('telephone');

        $response4 = $this->post(route('jobseekers.store'), ['email' => '']);
        $response4->assertSessionHasErrors('email');

        $response5 = $this->post(route('jobseekers.store'), ['password' => '']);
        $response5->assertSessionHasErrors('password');

        $response6 = $this->post(route('jobseekers.store'), ['accept1' => '']);
        $response6->assertSessionHasErrors('accept1');

        $response7 = $this->post(route('jobseekers.store'), ['accept2' => '']);
        $response7->assertSessionHasErrors('accept2');
    }
    /** @test */
    public function a_jobseeker_can_be_updated_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $response = $this->put(route('jobseekers.update', $jobseeker->id), [
            'name' => 'Új Álláskereső',
            'address' => 'Cím',
            'telephone' => '+36-00-123-456',
        ]);
        $this->assertEquals('Új Álláskereső', User::first()->name);
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült az álláskereső módosítása!'
        ]);
    }
    /** @test */
    public function a_jobseeker_can_be_deleted_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $response = $this->delete(route('jobseekers.destroy', $jobseeker));
        $this->assertCount(0, User::all());
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült az álláskereső törlése!'
        ]);
        $this->assertDatabaseMissing('users', ['email' => $jobseeker->email]);
    }
    /** @test */
    public function all_jobseekers_can_be_get_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $response = $this->get(route('jobseekers.index'));
        $this->assertCount(1, User::all());
        $this->assertEquals($jobseeker['email'], $response[0]['email']);
    }
    /** @test */
    public function a_jobseeker_can_be_get_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $response = $this->getJson(route('jobseekers.show', $jobseeker));
        $response->assertOk();
        $this->assertEquals($response['data']['email'], $jobseeker->email);
    }

    /** @test */
    public function an_authenticated_jobseeker_can_get_the_cvs_created_by_the_jobseeker()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $cv = $this->createCv(['user_id' => $jobseeker]);

        $response = $this->getJson(route('jobseekers.cvs', $jobseeker));
        $response->assertOk();
        $this->assertEquals($response[0]['title'], $cv->title);
    }

    /** @test */
    public function an_authenticated_jobseeker_can_get_the_jobapplications_created_by_the_jobseeker()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $jobapplication = $this->createJobapplication(['user_id' => $jobseeker]);

        $response = $this->getJson(route('jobseekers.jobapplications', $jobseeker));
        $response->assertOk();
        $this->assertEquals($response[0]['status'], $jobapplication->status);
    }

    /** @test */
    public function an_authenticated_jobseeker_can_get_the_jobapplications_created_by_the_jobseeker_for_a_jobadvertisement()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $jobadvertisement = $this->createJobadvertisement();
        $jobapplication = $this->createJobapplication(['user_id' => $jobseeker, 'jobadvertisement_id' => $jobadvertisement]);

        $response = $this->getJson(route('jobseekers.jobseeker_jobapplications', [$jobseeker, $jobadvertisement]));
        $response->assertOk();
        $this->assertEquals($response[0]['status'], $jobapplication->status);
    }
}
