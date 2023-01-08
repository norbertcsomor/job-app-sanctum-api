<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCvRequest;
use App\Models\Cv;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    /**
     * Új önéletrajz létrehozása az adatbázisban.
     *
     * @param  \App\Http\Requests\StoreCvRequest  $request a létrehozandó önéletrajz adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function store(StoreCvRequest $request)
    {
        $cv = new  Cv();
        $cv->user_id = $request->input('user_id');
        $cv->title = $request->input('title');
        if ($request->hasFile('path')) {
            $cv->path = $request->file('path')->store('cvs', 'public');
        }
        $success = $cv->save();

        if ($success) {
            return response([
                'message' => 'Sikerült az önéletrajz létrehozása!',
                'cv' => $cv
            ], Response::HTTP_CREATED)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az önéletrajz létrehozása!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Letölt egy adott önéletrajzot a szerverről.
     *
     * @param  \App\Models\Cv  $cv az önéletrajz adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function show(Cv $cv)
    {
        $file = Storage::disk('public')->download($cv->path);
        return $file;
    }

    /**
     * Létező önéletrajz törlése az adatbázisból.
     *
     * @param  \App\Models\Cv  $cv a törlendő önéletrajz adatai.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function destroy(Cv $cv)
    {
        $cv = Cv::find($cv->id);
        Storage::disk('public')->delete($cv->path);

        $success = $cv->delete();

        if ($success) {
            return response([
                'message' => 'Sikerült az önéletrajz törlése!'
            ], Response::HTTP_OK)
                ->header('Content-Type', 'application/json');
        } else {
            return response([
                'message' => 'Nem sikerült az önéletrajz törlése!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'application/json');
        }
    }
}
