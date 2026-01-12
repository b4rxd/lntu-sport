<?php

namespace App\Http\Controllers\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\RegularScheduler;
use App\Http\Requests\StoreLocationRequest;

class LocationController extends Controller
{
    public function index(){
        $locations = Location::where('enabled', true)->get();

        return view('location.location-list', compact('locations'));
    }

    public function create(){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::CREATE_LOCATION)) {
            return redirect('/card/info');
        }

        return view('location.create-location');
    }

    public function store(StoreLocationRequest $request){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::CREATE_LOCATION)) {
            abort(403, 'У вас немає доступу для створення локації');
        }

        $validated = $request->validated();

        $location = Location::create([
            
            'title' => $validated['title'],
            'description' => $validated['description']
        ]);

        if(!empty($validated['regular'])){
            foreach ($validated['regular'] as $r) {
                RegularScheduler::create([
                    'location_id' => $location->id,
                    'day_number' => $r['day_number'],
                    'time_from' => $r['time_from'],
                    'time_till' => $r['time_till']
                ]);
            }
        }

        return redirect("locations");
    }

    public function edit($id){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::EDIT_LOCATION)) {
            return redirect('/card/info');
        }

        $location = Location::with(['regularSchedulers'])
            ->findOrFail($id);

        $regular = $location->regularSchedulers->map(function ($r) {
            return [
                'day_number' => $r->day_number,
                'time_from'  => optional($r->time_from)->format('H:i'),
                'time_till'  => optional($r->time_till)->format('H:i'),
            ];
        });

        return view('location.edit-location', compact('location', 'regular'));
    }


    public function update(StoreLocationRequest $request, $id){
        $user = auth()->user();

        if (!$user || !$user->hasPermission(\App\Enums\Permission::EDIT_LOCATION)) {
            abort(403, 'У вас немає доступу для редагування локації');
        }

        $validated = $request->validated();

        $location = Location::findOrFail($id);
        $location->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        $location->regularSchedulers()->delete();
        if (!empty($validated['regular'])) {
            foreach ($validated['regular'] as $r) {
                RegularScheduler::create([
                    'location_id' => $location->id,
                    'day_number' => $r['day_number'],
                    'time_from' => $r['time_from'],
                    'time_till' => $r['time_till'],
                ]);
            }
        }

        return redirect()->route('locations.index')
            ->with('success', 'Success location update');
    }

    public function destroy(Request $request, $id){
        $location = Location::findOrFail($id);
        $location->enabled = false;
        $location->save();
        
        return redirect()->route('locations.index')
                ->with('success', 'Succsess delete');
    }
}