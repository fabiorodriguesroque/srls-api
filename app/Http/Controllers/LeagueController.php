<?php

namespace App\Http\Controllers;

use App\Http\Actions\League\CreateLeague;
use App\Http\Requests\League\StoreLeagueRequest;
use App\Http\Requests\League\UpdateLeagueRequest;
use App\Http\Resources\LeagueResource;
use App\Models\League;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class LeagueController extends Controller
{
    /**
     * Display a list of leagues.
     */
    public function index()
    {
        return LeagueResource::collection(League::paginate());
    }

    /**
     * Store a newly created league in storage.
     */
    public function store(StoreLeagueRequest $request, CreateLeague $action)
    {
        try {
            $league = $action->handle($request->validated());
            return new LeagueResource($league);
        } catch (Exception $ex) {
            abort(500, 'Could not create league or assign it to a league manager.');
        }
    }

    /**
     * Display the specified league.
     */
    public function show(string $id)
    {
        try {
            $league = League::findOrFail($id);
            return new LeagueResource($league);
        } catch (Exception $ex) {
            abort(404, 'League not found.');
        }
    }

    /**
     * Update the specified league in storage.
     */
    public function update(UpdateLeagueRequest $request, string $id)
    {
        try {
            $league = League::findOrFail($id);
            $league->fill($request->validated())->save();
            return new LeagueResource($league);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'League not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $league = League::findOrFail($id);
            $this->authorize('delete', $league);
            $league->delete();
            return response()->json([
                'message' => 'League successfully deleted.'
            ]);
        } catch (ModelNotFoundException $ex) {
            return abort(404, 'League not found.');
        }  
    }
}
