<?php

namespace App\Http\Controllers;

use App\Http\Actions\Driver\CreateDriver;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DriverResource::collection(Driver::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request, CreateDriver $action)
    {
        try {
            $driver = $action->handle($request->validated());
            return new DriverResource($driver);
        } catch (\Throwable $th) {
            //throw $th;
        }
        // try {
        //     $league = $action->handle($request->validated());
        //     return new LeagueResource($league);
        // } catch (Exception $ex) {
        //     abort(500, 'Could not create league or assign it to a league manager.');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
