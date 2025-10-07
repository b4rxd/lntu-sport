<?php

namespace App\Http\Controllers\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\RegularScheduler;
use App\Models\VacationScheduler;
use App\Models\SpecialScheduler;
use App\Http\Requests\StoreLocationRequest;

class LocationController extends Controller
{
    public function index(){
        $locations = Location::all();
        return view('location.location-list', compact('locations'));
    }

    public function create(){
        return view('location.create-location');
    }

    public function store(StoreLocationRequest $request){
        $validated = $request->validated();

        $location = Location::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'enabled' => true
        ]);

        if(!empty($validated['regular'])){
            foreach ($validated['regular'] as $r) {
                RegularScheduler::create([
                    'location_id' => $location->id,
                    'day_number' => $r['day_number'],
                    'time_from' => $r['time_from'],
                    'time_till' => $r['time_till'],
                    'date_from' => $r['date_from'],
                    'date_till' => $r['date_till'] ?? null,
                    'enabled' => true
                ]);
            }
        }

        if (!empty($validated['vacation'])) {
            foreach ($validated['vacation'] as $v) {
                VacationScheduler::create([
                    'location_id' => $location->id,
                    'day_number' => $v['day_number'],
                    'title' => $v['title'],
                    'date_from' => $v['date_from'],
                    'date_till' => $v['date_till'] ?? null,
                ]);
            }
        }

        if (!empty($validated['special'])) {
            foreach ($validated['special'] as $s) {
                SpecialScheduler::create([
                    'location_id' => $location->id,
                    'time_from' => $s['time_from'],
                    'time_till' => $s['time_till'],
                    'date_from' => $s['date_from'],
                    'date_till' => $s['date_till'] ?? null,
                ]);
            }
        }

        return redirect("locations");
    }

    public function edit($id)
{
    $location = Location::with(['regularSchedulers', 'vacationSchedulers', 'specialSchedulers'])
        ->findOrFail($id);

    $regular = $location->regularSchedulers->map(function ($r) {
        return [
            'day_number' => $r->day_number,
            'time_from'  => optional($r->time_from)->format('H:i'),
            'time_till'  => optional($r->time_till)->format('H:i'),
            'date_from'  => optional($r->date_from)->format('Y-m-d'),
            'date_till'  => optional($r->date_till)->format('Y-m-d'),
        ];
    });

    $vacation = $location->vacationSchedulers->map(function ($v) {
        return [
            'day_number' => $v->day_number,
            'title'      => $v->title,
            'date_from'  => optional($v->date_from)->format('Y-m-d'),
            'date_till'  => optional($v->date_till)->format('Y-m-d'),
        ];
    });

    $special = $location->specialSchedulers->map(function ($s) {
        return [
            'time_from'  => optional($s->time_from)->format('H:i'),
            'time_till'  => optional($s->time_till)->format('H:i'),
            'date_from'  => optional($s->date_from)->format('Y-m-d'),
            'date_till'  => optional($s->date_till)->format('Y-m-d'),
        ];
    });

    return view('location.edit-location', compact('location', 'regular', 'vacation', 'special'));
}


    public function update(StoreLocationRequest $request, $id)
    {
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
                    'date_from' => $r['date_from'],
                    'date_till' => $r['date_till'] ?? null,
                    'enabled' => true
                ]);
            }
        }

        $location->vacationSchedulers()->delete();
        if (!empty($validated['vacation'])) {
            foreach ($validated['vacation'] as $v) {
                VacationScheduler::create([
                    'location_id' => $location->id,
                    'day_number' => $v['day_number'],
                    'title' => $v['title'],
                    'date_from' => $v['date_from'],
                    'date_till' => $v['date_till'] ?? null,
                ]);
            }
        }

        $location->specialSchedulers()->delete();
        if (!empty($validated['special'])) {
            foreach ($validated['special'] as $s) {
                SpecialScheduler::create([
                    'location_id' => $location->id,
                    'time_from' => $s['time_from'],
                    'time_till' => $s['time_till'],
                    'date_from' => $s['date_from'],
                    'date_till' => $s['date_till'] ?? null,
                ]);
            }
        }

        return redirect()->route('locations.index')
            ->with('success', 'Success location update');
    }

    public function toggle(Request $request, $id){
        $location = Location::findOrFail($id);
    
        $location->update([
            'enabled' => !$location->enabled,
        ]);

        return redirect()->route('locations.index')
                ->with('success', 'Succsess toggle');
    }

    public function destroy(Request $request, $id){
        $location = Location::findOrFail($id);
        $location->delete();
        
        return redirect()->route('locations.index')
                ->with('success', 'Succsess delete');
    }
}