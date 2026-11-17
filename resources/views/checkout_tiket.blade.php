@extends('layouts.main')

@section('title', 'Checkout Tiket - ' . $event->title)

@section('content')
<div class="booking-page-wrapper">
    <div class="booking-modal-card">

        {{-- Step Progress Indicator --}}
        <div class="booking-stepper" id="booking-stepper">
            <div class="stepper-item active" data-step-indicator="1">
                <span class="stepper-num">1</span>
                <span>Select Tickets</span>
            </div>
            <div class="stepper-sep"></div>
            <div class="stepper-item" data-step-indicator="2">
                <span class="stepper-num">2</span>
                <span>Attendee Details</span>
            </div>
            <div class="stepper-sep"></div>
            <div class="stepper-item" data-step-indicator="3">
                <span class="stepper-num">3</span>
                <span>Order Summary</span>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            {{-- ============================================================
                 STEP 1: SELECT TICKETS (SESUAI FIGMA FRAME 1)
                 ============================================================ --}}
            <div class="booking-step active" id="booking-step-1">
                <div class="booking-header">
                    <div class="booking-header-left">
                        <h1>Select Tickets</h1>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn-step-nav" title="Kembali ke Detail Event">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                <div class="booking-table-head">
                    <span>Ticket Types</span>
                    <span>Quantity</span>
                </div>

                <div class="booking-ticket-list">
                    @forelse ($e_tickets as $ticket)
                    @php
                    $available = max(
                    0,
                    $ticket->quota
                    - ($ticket->reserved_quota ?? 0)
                    - ($ticket->sold_quota ?? 0)
                    );

                    $maxPerOrder = (!empty($ticket->max_per_order) && $ticket->max_per_order > 0)
                    ? min($available, $ticket->max_per_order)
                    : $available;
                    @endphp

                    <div class="figma-ticket-row {{ $available <= 0 ? 'sold-out' : '' }}">
                        <div class="figma-ticket-info">
                            <h3>{{ $ticket->name }}</h3>
                            <div class="figma-ticket-price">
                                @if ($ticket->price > 0)
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                @else
                                Gratis
                                @endif
                            </div>
                            <span class="figma-ticket-quota">
                                @if ($available > 0)
                                Tersedia {{ $available }} tiket
                                @else
                                Tiket Habis
                                @endif
                            </span>
                        </div>

                        <div class="figma-qty-control">
                            <button
                                type="button"
                                class="figma-btn-circle btn-minus"
                                data-id="{{ $ticket->id }}"
                                @if ($available <=0) disabled @endif>
                                &minus;
                            </button>

                            <input
                                type="number"
                                id="quantity_{{ $ticket->id }}"
                                name="quantities[{{ $ticket->id }}]"
                                class="figma-qty-input ticket-quantity"
                                data-price="{{ $ticket->price }}"
                                data-name="{{ $ticket->name }}"
                                min="0"
                                max="{{ $maxPerOrder }}"
                                value="0"
                                readonly
                                @if ($available <=0) disabled @endif>

                            <button
                                type="button"
                                class="figma-btn-circle btn-plus"
                                data-id="{{ $ticket->id }}"
                                @if ($available <=0) disabled @endif>
                                +
                            </button>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 36px 28px; text-align: center; color: #5a5a5a;">
                        Belum ada tipe tiket yang tersedia untuk event ini.
                    </div>
                    @endforelse
                </div>

                <div class="booking-footer-bar">
                    <div class="booking-qty-total-row">
                        <div>Qty: <span class="booking-green-val js-total-qty">0</span></div>
                        <div>Total: <span class="booking-green-val js-total-price">Rp 0</span></div>
                    </div>

                    <button type="button" id="btn-to-step-2" class="btn-booking-navy" disabled>
                        <span>Proceed</span>
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ============================================================
                 STEP 2: ATTENDEE DETAILS (SESUAI FIGMA FRAME 2)
                 ============================================================ --}}
            <div class="booking-step" id="booking-step-2">
                <div class="booking-header">
                    <div class="booking-header-left">
                        <button type="button" class="btn-step-nav" id="btn-back-to-1" title="Kembali pilih tiket">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h1>Attendee Details</h1>
                    </div>
                </div>

                <div class="booking-event-meta-bar">
                    <span>{{ $event->title }}</span>
                    @if (!empty($event->start_time))
                    <span class="booking-event-meta-date">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('l, d F Y') }}
                    </span>
                    @endif
                </div>

                <div class="attendee-section-wrap">
                    <div class="attendee-ticket-caption" id="attendee-ticket-label">
                        Informasi Pemesan &amp; Penerima E-Tiket
                    </div>

                    <div class="attendee-form-card">
                        <div class="figma-form-group">
                            <label for="buyer_name">Full Name</label>
                            <input
                                type="text"
                                id="buyer_name"
                                name="buyer_name"
                                class="figma-input"
                                value="{{ old('buyer_name', auth()->user()->name ?? '') }}"
                                placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="figma-form-group">
                            <label for="buyer_email">E-mail Address</label>
                            <input
                                type="email"
                                id="buyer_email"
                                name="buyer_email"
                                class="figma-input"
                                value="{{ old('buyer_email', auth()->user()->email ?? '') }}"
                                placeholder="Enter your e-mail"
                                required>
                        </div>

                        <div class="figma-form-group">
                            <label for="buyer_phone">Phone Number (WhatsApp / HP)</label>
                            <div class="figma-phone-wrap">
                                <span class="figma-phone-prefix">
                                    <span class="flag-id" aria-hidden="true"></span>
                                    <span>+62</span>
                                </span>
                                <input
                                    type="tel"
                                    id="buyer_phone"
                                    name="buyer_phone"
                                    value="{{ old('buyer_phone') }}"
                                    placeholder="081234567890"
                                    required>
                            </div>
                        </div>
                    </div>

                    <p class="booking-terms-note">
                        I accept the <span>Terms &amp; Conditions</span> and have read the <span>Privacy Policy</span>
                    </p>
                </div>

                <div class="booking-footer-bar">
                    <div class="booking-qty-total-row">
                        <div>Qty: <span class="booking-green-val js-total-qty">0</span></div>
                        <div>Total: <span class="booking-green-val js-total-price">Rp 0</span></div>
                    </div>

                    <button type="button" id="btn-to-step-3" class="btn-booking-navy">
                        <span>Continue to Checkout</span>
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ============================================================
                 STEP 3: ORDER SUMMARY & MIDTRANS PAY NOW (SESUAI FIGMA FRAME 3)
                 ============================================================ --}}
            <div class="booking-step" id="booking-step-3">
                <div class="booking-header">
                    <div class="booking-header-left">
                        <button type="button" class="btn-step-nav" id="btn-back-to-2" title="Ubah data pemesan">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h1>Order Summary</h1>
                    </div>
                </div>

                {{-- Kartu E-Ticket Stub Preview ala Figma --}}
                <div class="summary-ticket-stage" id="summary-ticket-cards">
                    {{-- Diisi secara dinamis oleh JavaScript sesuai tiket & data pemesan --}}
                </div>

                <div class="booking-footer-bar">
                    <div class="summary-breakdown-box">
                        <div class="breakdown-line">
                            <span>Sub Total:</span>
                            <span class="js-total-price">Rp 0</span>
                        </div>
                        <div class="breakdown-line">
                            <span>Platform Fee:</span>
                            <span>Rp 0</span>
                        </div>
                        <hr class="breakdown-divider">
                        <div class="breakdown-grand-total">
                            <span>Order Total:</span>
                            <span class="js-total-price">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" id="checkout-submit" class="btn-booking-pay">
                        <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <span id="checkout-submit-label">Pay Now</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.ticket-quantity');
        const totalQtyDisplays = document.querySelectorAll('.js-total-qty');
        const totalPriceDisplays = document.querySelectorAll('.js-total-price');

        const step1 = document.getElementById('booking-step-1');
        const step2 = document.getElementById('booking-step-2');
        const step3 = document.getElementById('booking-step-3');
        const stepperItems = document.querySelectorAll('[data-step-indicator]');

        const btnToStep2 = document.getElementById('btn-to-step-2');
        const btnBackTo1 = document.getElementById('btn-back-to-1');
        const btnToStep3 = document.getElementById('btn-to-step-3');
        const btnBackTo2 = document.getElementById('btn-back-to-2');

        const buyerNameInput = document.getElementById('buyer_name');
        const buyerEmailInput = document.getElementById('buyer_email');
        const buyerPhoneInput = document.getElementById('buyer_phone');
        const attendeeTicketLabel = document.getElementById('attendee-ticket-label');
        const summaryTicketCards = document.getElementById('summary-ticket-cards');

        const checkoutForm = document.getElementById('checkout-form');
        const submitButton = document.getElementById('checkout-submit');
        const submitLabel = document.getElementById('checkout-submit-label');

        const rupiah = amount => 'Rp ' + amount.toLocaleString('id-ID');

        function goToStep(stepNumber) {
            [step1, step2, step3].forEach((el, idx) => {
                el.classList.toggle('active', idx + 1 === stepNumber);
            });

            stepperItems.forEach(item => {
                const s = Number(item.dataset.stepIndicator);
                item.classList.remove('active', 'completed');
                if (s === stepNumber) item.classList.add('active');
                if (s < stepNumber) item.classList.add('completed');
            });

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function getSelectedItems() {
            const selected = [];
            quantityInputs.forEach(input => {
                const qty = Number(input.value) || 0;
                if (qty > 0) {
                    const price = Number(input.dataset.price) || 0;
                    selected.push({
                        name: input.dataset.name,
                        qty: qty,
                        price: price,
                        subtotal: qty * price
                    });
                }
            });
            return selected;
        }

        function updateCheckoutState() {
            const items = getSelectedItems();
            const totalQty = items.reduce((sum, item) => sum + item.qty, 0);
            const totalPrice = items.reduce((sum, item) => sum + item.subtotal, 0);

            totalQtyDisplays.forEach(el => el.textContent = totalQty);
            totalPriceDisplays.forEach(el => el.textContent = rupiah(totalPrice));
            btnToStep2.disabled = totalQty === 0;

            if (items.length > 0) {
                const summaryText = items.map(i => `${i.name} (${i.qty}×)`).join(', ');
                attendeeTicketLabel.textContent = `${summaryText} — Data Pemesan`;
            }
        }

        function renderOrderSummaryStubs() {
            const items = getSelectedItems();
            const buyerName = buyerNameInput.value.trim() || 'Pemesan';
            const buyerEmail = buyerEmailInput.value.trim() || '-';

            summaryTicketCards.innerHTML = '';

            items.forEach(item => {
                const card = document.createElement('div');
                card.className = 'figma-ticket-stub';
                card.innerHTML = `
                    <div class="stub-ticket-title">${escapeHtml(item.name)} &times; ${item.qty}</div>
                    <div class="stub-ticket-bottom">
                        <div>
                            <div class="stub-attendee-name">${escapeHtml(buyerName)}</div>
                            <div class="stub-attendee-email">${escapeHtml(buyerEmail)}</div>
                        </div>
                        <div class="stub-price-tag">${item.subtotal > 0 ? rupiah(item.subtotal) : 'Gratis'}</div>
                    </div>
                `;
                summaryTicketCards.appendChild(card);
            });
        }

        // Kontrol tombol + dan - tiket
        document.addEventListener('click', function(e) {
            const plusBtn = e.target.closest('.btn-plus');
            const minusBtn = e.target.closest('.btn-minus');

            if (plusBtn) {
                const input = document.getElementById('quantity_' + plusBtn.dataset.id);
                if (!input || input.disabled) return;

                const maxAttr = input.getAttribute('max');
                const max = (maxAttr !== null && maxAttr !== '' && !isNaN(maxAttr)) ? Number(maxAttr) : 999;
                const val = Number(input.value) || 0;

                if (val < max) {
                    input.value = val + 1;
                    updateCheckoutState();
                }
            }

            if (minusBtn) {
                const input = document.getElementById('quantity_' + minusBtn.dataset.id);
                if (!input || input.disabled) return;

                const val = Number(input.value) || 0;
                if (val > 0) {
                    input.value = val - 1;
                    updateCheckoutState();
                }
            }
        });

        // Navigasi antar-step Figma
        btnToStep2.addEventListener('click', function() {
            if (getSelectedItems().length === 0) return;
            goToStep(2);
        });

        btnBackTo1.addEventListener('click', function() {
            goToStep(1);
        });

        btnToStep3.addEventListener('click', function() {
            if (!buyerNameInput.reportValidity() ||
                !buyerEmailInput.reportValidity() ||
                !buyerPhoneInput.reportValidity()) {
                return;
            }
            renderOrderSummaryStubs();
            goToStep(3);
        });

        btnBackTo2.addEventListener('click', function() {
            goToStep(2);
        });

        updateCheckoutState();

        // Submit ke CheckoutController@store -> Midtrans Snap
        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            submitButton.disabled = true;
            submitLabel.textContent = 'Memproses Pembayaran...';

            const formData = new FormData(checkoutForm);

            try {
                const response = await fetch("{{ route('checkout.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    let errorMsg = result.message || 'Gagal memproses pesanan.';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join('\n');
                    }
                    alert(errorMsg);

                    submitButton.disabled = false;
                    submitLabel.textContent = 'Pay Now';
                    return;
                }

                window.snap.pay(result.snap_token, {
                    onSuccess: function() {
                        alert('Pembayaran berhasil!');
                        window.location.href = '/orders/' + result.order_code + '/ticket';
                    },
                    onPending: function() {
                        alert('Silakan selesaikan pembayaran Anda sesuai instruksi Midtrans.');
                        window.location.href = '/orders/' + result.order_code + '/ticket';
                    },
                    onError: function() {
                        alert('Pembayaran gagal!');
                        submitButton.disabled = false;
                        submitLabel.textContent = 'Pay Now';
                    },
                    onClose: function() {
                        alert('Pop-up pembayaran ditutup sebelum transaksi selesai.');
                        submitButton.disabled = false;
                        submitLabel.textContent = 'Pay Now';
                    }
                });

            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan jaringan.');
                submitButton.disabled = false;
                submitLabel.textContent = 'Pay Now';
            }
        });
    });
</script>
@endpush