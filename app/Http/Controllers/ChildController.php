<?php

namespace App\Http\Controllers;

use App\GenderEnum;
use App\StatusEnum;
use App\Models\Child;
use App\Models\DivorceCase;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    use AuthorizesRequests;

    public function index(DivorceCase $divorceCase)
    {
        $this->authorize('viewAny', [Child::class, $divorceCase]);

        $children = $divorceCase->children()->paginate(10);

        return view('children.index', compact('divorceCase', 'children'));
    }

    public function create(DivorceCase $divorceCase)
    {
        $this->authorize('create', [Child::class, $divorceCase]);

        return view('children.create', compact('divorceCase'));
    }

    public function store(Request $request, DivorceCase $divorceCase)
    {
        $this->authorize('create', [Child::class, $divorceCase]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'nationality_no' => ['required', 'digits:12', 'regex:/^[12]/', 'unique:children,nationality_no'],
            'date_of_birth' => 'required|date',
        ]);

        $validated['gender'] = $this->extractGender($validated['nationality_no']);
        $validated['case_id'] = $divorceCase->id;

        Child::create($validated);
        $divorceCase->status = StatusEnum::Active->value;
        $divorceCase->save();
        return redirect()
            ->route('divorce-cases.children.index', $divorceCase)
            ->with('success', __('added successfully.'));
    }

    public function show(DivorceCase $divorceCase, Child $child)
    {
        $this->authorize('view', $child);

        return view('children.show', compact('divorceCase', 'child'));
    }

    public function edit(DivorceCase $divorceCase, Child $child)
    {
        $this->authorize('update', $child);

        return view('children.edit', compact('divorceCase', 'child'));
    }

    public function update(Request $request, DivorceCase $divorceCase, Child $child)
    {
        $this->authorize('update', $child);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'nationality_no' => [
                'required',
                'digits:12',
                'regex:/^[12]/',
                'unique:children,nationality_no,' . $child->id,
            ],
            'date_of_birth' => 'required|date',
        ]);

        $validated['gender'] = $this->extractGender($validated['nationality_no']);

        $child->update($validated);

        return redirect()
            ->route('divorce-cases.children.index', $divorceCase)
            ->with('success', __('updated successfully.'));
    }

    public function destroy(DivorceCase $divorceCase, Child $child)
    {
        $this->authorize('delete', $child);

        $child->delete();

        return redirect()
            ->route('divorce-cases.children.index', $divorceCase)
            ->with('success', __('deleted successfully.'));
    }

    private function extractGender(string $nationalityNo): int
{
    $prefix = $nationalityNo[0];

    foreach (GenderEnum::cases() as $gender) {
        if ((string) $gender->value === $prefix) {
            return $gender->value;
        }
    }

    throw new \InvalidArgumentException("Unknown gender prefix: {$prefix}");
}
}
