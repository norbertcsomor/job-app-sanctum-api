<?php

namespace Tests\Feature;

use App\Models\Cv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_cv_can_be_added_by_an_authenticated_user()
    {
        Storage::fake('public');
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $file = File::fake()->create('cv.pdf', 2000);
        $cv = Cv::factory()->make([
            'user_id' => $jobseeker,
            'title' => 'Teszt Önéletrajz',
            'path' => $file
        ]);

        $response = $this->post(route('cvs.store'), $cv->toArray());
        $response->assertCreated();
        $response->assertJson([
            'message' => 'Sikerült az önéletrajz létrehozása!'
        ]);

        $cv = Cv::first();

        $this->assertCount(1, Cv::all());
        $this->assertNotNull($cv->path);
        $this->assertFileEquals($file, Storage::disk('public')->path($cv->path));
        $this->assertEquals('Teszt Önéletrajz', $cv->title);
        $this->assertDatabaseHas('cvs', ['title' => $cv->title]);
        Storage::disk('public')->assertExists($cv->path);
    }
    /** @test */
    public function all_fields_of_a_cv_are_required()
    {
        $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $response1 = $this->post(route('cvs.store'), ['user_id' => '']);
        $response1->assertSessionHasErrors('user_id');

        $response2 = $this->post(route('cvs.store'), ['title' => '']);
        $response2->assertSessionHasErrors('title');

        $response3 = $this->post(route('cvs.store'), ['path' => '']);
        $response3->assertSessionHasErrors('path');
    }
    /** @test */
    public function a_cv_can_be_deleted_by_an_authenticated_user()
    {
        Storage::fake('public');
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);
        $file = File::fake()->create('cv.pdf', 2000);
        $cv = Cv::factory()->make([
            'user_id' => $jobseeker,
            'title' => 'Teszt Önéletrajz',
            'path' => $file
        ]);

        $response = $this->post(route('cvs.store'), $cv->toArray());
        $response->assertCreated();
        $response->assertJson([
            'message' => 'Sikerült az önéletrajz létrehozása!'
        ]);

        $cv = Cv::first();

        // dd($cv);

        $response = $this->delete(route('cvs.destroy', $cv->id));

        $this->assertCount(0, Cv::all());
        $response->assertOk();
        $response->assertJson([
            'message' => 'Sikerült az önéletrajz törlése!'
        ]);
        $this->assertDatabaseMissing('cvs', ['title' => $cv->title]);
        Storage::disk('public')->assertMissing($cv->path);
    }

    public function a_cv_can_be_downloaded_by_an_authenticated_user()
    {
        $storage = Storage::fake('public');
        $jobseeker = $this->createAuthenticatedUser(['role' => 'jobseeker']);

        $file = File::fake()->create('cv.pdf', 2000);
        
        $cv = Cv::create([
            'user_id' => $jobseeker,
            'title' => 'Teszt Önéletrajz',
            'path' => $file->store('cvs', 'public')
        ]);
        
        // $response = $this->post(route('cvs.store'), $postedCv);

        $cvInDb = Cv::first();

        $response = $this->get(route('cvs.show', $cvInDb->id));
        // dd($response->getContent());
        $response->assertOk();

        /*          Storage::shouldReceive('disk')
            ->with('public')
            ->andReturn($storage);
        Storage::shouldReceive('download')
            ->with($cv->path)
            ->andReturn($file); */

        // ugyanez
        // Storage::disk('public')->download($cv->path);
        // $this->assertEquals($response['data']['title'], $cv->title);
    }
}
