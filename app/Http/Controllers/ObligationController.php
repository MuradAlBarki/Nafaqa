<?php

namespace App\Http\Controllers;

use App\Models\DivorceCase;
use App\Models\Obligation;
use App\Notifications\UserAlertNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ObligationController extends Controller
{
    use AuthorizesRequests;

    public function create(DivorceCase $divorceCase)
    {
        $this->authorize('create', [Obligation::class, $divorceCase]);

        return view('obligations.create', compact('divorceCase'));
    }

    public function store(Request $request, DivorceCase $divorceCase)
    {
        $this->authorize('create', [Obligation::class, $divorceCase]);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ]);

        $obligation = $divorceCase->obligation()->create($validated);
        
        foreach($divorceCase->parents() as $parent){
        $parent->user->notify(new UserAlertNotification(
        __('Obligation Created'),
        __('validation.obligation_created_amount', ['amount' => number_format($obligation->amount, 2)]),
        'obligation',
        route('divorce-cases.show', $divorceCase)
        ));
    }

        return redirect()
            ->route('divorce-cases.index', $divorceCase)
            ->with('success', __('created successfully.'));
    }

    public function show(DivorceCase $divorceCase, Obligation $obligation)
    {
        $this->authorize('view', $obligation);
        $payments = $divorceCase->payments()->paginate(10);

        return view('obligations.show', compact('divorceCase', 'obligation', 'payments'));
    }

    public function edit(DivorceCase $divorceCase, Obligation $obligation)
    {
        $this->authorize('update', $obligation);

        return view('obligations.edit', compact('divorceCase', 'obligation'));
    }

    public function update(Request $request, DivorceCase $divorceCase, Obligation $obligation)
    {
        $this->authorize('update', $obligation);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ]);

        $obligation->update($validated);

        foreach($divorceCase->parents() as $parent){
        $parent->user->notify(new UserAlertNotification(
        __('Obligation Updated'),
        __('validation.obligation_updated_amount', ['amount' => number_format($obligation->amount, 2)]),
        'obligation',
        route('divorce-cases.show', $divorceCase)
        ));
    }

        return redirect()
            ->route('divorce-cases.index', $divorceCase)
            ->with('success', __('updated successfully.'));
    }
}
