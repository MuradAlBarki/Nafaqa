<input type="hidden" name="user" value="{{ request('user') }}">

{{-- Row 1: Parents (2 columns) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 mt-4">

<div>
        <label for="father_id" class="block text-sm font-medium text-gray-700">{{ __('Father') }}</label>
        <select
            name="father_id"
            id="father_id"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            oninvalid="this.setCustomValidity('{{ __('Father is required') }}')"
            oninput="this.setCustomValidity('')"
        >
            <option value="">{{ __('Select') }} {{ __('Father') }}</option>
            @foreach($fathers as $father)
                <option value="{{ $father->id }}" {{ old('father_id', $divorceCase->father_id ?? '') == $father->id ? 'selected' : '' }}>
                   {{ $father->first_name . ' ' . $father->mid_name . ' ' . $father->last_name }} - {{$father->national_no}}
                </option>
            @endforeach
        </select>
    </div>


    <div>
        <label for="mother_id" class="block text-sm font-medium text-gray-700">{{ __('Mother') }}</label>
        <select
            name="mother_id"
            id="mother_id"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            oninvalid="this.setCustomValidity('{{ __('Mother is required') }}')"
            oninput="this.setCustomValidity('')"
        >
            <option value="">{{ __('Select') }} {{ __('Mother') }}</option>
            @foreach($mothers as $mother)
                <option value="{{ $mother->id }}" {{ old('mother_id', $divorceCase->mother_id ?? '') == $mother->id ? 'selected' : '' }}>
                    {{ $mother->first_name . ' ' . $mother->mid_name . ' ' . $mother->last_name }} - {{$mother->national_no}}
                </option>
            @endforeach
        </select>
    </div>
    
</div>
{{-- Row 2: Case Number and Divorce Date (2 columns) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="case_no" class="block text-sm font-medium text-gray-700">{{ __('Case Number') }}</label>
        <input
            type="text"
            name="case_no"
            id="case_no"
            value="{{ old('case_no', $divorceCase->case_no ?? '') }}"
            placeholder="{{ __('Case Number') }}"
            required
            maxlength="50"
            oninvalid="this.setCustomValidity('{{ __('Case Number is required and max 50 chars') }}')"
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
    </div>
    <div>
        <label for="divorce_date" class="block text-sm font-medium text-gray-700">{{ __('Divorce Date') }}</label>
        <input
            type="date"
            name="divorce_date"
            id="divorce_date"
            value="{{ old('divorce_date', isset($divorceCase->divorce_date) ? $divorceCase->divorce_date->format('Y-m-d') : '') }}"
            required
            max="{{ date('Y-m-d') }}"
            oninvalid="this.setCustomValidity('{{ __('Divorce Date is required and cannot be in the future') }}')"
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
    </div>
</div>

{{-- Row 3: Court Document (file upload) --}}
<div class="mb-6">
    <label for="court_document" class="block text-sm font-medium text-gray-700">{{ __('Court Document') }}</label>
    <input
        type="file"
        name="court_document"
        id="court_document"
        accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf"
        @if (!isset($divorceCase)) required @endif
        oninvalid="this.setCustomValidity('{{ __('Court Document is required') }}')"
        oninput="this.setCustomValidity('')"
        class="block w-full text-sm text-gray-500
               file:mr-4 file:py-2 file:px-4
               file:rounded-md file:border-0
               file:text-sm file:font-semibold
               file:bg-indigo-50 file:text-indigo-700
               hover:file:bg-indigo-100"
    />
</div>