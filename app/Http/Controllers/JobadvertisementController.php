<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobadvertisementRequest;
use App\Http\Requests\UpdateJobadvertisementRequest;
use App\Http\Resources\JobadvertisementResource;
use App\Models\Jobadvertisement;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Jobapplication;

class JobadvertisementController extends Controller
{
    /**
     * Az összes álláshirdetés lekérdezése az adatbázisból.
     *
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function index()
    {
        return Jobadvertisement::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Új álláshirdetés létrehozása az adatbázisban. 
     *
     * @param  \App\Http\Requests\StoreJobadvertisementRequest  $request a létrehozandó álláshirdetés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function store(StoreJobadvertisementRequest $request)
    {
        $jobadvertisement = new Jobadvertisement();
        $jobadvertisement->user_id = $request->input('user_id');
        $jobadvertisement->title = $request->input('title');
        $jobadvertisement->location = $request->input('location');
        $jobadvertisement->description = $request->input('description');

        $success = $jobadvertisement->save();

        if ($success) {
            return response([
                'message' => 'Sikerült az álláshirdetés létrehozása!',
                'jobadvertisement' => $jobadvertisement
            ], Response::HTTP_CREATED)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az álláshirdetés létrehozása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Létező álláshirdetés lekérdezése az adatbázisból.
     *
     * @param  \App\Models\Jobadvertisement  $jobadvertisement az álláshirdetés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function show(Jobadvertisement $jobadvertisement)
    {
        return new JobadvertisementResource($jobadvertisement);
    }

    /**
     * Létező álláshirdetés módosítása az adatbázisban.
     *
     * @param  \App\Http\Requests\UpdateJobadvertisementRequest  $request a módosítandó álláshirdetés adatai.
     * @param  \App\Models\Jobadvertisement  $jobadvertisement
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function update(UpdateJobadvertisementRequest $request, Jobadvertisement $jobadvertisement)
    {
        $success = $jobadvertisement->update($request->validated());

        if ($success) {
            return response([
                'message' => 'Sikerült az álláshirdetés módosítása!'
            ], Response::HTTP_OK)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az álláshirdetés módosítása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Létező álláshirdetés törlése az adatbázisból.
     *
     * @param  \App\Models\Jobadvertisement  $jobadvertisement a törlendő álláshirdetés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function destroy(Jobadvertisement $jobadvertisement)
    {
        $success = $jobadvertisement->destroy($jobadvertisement->id);

        if ($success) {
            return response([
                'message' => 'Sikerült az álláshirdetés törlése!'
            ], Response::HTTP_OK)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az álláshirdetés törlése!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /** 
     * Az álláshirdetés összes jelentkezésének lekérdezése az adatbázisból.
     * 
     * @param \App\Models\Jobadvertisement $jobadvertisement az álláshirdetés adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function jobapplications($jobadvertisement)
    {
		$jobapplications = Jobapplication::with(['cv', 'jobadvertisement', 'user'])->where('jobadvertisement_id', $jobadvertisement)->get()->toArray();
        return $jobapplications;
    }
}
