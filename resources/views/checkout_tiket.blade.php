@extends('layouts.main')

@section('title', 'Checkout Tiket - ' . $event->title)

@section('content')
<div class="checkout-wrapper">
    <div class="checkout-header-title">
        <h1>Checkout Tiket</h1>
        <p>Lengkapi data pemesan dan pilih tiket yang ingin kamu beli.</p>
    </div>

    <div class="checkout-grid">
        <div class="checkout-main-content">
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf
                
                {{-- PERBAIKAN: Tambahkan hidden input event_id yang diwajibkan oleh Controller --}}
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="checkout-card">
                    <h2>Informasi Pemesan</h2>

                    <div class="form-group">
                        <label for="buyer_name">Nama Lengkap</label>
                        <input
                            type="text"
                            id="buyer_name"
                            name="buyer_name"
                            value="{{ old('buyer_name') }}"
                            placeholder="Contoh: Budi Santoso"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="buyer_email">Alamat Email</label>
                        <input
                            type="email"
                            id="buyer_email"
                            name="buyer_email"
                            value="{{ old('buyer_email') }}"
                            placeholder="nama@email.com"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="buyer_phone">Nomor Whatsapp / HP</label>
                        <input
                            type="tel"
                            id="buyer_phone"
                            name="buyer_phone"
                            value="{{ old('buyer_phone') }}"
                            placeholder="081234567890"
                            required>
                    </div>
                </div>

                <div class="checkout-card">
                    <h2>Pilih Tiket</h2>

                    <div class="checkout-ticket-list">
                        @forelse ($tickets as $ticket)
                        @php
                        $available = max(
                        0,
                        $ticket->quota
                        - ($ticket->reserved_quota ?? 0)
                        - ($ticket->sold_quota ?? 0)
                        );

                        // Validasi agar max_per_order tidak menjadi 0
                        $maxPerOrder = (!empty($ticket->max_per_order) && $ticket->max_per_order > 0)
                        ? min($available, $ticket->max_per_order)
                        : $available;
                        @endphp

                        <div class="ticket-item">
                            <div class="ticket-info">
                                <h3>{{ $ticket->name }}</h3>
                                <div class="ticket-price">
                                    @if ($ticket->price > 0)
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    @else
                                    Gratis
                                    @endif
                                </div>
                                <span class="ticket-quota">
                                    @if ($available > 0)
                                    Sisa {{ $available }} tiket
                                    @else
                                    Tiket Habis
                                    @endif
                                </span>
                            </div>

                            <div class="ticket-qty-control">
                                <button type="button" class="btn-qty btn-minus" data-id="{{ $ticket->id }}" @if ($available <=0) disabled @endif>-</button>
                                <input
                                    type="number"
                                    id="quantity_{{ $ticket->id }}"
                                    name="quantities[{{ $ticket->id }}]"
                                    class="ticket-quantity"
                                    data-price="{{ $ticket->price }}"
                                    data-name="{{ $ticket->name }}"
                                    min="0"
                                    max="{{ $maxPerOrder }}"
                                    value="0"
                                    readonly
                                    @if ($available <=0) disabled @endif>
                                <button type="button" class="btn-qty btn-plus" data-id="{{ $ticket->id }}" @if ($available <=0) disabled @endif>+</button>
                            </div>
                        </div>
                        @empty
                        <p>Belum ada tipe tiket yang tersedia untuk event ini.</p>
                        @endforelse
                    </div>
                </div>
            </form>
        </div>

        <aside class="summary-card">
            <h2>Ringkasan Pesanan</h2>

            <div class="summary-event-title">{{ $event->title }}</div>
            <div class="summary-event-venue">
                📍 {{ $event->venue_name }}@if ($event->city), {{ $event->city }}@endif
            </div>

            <hr class="summary-divider">

            <div id="checkout-selected-tickets">
                <p style="color: #94a3b8; font-size: 0.9rem;">Belum ada tiket dipilih.</p>
            </div>

            <hr class="summary-divider">

            <div class="summary-item-row">
                <span>Total Jumlah</span>
                <strong id="checkout-total-quantity">0 tiket</strong>
            </div>

            <div class="summary-total-row">
                <span>Total Bayar</span>
                <strong id="checkout-total-price" style="color: #2563eb;">Rp 0</strong>
            </div>

            <button
                type="submit"
                form="checkout-form"
                id="checkout-submit"
                class="btn-checkout"
                disabled>
                Lanjutkan Pembayaran
            </button>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.ticket-quantity');
        const selectedTickets = document.getElementById('checkout-selected-tickets');
        const totalQuantityElement = document.getElementById('checkout-total-quantity');
        const totalPriceElement = document.getElementById('checkout-total-price');
        const submitButton = document.getElementById('checkout-submit');
        const checkoutForm = document.getElementById('checkout-form');

        const rupiah = amount => 'Rp ' + amount.toLocaleString('id-ID');

        function updateCheckout() {
            let totalQuantity = 0;
            let totalPrice = 0;
            
            // PERBAIKAN: Gunakan innerHTML agar lebih kompatibel daripada replaceChildren()
            selectedTickets.innerHTML = '';

            quantityInputs.forEach(input => {
                const quantity = Number(input.value) || 0;
                if (quantity === 0) return;

                const price = Number(input.dataset.price) || 0;
                const ticketName = input.dataset.name;
                const subtotal = quantity * price;

                const row = document.createElement('div');
                row.className = 'summary-item-row';
                row.innerHTML = `<span>${ticketName} × ${quantity}</span><strong>${rupiah(subtotal)}</strong>`;
                selectedTickets.appendChild(row);

                totalQuantity += quantity;
                totalPrice += subtotal;
            });

            if (totalQuantity === 0) {
                selectedTickets.innerHTML = '<p style="color: #94a3b8; font-size: 0.9rem;">Belum ada tiket dipilih.</p>';
            }

            totalQuantityElement.textContent = totalQuantity + ' tiket';
            totalPriceElement.textContent = rupiah(totalPrice);
            submitButton.disabled = totalQuantity === 0;
        }

        document.addEventListener('click', function(e) {
            const plusBtn = e.target.closest('.btn-plus');
            const minusBtn = e.target.closest('.btn-minus');

            if (plusBtn) {
                const id = plusBtn.dataset.id;
                const input = document.getElementById('quantity_' + id);
                if (!input || input.disabled) return;

                const maxAttr = input.getAttribute('max');
                const max = (maxAttr !== null && maxAttr !== '' && !isNaN(maxAttr)) ? Number(maxAttr) : 999;
                let val = Number(input.value) || 0;

                if (val < max) {
                    input.value = val + 1;
                    updateCheckout();
                }
            }

            if (minusBtn) {
                const id = minusBtn.dataset.id;
                const input = document.getElementById('quantity_' + id);
                if (!input || input.disabled) return;

                let val = Number(input.value) || 0;
                if (val > 0) {
                    input.value = val - 1;
                    updateCheckout();
                }
            }
        });

        updateCheckout();

        checkoutForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitButton.disabled = true;
            submitButton.textContent = 'Memproses...';

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
                    // PERBAIKAN: Ekstrak error validasi Laravel dengan lebih akurat
                    let errorMsg = result.message || 'Gagal memproses pesanan.';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join('\n');
                    }
                    alert(errorMsg);
                    
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lanjutkan Pembayaran';
                    return;
                }

                window.snap.pay(result.snap_token, {
                    onSuccess: function(res) {
                        alert('Pembayaran berhasil!');
                        window.location.href = '/';
                    },
                    onPending: function(res) {
                        alert('Silakan selesaikan pembayaran Anda.');
                        window.location.reload();
                    },
                    onError: function(res) {
                        alert('Pembayaran gagal!');
                        submitButton.disabled = false;
                        submitButton.textContent = 'Lanjutkan Pembayaran';
                    },
                    onClose: function() {
                        alert('Pop-up pembayaran ditutup sebelum transaksi selesai.');
                        submitButton.disabled = false;
                        submitButton.textContent = 'Lanjutkan Pembayaran';
                    }
                });

            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan jaringan.');
                submitButton.disabled = false;
                submitButton.textContent = 'Lanjutkan Pembayaran';
            }
        });
    });
</script>
@endpush