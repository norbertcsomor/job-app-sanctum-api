<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\Jobadvertisement;
use App\Models\User;
use Tests\TestCase;

class JobadvertisementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_jobadvertisement_can_be_added_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);

        $jobadvertisement = Jobadvertisement::factory()->make([
            'user_id' => $employer,
            'title' => 'Teszt Álláshirdetés',
            'location' => 'Helyszín',
            'description' => 'Leírás',
        ]);

        $response = $this->post(route('jobadvertisements.store'), $jobadvertisement->toArray());

        $response->assertCreated();
        $response->assertJson([
            'message' => 'Sikerült az álláshirdetés létrehozása!'
        ]);
        $this->assertCount(1, Jobadvertisement::all());
        $this->assertDatabaseHas('jobadvertisements', ['title' => $jobadvertisement->title]);
    }
    /** @test */
    public function all_fields_of_a_jobadvertisement_are_required()
    {
        $this->createAuthenticatedUser(['role' => 'employer']);

        $response1 = $this->post(route('jobadvertisements.store'), ['user_id' => '']);
        $response1->assertSessionHasErrors('user_id');

        $response2 = $this->post(route('jobadvertisements.store'), ['title' => '']);
        $response2->assertSessionHasErrors('title');

        $response3 = $this->post(route('jobadvertisements.store'), ['location' => '']);
        $response3->assertSessionHasErrors('location');

        $response4 = $this->post(route('jobadvertisements.store'), ['description' => '']);
        $response4->assertSessionHasErrors('description');
    }
    /** @test */
    public function a_jobadvertisement_can_be_updated_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);
        $jobadvertisement = Jobadvertisement::factory()->create(['user_id' => $employer]);

        $response = $this->put(route('jobadvertisements.update', $jobadvertisement), [
            'title' => 'Új Álláshirdetés',
            'location' => 'Helyszín',
            'description' => 'Leírás',
        ]);

        $this->assertEquals('Új Álláshirdetés', Jobadvertisement::first()->title);
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült az álláshirdetés módosítása!'
        ]);
    }
    /** @test */
    public function a_jobadvertisement_can_be_deleted_by_an_authenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);
        $jobadvertisement = Jobadvertisement::factory()->create(['user_id' => $employer]);

        $response = $this->delete(route('jobadvertisements.destroy', $jobadvertisement->id));
        $this->assertCount(0, Jobadvertisement::all());
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült az álláshirdetés törlése!'
        ]);
        $this->assertDatabaseMissing('jobadvertisements', ['name' => $jobadvertisement->name]);
    }
    /** @test */
    public function all_jobadvertisements_can_be_get_by_an_unauthenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);
        Jobadvertisement::factory()->create(['user_id' => $employer]);

        $response = $this->get(route('jobadvertisements.index'));
        $this->assertCount(1, Jobadvertisement::all());
        $this->assertEquals('Teszt Álláshirdetés', $response[0]['title']);
    }
    /** @test */
    public function a_jobadvertisement_can_be_get_by_an_unauthenticated_user()
    {
        $employer = $this->createAuthenticatedUser(['role' => 'employer']);
        $jobadvertisement = Jobadvertisement::factory()->create(['user_id' => $employer]);
        
        $response = $this->getJson(route('jobadvertisements.show', $jobadvertisement->id));
        $response->assertOk();
        $this->assertEquals($response['data']['title'], $jobadvertisement->title);
    }
}
