<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobseekerRequest;
use App\Http\Requests\UpdateJobseekerRequest;
use App\Http\Resources\JobseekerResource;
use App\Models\Cv;
use App\Models\Jobapplication;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class JobseekerController extends Controller
{
    /**
     * Az összes álláskereső lekérdezése az adatbázisból.
     *
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function index()
    {
        return User::where('role', 'jobseeker')->get()->toArray();
    }

    /**
     * Új álláskereső létrehozása az adatbázisban. 
     *
     * @param  \App\Http\Requests\StoreJobseekerRequest  $request a létrehozandó álláskereső adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function store(StoreJobseekerRequest $request)
    {
        $jobseeker = new User();
        $jobseeker->name = $request->input('name');
        $jobseeker->address = $request->input('address');
        $jobseeker->telephone = $request->input('telephone');
        $jobseeker->email = $request->input('email');
        $jobseeker->password = bcrypt($request->input('password'));
        $jobseeker->role = 'jobseeker';

        $success = $jobseeker->save();

        if ($success) {
            return response([
                'message' => 'Sikerült az álláskereső létrehozása!'
            ], Response::HTTP_CREATED)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az álláskereső létrehozása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Létező álláskereső lekérdezése az adatbázisból.
     *
     * @param  \App\Models\User  $jobseeker a lekérendő álláskereső adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function show(User $jobseeker)
    {
        return new JobseekerResource($jobseeker);
    }

    /**
     * Létező álláskereső módosítása az adatbázisban.
     *
     * @param  \App\Http\Requests\UpdateJobseekerRequest  $request a módosítandó álláskereső adatai.
     * @param  \App\Models\User  $jobseeker az álláskereső
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function update(UpdateJobseekerRequest $request, User $jobseeker)
    {
        $success = $jobseeker->update($request->all());

        if ($success) {
            return response([
                'message' => 'Sikerült az álláskereső módosítása!'
            ], Response::HTTP_OK);
        } else {
            return response([
                'message' => 'Nem sikerült az álláskereső módosítása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Létező álláskereső törlése az adatbázisból.
     *
     * @param  \App\Models\User  $jobseeker a törlendő álláskereső adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function destroy(User $jobseeker)
    {
        $success = $jobseeker->destroy($jobseeker->id);

        if ($success) {
            return response([
                'message' => 'Sikerült az álláskereső törlése!'
            ], Response::HTTP_OK)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az álláskereső törlése!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /** 
     * Az álláskereső összes önéletrajzának lekérdezése az adatbázisból.
     * 
     * @param \App\Models\User  $jobseeker az álláskereső adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function cvs($jobseeker)
    {
        return Cv::where('user_id', $jobseeker)
            ->get()
            ->toArray();
    }

    /** 
     * Az álláskereső összes jelentkezésének lekérdezése az adatbázisból.
     * 
     * @param \App\Models\User  $jobseeker az álláskereső adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function jobapplications($jobseeker)
    {
        return Jobapplication::with('jobadvertisement', 'cv')
            ->where('user_id', $jobseeker)
            ->get()
            ->toArray();
    }

    /** 
     * Az álláskereső adott álláshirdetésre történő jelentkezésének lekérdezése az adatbázisból.
     * 
     * @param \App\Models\User  $jobseeker az álláskereső adatai.
     * @param \App\Models\Jobadvertisement  $jobadvertisement az álláshirdetés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function jobseeker_jobapplications($jobseeker, $jobadvertisement)
    {
        return DB::table('jobapplications')
            ->where('user_id', $jobseeker)
            ->where('jobadvertisement_id', $jobadvertisement)
            ->get()
            ->toArray();
    }
}
