<div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
            <tr>
                <th class="px-6 py-3">{{ __('Amount') }}</th>
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
                        {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-{{ $statusEnum->realColor() }}-500"></span>
                        {{ __($statusEnum->label()) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-3 items-center">
                            @can('view', $payment)
                                <a href="{{ route('payments.show', $payment->id) }}"
                                   title="{{ __('View Payment') }}"
                                   class="text-gray-600 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="h-6 w-6">
                                        <rect x="2.25" y="6" width="19.5" height="12" rx="2.25" ry="2.25"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="3"
                                                stroke-linecap="round" stroke-linejoin="round"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 9h19.5M2.25 15h19.5"/>
                                    </svg>
                                </a>
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

