<?php

namespace App\Http\Controllers;

use App\Http\Actions\Driver\CreateDriver;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Exception;
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

            $this->authorize('create', $driver);

            return new DriverResource($driver);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $league = Driver::findOrFail($id);
            return new DriverResource($league);
        } catch (Exception $ex) {
            abort(404, 'League not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, string $id)
    {
        $driver = Driver::find($id);

        $this->authorize('update', $driver);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
