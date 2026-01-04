<?php

namespace App\Http\Controllers\Visit;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\VisitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function index()
    {
        $visitLogs = VisitLog::with(['subscription.client', 'location', 'createdBy'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('visit.visits', compact('visitLogs'));
    }

    public function store(Request $request){
        $this->authorize('create-visit');

        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'location_id' => 'required|exists:locations,id',
        ]);

        $subscription = Subscription::find($request->subscription_id);

        if ($subscription->end_date && $subscription->end_date->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Підписка прострочена'
            ], 400);
        }

        if ($subscription->count_usage !== null && $subscription->count_usage <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Вичерпано кількість доступних відвідувань'
            ], 400);
        }

        $visit = VisitLog::create([
            'subscription_id' => $subscription->id,
            'location_id' => $request->location_id,
            'type' => 'visit',
            'created_by_id' => Auth::id(),
        ]);

        if ($subscription->count_usage !== null) {
            $subscription->decrement('count_usage');
        }

        return response()->json([
            'success' => true,
            'visit' => $visit
        ]);
    }

}
