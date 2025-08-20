<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;
    
   public function index(Request $request)
    {
        $this->authorize('viewAny', Activity::class);


        $logs = Activity::where('subject_type', $request->model)
                        ->where('subject_id', $request->id)
                        ->latest()
                        ->paginate(6);


        return view('logs.index', compact('logs'));
    }

}
