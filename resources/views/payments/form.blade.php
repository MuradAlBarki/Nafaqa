<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
        <input
            type="number"
            name="amount"
            id="amount"
            value="{{ old('amount', $payment->amount ?? $divorceCase->obligation->amount) }}"
            placeholder="{{ __('Enter amount') }}"
            required
            min="0"
            step="0.01"
            oninvalid="this.setCustomValidity('{{ __('Amount is required and must be a positive number') }}')"
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
    </div>

    <div>
        <label for="payment_for_date" class="block text-sm font-medium text-gray-700" >{{ __('Start Date') }}</label>
        <input
            type="date"
            name="payment_for_date"
            id="payment_for_date"
            value="{{ old('payment_for_date', isset($payment->payment_for_date) ? $payment->payment_for_date->format('Y-m-d') : today()->format('Y-m-d')) }}"
            required
            max="{{ date('Y-m-d') }}"
            oninvalid="this.setCustomValidity('{{ __('Start Date is required and cannot be in the future') }}')"
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
    </div>
</div>

