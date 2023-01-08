<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobapplicationRequest;
use App\Http\Requests\UpdateJobapplicationRequest;
use App\Http\Resources\JobapplicationResource;
use App\Models\Jobapplication;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class JobapplicationController extends Controller
{
    /**
     * Az összes jelentkezés lekérdezése az adatbázisból.
     *
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function index()
    {
        $jobadvertisements = DB::table('jobadvertisements')
            ->leftJoin('jobapplications', 'jobadvertisements.id', '=', 'jobapplications.jobadvertisement_id')
            ->leftJoin('jobseekers', 'jobseekers.id', '=', 'jobapplications.user_id')
            ->orderBy('created_at', 'desc')->get();
        return $jobadvertisements;
    }

    /**
     * Új jelentkezés létrehozása az adatbázisban. 
     *
     * @param  \App\Http\Requests\StoreJobapplicationRequest  $request a létrehozandó jelentkezés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function store(StoreJobapplicationRequest $request)
    {
        $jobapplication = new Jobapplication();
        $jobapplication->user_id = $request->input('user_id');
        $jobapplication->jobadvertisement_id = $request->input('jobadvertisement_id');
        $jobapplication->cv_id = $request->input('cv_id');
        $jobapplication->status = $request->input('status');

        $success = $jobapplication->save();

        if ($success) {
            return response([
                'message' => 'Sikerült a jelentkezés létrehozása!',
                'jobapplication' => $jobapplication
            ], Response::HTTP_CREATED)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült a jelentkezés létrehozása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Létező jelentkezés lekérdezése az adatbázisból.
     *
     * @param  \App\Models\Jobapplication  $jobapplication az jelentkezés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function show(Jobapplication $jobapplication)
    {
        return new JobapplicationResource($jobapplication);
    }

    /**
     * Létező jelentkezés törlése az adatbázisból.
     *
     * @param  \App\Models\Jobapplication  $jobapplication a törlendő jelentkezés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function destroy(Jobapplication $jobapplication)
    {
        $success = $jobapplication->destroy($jobapplication->id);
        
        if ($success) {
            return response([
                'message' => 'Sikerült a jelentkezés törlése!'
            ], Response::HTTP_OK)
            ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült a jelentkezés törlése!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
            ->header('Content-Type', 'application/json');
        }
    }
    
    /**
     * Jelentkezés állapotának módosítása.
     * 
     * @param  \App\Http\Requests\StoreJobapplicationRequest  $request a létrehozandó jelentkezés adatai.
     * @param  \App\Models\Jobapplication  $jobapplication a müdosítandó jelentkezés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function status(UpdateJobapplicationRequest $request, $jobapplication)
    {
        if ($request->input('status') == ("Elfogadva" || "Elutasítva")) {

            $jobapplication = Jobapplication::find($jobapplication);
            $jobapplication->status = $request->input('status');

            $success = $jobapplication->update();

            if ($success) {
                return response([
                    'message' => 'Sikerült a jelentkezés állapotának módosítása!'
                ], Response::HTTP_OK);
            } else {
                return response([
                    'message' => 'Nem sikerült a jelentkezés állapotának módosítsa!'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
