<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployerRequest;
use App\Http\Requests\UpdateEmployerRequest;
use App\Http\Resources\EmployerResource;
use App\Models\User;
use App\Models\Jobadvertisement;
use Illuminate\Http\Response;

class EmployerController extends Controller
{
    /**
     * Az összes munkaadó lekérdezése az adatbázisból.
     *
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function index()
    {
        return User::where('role', 'employer')
            ->get()
            ->toArray();
    }

    /**
     * Új munkaadó létrehozása az adatbázisban. 
     *
     * @param  \App\Http\Requests\StoreEmployerRequest  $request a létrehozandó munkaadó adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function store(StoreEmployerRequest $request)
    {
        $employer = new User();
        $employer->name = $request->input('name');
        $employer->address = $request->input('address');
        $employer->telephone = $request->input('telephone');
        $employer->email = $request->input('email');
        $employer->password = bcrypt($request->input('password'));
        $employer->role = 'employer';

        $success = $employer->save();

        if ($success) {
            return response([
                'message' => 'Sikerült a munkaadó létrehozása!'
            ], Response::HTTP_CREATED)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült a munkaadó létrehozása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Létező munkaadó lekérdezése az adatbázisból.
     *
     * @param  \App\Models\User  $employer a lekérendő munkaadó adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function show(User $employer)
    {
        return new EmployerResource($employer);
    }

    /**
     * Létező munkaadó módosítása az adatbázisban.
     *
     * @param  \App\Http\Requests\UpdateEmployerRequest  $request a módosítandó munkaadó adatai.
     * @param  \App\Models\User  $employer a munkaadó modellje.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function update(UpdateEmployerRequest $request, User $employer)
    {
        $success = $employer->update($request->validated());

        if ($success) {
            return response([
                'message' => 'Sikerült a munkaadó módosítása!'
            ], Response::HTTP_OK);
        } else {
            return response([
                'message' => 'Nem sikerült a munkaadó módosítása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Létező munkaadó törlése az adatbázisból.
     *
     * @param  \App\Models\User  $employer a törlendő munkaadó adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function destroy(User $employer)
    {
        $success = $employer->destroy($employer->id);

        if ($success) {
            return response([
                'message' => 'Sikerült a munkaadó törlése!'
            ], Response::HTTP_OK)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült a munkaadó törlése!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /** 
     * A munkaadó összes álláshirdetésének lekérdezése az adatbázisból.
     * 
     * @param  \App\Models\User  employer a munkaadó adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function jobadvertisements($employer)
    {
        return Jobadvertisement::where('user_id', $employer)
            ->get()
            ->toArray();
    }
}
