<?php

namespace App\Http\Controllers;

use App\GenderEnum;
use App\StatusEnum;
use App\Models\Child;
use App\Models\DivorceCase;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'nationality_no' => ['sometimes', 'digits:12', 'regex:/^[12]/', 'unique:children,nationality_no'],
            'date_of_birth' => 'required|date',
            'birth_certificate_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'gender' => [
                'required',
                'integer',
                Rule::in(array_column(GenderEnum::cases(), 'value'))
        ]]);
        $validated['birth_certificate_document_url'] = $request->file('birth_certificate_document')->store('birth_certificate_documents', 'public');

        $divorceCase->children()->create($validated);
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
                'sometimes',
                'digits:12',
                'regex:/^[12]/',
                'unique:children,nationality_no,' . $child->id,
            ],
            'date_of_birth' => 'required|date',
            'birth_certificate_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'gender' => [
                'required',
                'integer',
                Rule::in(array_column(GenderEnum::cases(), 'value'))
        ]]);

        if($request->file('birth_certificate_document')){
            $validated['birth_certificate_document_url'] = $request->file('birth_certificate_document')->store('birth_certificate_documents', 'public');
        }


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
}
