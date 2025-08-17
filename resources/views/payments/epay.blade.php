<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Payment Details') }}
        </h2>

        <a href="{{ route('divorce-cases.payments.index', $payment->divorceCase->id) }}"
           class="inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded shadow hover:bg-gray-200 hover:text-gray-900 transition">
            {{ __('Back') }}
        </a>
    </div>
</x-slot>

    <div class="max-w-3xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">{{ __('Choose Payment Method') }}</h3>

        <input type="hidden" id="payment_method" name="payment_method" value="">


        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {{-- Moamalat Pay --}}
            <button type="button" id="moamalat-btn" class="payment-btn flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <img src="{{ asset('images/payments/moamalat.jpg') }}" alt="Moamalat Pay" class="h-16 w-16 object-contain">
                <span class="mt-2 text-sm">Moamalat</span>
            </button>

            {{-- Visa --}}
            <button type="button" class="payment-btn flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <img src="{{ asset('images/payments/visa.png') }}" alt="Visa" class="h-16 w-16 object-contain">
                <span class="mt-2 text-sm">Visa</span>
            </button>

            {{-- Mastercard --}}
            <button type="button" class="payment-btn flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <img src="{{ asset('images/payments/mastercard.png') }}" alt="Mastercard" class="h-16 w-16 object-contain">
                <span class="mt-2 text-sm">Mastercard</span>
            </button>

            {{-- Sadad --}}
            <button type="button" class="payment-btn flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <img src="{{ asset('images/payments/sadad.png') }}" alt="Sadad" class="h-16 w-16 object-contain">
                <span class="mt-2 text-sm">Sadad</span>
            </button>

            {{-- Apple Pay --}}
            <button type="button" class="payment-btn flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <img src="{{ asset('images/payments/apple.png') }}" alt="Apple Pay" class="h-16 w-16 object-contain">
                <span class="mt-2 text-sm">Apple Pay</span>
            </button>

            {{-- Google Pay --}}
            <button type="button" class="payment-btn flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <img src="{{ asset('images/payments/google.png') }}" alt="Google Pay" class="h-16 w-16 object-contain">
                <span class="mt-2 text-sm">Google Pay</span>
            </button>
        </div>
    </div>

    <script>
    // Make Laravel CSRF token available in JS
    const csrf = "{{ csrf_token() }}";
</script>
    <script src="https://tnpg.moamalat.net:6006/js/lightbox.js"></script>

    <script>
        // Payment selection highlight & set hidden input
        const buttons = document.querySelectorAll('.payment-btn');
        const paymentInput = document.getElementById('payment_method');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('border-indigo-500'));
                btn.classList.add('border-indigo-500');

                const method = btn.querySelector('span').textContent.trim();
                paymentInput.value = method;

                if(method === 'Moamalat') {
                    startMoamalatPayment();
                }
            });
        });

        // Moamalat payment function
        function startMoamalatPayment() {
            const mID = "10081014649"; 
            const tID = "99179395";  
            const amount = {{ $payment->amount * 1000 ?? 0 }};
            const merchantReference = "MREF-" + getCurrentDateTime(); 
            const trxDateTime = getCurrentDateTime(); 
            const secureKey = "3a488a89b3f7993476c252f017c488bb"; 

            generateSecureHash(`Amount=${amount}&DateTimeLocalTrxn=${trxDateTime}&MerchantId=${mID}&MerchantReference=${merchantReference}&TerminalId=${tID}`, secureKey)
            .then((hash) => {
                Lightbox.Checkout.configure = {
                    MID: mID,
                    TID: tID,
                    AmountTrxn: amount,
                    MerchantReference: merchantReference,
                    TrxDateTime: trxDateTime,
                    SecureHash: hash,

                    completeCallback: async function (data) {
                        try {
                            await fetch("{{ route('payments.success', $payment) }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: JSON.stringify({
                                    gateway: 'moamalat',
                                    response: data 
                                })
                            });
                        } catch (err) {
                            console.error('Network error:', err);
                        }
                    },


                    errorCallback: async function (error) {
                        try {
                            fetch("{{ route('payments.fail', $payment) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                gateway: 'moamalat',
                                response: error
                            })
                        });
                        } catch (err) {
                                console.error('Network error:', err);
                        }
                        },
                    cancelCallback: function () {
                      
                    }
                };

                Lightbox.Checkout.showLightbox();
            });
        }

        function getCurrentDateTime() {
            const now = new Date();
            const pad = n => (n < 10 ? "0" + n : n);
            return `${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}${pad(now.getHours())}${pad(now.getMinutes())}`;
        }

        async function generateSecureHash(params, hexSecretKey) {
            const cryptoKey = await crypto.subtle.importKey(
                "raw",
                hexToBytes(hexSecretKey),
                { name: "HMAC", hash: "SHA-256" },
                false,
                ["sign"]
            );
            const signature = await crypto.subtle.sign(
                "HMAC",
                cryptoKey,
                new TextEncoder().encode(params)
            );
            return bufferToHex(signature).toUpperCase();
        }

        function hexToBytes(hex) {
            const bytes = new Uint8Array(hex.length / 2);
            for (let i = 0; i < bytes.length; i++) {
                bytes[i] = parseInt(hex.substr(i*2, 2), 16);
            }
            return bytes;
        }

        function bufferToHex(buffer) {
            return [...new Uint8Array(buffer)]
                .map(b => b.toString(16).padStart(2,"0"))
                .join("");
        }
    </script>
</x-app-layout>
