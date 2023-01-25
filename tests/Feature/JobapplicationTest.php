<?php

namespace Tests\Feature;

use App\Models\Jobadvertisement;
use App\Models\Jobapplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Cv;
use Tests\TestCase;

class JobapplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_jobapplication_can_be_added_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $jobapplication = Jobapplication::factory()->make([
            'user_id' => $jobseeker,
            'jobadvertisement_id' => Jobadvertisement::factory()->create()->id,
            'cv_id' => Cv::factory()->create()->id,
        ]);

        $response = $this->post(route('jobapplications.store'), $jobapplication->toArray());
        $response->assertCreated();
        $response->assertJson([
            'message' => 'Sikerült a jelentkezés létrehozása!'
        ]);
        $this->assertCount(1, Jobapplication::all());
        $this->assertDatabaseHas('jobapplications', ['status' => "Nincs megnézve"]);
    }
    /** @test */
    public function all_fields_of_a_jobapplication_are_required()
    {
        $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $response1 = $this->post(route('jobapplications.store'), ['user_id' => '']);
        $response1->assertSessionHasErrors('user_id');

        $response2 = $this->post(route('jobapplications.store'), ['jobadvertisement_id' => '']);
        $response2->assertSessionHasErrors('jobadvertisement_id');

        $response3 = $this->post(route('jobapplications.store'), ['cv_id' => '']);
        $response3->assertSessionHasErrors('cv_id');
    }
    /** @test */
    public function a_jobapplication_can_be_deleted_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $jobapplication = $this->createJobapplication(['user_id' => $jobseeker]);

        $response = $this->delete(route('jobapplications.destroy', $jobapplication->id));
        $this->assertCount(0, Jobapplication::all());
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült a jelentkezés törlése!'
        ]);
        $this->assertDatabaseMissing('jobapplications', ['id' => $jobapplication->id]);
    }
    /** @test */
    public function a_jobapplication_status_can_be_updated_by_an_authenticated_user()
    {
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $jobapplication = $this->createJobapplication(['user_id' => $jobseeker]);

        $response = $this->patch(route('jobapplications.status', $jobapplication), [
            'status' => 'Elfogadva.',
        ]);

        $this->assertEquals('Elfogadva.', Jobapplication::first()->status);
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült a jelentkezés állapotának módosítása!'
        ]);
    }
}
