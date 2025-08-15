<x-app-layout>
    <x-slot name="header">
        <h2><strong>{{ __('Edit') }} {{ __('Payment') }}</strong></h2>
    </x-slot>

    <div class="p-8 max-w-6xl mx-auto bg-white rounded shadow mt-10">

        <form action="{{ route('divorce-cases.payments.update', [$divorceCase, $payment]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('payments.form')

            @can('update', $payment)
            {{-- Status select --}}
            <div class="mt-6">
                <label for="status" class="block text-sm font-medium text-gray-700">
                    {{ __('Status') }}
                </label>
                <select id="status" name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                   @foreach($status as $case)
    <option value="{{ $case->value }}" {{ old('status', $payment->status) == $case->value ? 'selected' : '' }}>
        {{ __($case->label()) }}

    </option>
@endforeach
                </select>
            </div>
            @endcan


            <div class="mt-8 flex justify-center space-x-8">
                <button
                    type="submit"
                    class="
                      w-48
                      px-6 py-3
                      bg-blue-100
                      text-blue-700
                      font-semibold
                      rounded-md
                      hover:bg-blue-200
                      focus:outline-none
                      focus:ring-2
                      focus:ring-blue-300
                      focus:ring-offset-2
                      transition
                      duration-150
                      ease-in-out
                    "
                >
                    {{ __('Save') }}
                </button>
                &nbsp;&nbsp;&nbsp;
                <a href="{{ url()->previous() }}"
                    class="
                      w-48
                      px-6 py-3
                      bg-gray-100
                      text-gray-700
                      font-semibold
                      rounded-md
                      hover:bg-gray-200
                      focus:outline-none
                      focus:ring-2
                      focus:ring-gray-300
                      focus:ring-offset-2
                      transition
                      duration-150
                      ease-in-out
                      text-center
                      inline-block
                    "
                >
                    {{ __('Back') }}
                </a>
            </div>
        </form>

    </div>
</x-app-layout>
