<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ __('Payment Details') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8 mt-10">

        {{-- Father upload form --}}
        @if($payment->status === \App\PaymentStatusEnum::Entry && $divorceCase->isFather(auth()->user()))
            <form id="payment-upload-form" method="POST" action="{{ route('payments.pay', [$divorceCase, $payment]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">

                    <!-- Amount -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Amount') }}</label>
                        <input type="number"
                               value="{{ $payment->amount ?? '' }}"
                               disabled
                               class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 shadow-sm cursor-not-allowed"/>
                    </div>

                    <!-- Payment Month -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Payment Month') }}</label>
                        <input type="text"
                               value="{{ $payment->due_date?->format('Y-m') }}"
                               disabled
                               class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 shadow-sm cursor-not-allowed"/>
                    </div>

                    <!-- Proof Document -->
                    @if($payment->proof_document_url)
                        <div class="flex flex-col md:col-span-2">
                            <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Proof Document') }}</label>
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $payment->proof_document_url) }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ __('View uploaded document') }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col md:col-span-2">
                        <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Proof Document') }}</label>
                        <input type="file"
                               name="proof_document"
                               accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf"
                               required
                               oninvalid="this.setCustomValidity('{{ __('Document File is required') }}')"
                               oninput="this.setCustomValidity('')"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100 mt-1"/>
                    </div>

                </div>

                {{-- Upload + Back buttons --}}
                <div class="mt-8 flex justify-center space-x-4 gap-4">
                    <button type="submit"
                            class="w-48 px-6 py-3 bg-blue-100 text-blue-700 font-semibold rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                        {{ __('Upload Document') }}
                    </button>

                    <a href="{{ url()->previous() }}"
                       class="w-48 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition text-center inline-block">
                        {{ __('Back') }}
                    </a>
                </div>
            </form>

        @else
            {{-- Mother/Admin view --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">

                <!-- Amount -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Amount') }}</label>
                    <input type="number"
                           value="{{ $payment->amount ?? '' }}"
                           disabled
                           class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 shadow-sm cursor-not-allowed"/>
                </div>

                <!-- Payment Month -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Payment Month') }}</label>
                    <input type="text"
                           value="{{ $payment->due_date?->format('Y-m') }}"
                           disabled
                           class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 shadow-sm cursor-not-allowed"/>
                </div>

                <!-- Proof Document -->
                @if($payment->proof_document_url)
                    <div class="flex flex-col md:col-span-2">
                        <label class="text-sm font-medium mb-1 bg-white px-1">{{ __('Proof Document') }}</label>
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $payment->proof_document_url) }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ __('View uploaded document') }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($payment->epaid)
                    @php $epay = $payment->epayment(); @endphp
                    <div class="flex flex-col md:col-span-2 p-2 bg-green-50 border-l-4 border-green-400 rounded">
                        <span class="text-sm font-semibold text-green-700">
                            {{ __('Paid Electronically') }}
                        </span>
                        <span class="text-sm text-gray-700">
                            {{ __('Gateway') }} : <strong>{{ __($epay->gateway) }}</strong>
                        </span>
                        <span class="text-sm text-gray-700">
                            {{ __('Reference No') }}: <strong>{{ $epay->response_json['SystemReference'] ?? 'N/A' }}</strong>
                        </span>
                    </div>
                @endif

            </div>

            {{-- Approve/Reject + Back buttons --}}
            <div class="mt-4 flex justify-center gap-3">

                @if ($payment->status === \App\PaymentStatusEnum::PaidNotVerified && $divorceCase->isMother(auth()->user()))

                    <form action="{{ route('payments.review', $payment) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status"
                               value="{{ \App\PaymentStatusEnum::ConfirmedByMother->value}}">
                        <button type="submit"
                                class="px-5 py-2 bg-green-300 text-green-800 rounded-md hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                            {{ __('Approve') }}
                        </button>
                    </form>

                    <form action="{{ route('payments.review', $payment) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status"
                               value="{{\App\PaymentStatusEnum::RejectedByMother->value}}">
                        <button type="submit"
                                class="px-5 py-2 bg-red-300 text-red-800 rounded-md hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
                            {{ __('Reject') }}
                        </button>
                    </form>
                @endif

                @if ($payment->status === \App\PaymentStatusEnum::RejectedByMother && auth()->user()->can('changeStatus', $payment))

                    <form action="{{ route('payments.review', $payment) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status"
                               value="{{\App\PaymentStatusEnum::ConfirmedBySystem->value }}">
                        <button type="submit"
                                class="px-5 py-2 bg-green-300 text-green-800 rounded-md hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                            {{ __('Approve') }}
                        </button>
                    </form>

                    <form action="{{ route('payments.review', $payment) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status"
                               value="{{\App\PaymentStatusEnum::RejectedBySystem->value }}">
                        <button type="submit"
                                class="px-5 py-2 bg-red-300 text-red-800 rounded-md hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
                            {{ __('Reject') }}
                        </button>
                    </form>
                @endif

                {{-- Back button always visible --}}
                <a href="{{ url()->previous() }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 font-semibold rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                    {{ __('Back') }}
                </a>
            </div>

        @endif

    </div>
</x-app-layout>
