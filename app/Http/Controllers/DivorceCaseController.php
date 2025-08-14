<?php

namespace App\Http\Controllers;

use App\GenderEnum;
use App\Models\DivorceCase;
use App\Models\ProfileRole;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DivorceCaseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', DivorceCase::class);

        $divorceCases = DivorceCase::paginate(10);

        return view('divorce-cases.index', compact('divorceCases'));
    }

        public function userIndex()
    {
        $divorceCases = DivorceCase::where('father_id', auth()->user()->profileRole->id)
                   ->orWhere('mother_id', auth()->user()->profileRole->id)->paginate(10);

        return view('divorce-cases.user-index', compact('divorceCases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', DivorceCase::class);

        $mothers = ProfileRole::where('gender', GenderEnum::Female)->get();
        $fathers = ProfileRole::where('gender', GenderEnum::Male)->get();

        return view('divorce-cases.create', compact('mothers', 'fathers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', DivorceCase::class);

        $validated = $request->validate([
            'mother_id' => 'required|exists:profile_roles,id',
            'father_id' => 'required|exists:profile_roles,id',
            'case_no' => 'required|string|max:50|unique:divorce_cases,case_no',
            'divorce_date' => 'required|date',
            'court_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('court_document')) {
            $validated['court_document_url'] = $request->file('court_document')->store('court_documents', 'public');
        }

        DivorceCase::create($validated);

        return redirect()->route('divorce-cases.index')->with('success', __('Created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DivorceCase $divorceCase)
    {
        $this->authorize('view', $divorceCase);

        return view('divorce-cases.show', compact('divorceCase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DivorceCase $divorceCase)
    {
        $this->authorize('update', $divorceCase);

        $mothers = ProfileRole::where('gender', GenderEnum::Female)->get();
        $fathers = ProfileRole::where('gender', GenderEnum::Male)->get();

        return view('divorce-cases.edit', compact('divorceCase', 'mothers', 'fathers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DivorceCase $divorceCase)
    {
        $this->authorize('update', $divorceCase);

        $validated = $request->validate([
            'mother_id' => 'required|exists:profile_roles,id',
            'father_id' => 'required|exists:profile_roles,id',
            'case_no' => 'required|string|max:50|unique:divorce_cases,case_no,' . $divorceCase->id,
            'divorce_date' => 'required|date',
            'court_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

          if($request->file('court_document')){
            $validated['court_document_url'] = $request->file('court_document')->store('documents', 'public');
        }


        $divorceCase->update($validated);

        return redirect()->route('divorce-cases.index')->with('success', __('Updated successfully.'));
    }

    public function destroy(DivorceCase $divorceCase)
    {
        $this->authorize('delete', $divorceCase);

        $divorceCase->delete();

        return redirect()->route('divorce-cases.index')->with('success', __('Deleted successfully.'));
    }
}
