<?php

namespace Tests;

use App\Models\Cv;
use App\Models\Jobadvertisement;
use App\Models\Jobapplication;
use App\Models\Jobseeker;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Felhasználó létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó felhasználó alapadatai.
     */
    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    /**
     * Authentikált felhasználó létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó felhasználó alapadatai.
     */
    public function createAuthenticatedUser($args = [])
    {
        $user = $this->createUser($args);
        Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Munkaadó létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó munkaadó alapadatai.
     */
    public function createEmployer($args = ['role' => 'employer'])
    {
        return User::factory()->create($args);
    }

    /**
     * Álláskereső létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó álláskereső alapadatai.
     */
    public function createJobseeker($args = ['role' => 'jobseeker'])
    {
        return User::factory()->create($args);
    }
    /**
     * Önéletrajz létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó önéletrajz alapadatai.
     */
    public function createCv($args = [])
    {
        return Cv::factory()->create($args);
    }
    /**
     * Álláshirdetés létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó álláshirdetés alapadatai.
     */
    public function createJobadvertisement($args = [])
    {
        return Jobadvertisement::factory()->create($args);
    }
    /**
     * Jelentkezés létrehozása a gyártófüggvény segítségével.
     * 
     * @param $args a létrehozandó jelentkezés alapadatai.
     */
    public function createJobapplication($args = [])
    {
        return Jobapplication::factory()->create($args);
    }
}
