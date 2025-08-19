<input type="hidden" name="case_id" value="{{ old('case_id', $child->case_id ?? $case->id ?? '') }}">

{{-- Single Row: First Name, Nationality No, Date of Birth (3 columns) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 mt-4">

    {{-- First Name --}}
    <div>
        <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('First Name') }}</label>
        <input
            type="text"
            name="first_name"
            id="first_name"
            value="{{ old('first_name', $child->first_name ?? '') }}"
            placeholder="{{ __('First Name') }}"
            required
            maxlength="255"
            oninvalid="this.setCustomValidity('{{ __('First Name is required') }}')"
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
        @error('first_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nationality No --}}
    <div>
        <label for="nationality_no" class="block text-sm font-medium text-gray-700">
            {{ __('National No') }} {{ __('optional') }}
        </label>
        <input
        type="text"
        name="nationality_no"
        id="nationality_no"
        value="{{ old('nationality_no', $child->nationality_no ?? '') }}"
        placeholder="{{ __('xxxxxxxxxxxx') }}"
        minlength="12"
        maxlength="12"
        pattern="(^$)|(^[12][0-9]{11}$)"
        title="{{ __('Nationality number must be 12 digits starting with 1 or 2, or leave it empty') }}"
        oninvalid="this.setCustomValidity('{{ __('Please enter a valid 12-digit Nationality Number starting with 1 or 2, or leave it empty') }}')"
        oninput="this.setCustomValidity('')"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
    />

        @error('nationality_no')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Date of Birth --}}
    <div>
        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">{{ __('Date of Birth') }}</label>
        <input
            type="date"
            name="date_of_birth"
            id="date_of_birth"
            value="{{ old('date_of_birth', isset($child->date_of_birth) ? $child->date_of_birth->format('Y-m-d') : '') }}"
            required
            max="{{ date('Y-m-d') }}"
            oninvalid="this.setCustomValidity('{{ __('Date of Birth is required and cannot be in the future') }}')"
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
        @error('date_of_birth')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

{{-- Second Row: Gender + Birth Certificate --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    {{-- Gender --}}
    <div>
        <label for="gender" class="block text-sm font-medium text-gray-700">{{ __('Gender') }}</label>
        <select
            name="gender"
            id="gender"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
            @foreach(\App\GenderEnum::cases() as $gender)
                <option value="{{ $gender->value }}" {{ old('gender', $child->gender ?? '') == $gender->value ? 'selected' : '' }}>
                    {{ __($gender->label()) }}
                </option>
            @endforeach
        </select>
        @error('gender')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Birth Certificate --}}
    <div>
        <label for="birth_certificate" class="block text-sm font-medium text-gray-700">{{ __('Birth Certificate') }}</label>
        <input
            type="file"
            name="birth_certificate"
            id="birth_certificate"
            accept=".pdf,.jpg,.jpeg,.png"
            @if(!isset($child)) required @endif
            oninvalid="this.setCustomValidity('{{ __('Birth Certificate is required') }}')"
            oninput="this.setCustomValidity('')"
            class="block w-full text-sm text-gray-500
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-md file:border-0
                   file:text-sm file:font-semibold
                   file:bg-indigo-50 file:text-indigo-700
                   hover:file:bg-indigo-100"
        />
        @error('birth_certificate')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Empty column for alignment --}}
    <div></div>

</div>
