<div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
            <tr>
                <th class="px-6 py-3">{{ __('Amount') }}</th>
                <th class="px-6 py-3">{{ __('Payment Month') }}</th>
                <th class="px-6 py-3">{{ __('Payment Date') }}</th>
                <th class="px-6 py-3">{{ __('Status') }}</th>
                <th class="px-6 py-3">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($payments as $payment)
                @php $statusEnum = $payment->status; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $payment->payment_for_date->format('Y-m')  }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : __('not yet') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-{{ $statusEnum->realColor() }}-500"></span>
                        {{ __($statusEnum->label()) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-3 items-center">
                <div class="flex items-center space-x-2 gap-2">         

            <a href="{{ route('divorce-cases.payments.show', [$divorceCase->id, $payment->id]) }}"
               title="{{ __('Show Payment') }}"
               class="text-gray-600 hover:text-blue-600 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
</svg>
            </a>
    
        {{-- Edit --}}
        @can('update', $payment)
            <a href="{{ route('divorce-cases.payments.edit', [$divorceCase->id, $payment->id]) }}"
               title="{{ __('Edit Payment') }}"
               class="text-gray-600 hover:text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687 1.687m-2.9-2.9l-8.31 8.31a2.121 2.121 0 00-.53.95l-.47 2.353a.53.53 0 00.63.63l2.353-.47c.356-.07.684-.233.95-.53l8.31-8.31m-2.9-2.9l2.9 2.9"/>
                </svg>
            </a>
        @endcan

        @if($payment->status === \App\PaymentStatusEnum::Entry && $divorceCase->isFather(auth()->user()))
        <a href="{{ route('payments.epay', $payment) }}"
               title="{{ __('Epay Payment') }}"
               class="text-gray-600 hover:text-green-600">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                <rect x="2" y="5" width="20" height="14" rx="2" ry="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="6" cy="15" r="1" fill="currentColor"/>
                <circle cx="10" cy="15" r="1" fill="currentColor"/>
                <circle cx="14" cy="15" r="1" fill="currentColor"/>
</svg>

            </a>
        @endif

        <!-- Epayments Button (show only if payment has epayments) -->
         @can('viewAny', [\App\Models\Epayment::class, $payment])
@if($payment->epayments()->exists())
    <a href="{{ route('epayments.index', $payment) }}"
       title="{{ __('View Epayments') }}"
       class="text-gray-600 hover:text-purple-600">
        <!-- SVG for Epayments -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </a>
@endif
@endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>

