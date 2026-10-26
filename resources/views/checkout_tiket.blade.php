
@extends('layouts.main')

@section('title', 'Checkout Tiket - M!VENT')

@section('content')
<div class="detail-container">
    <div class="checkout-header">
        <h1>Checkout Tiket</h1>
        <p>Pilih satu atau beberapa jenis tiket sesuai kebutuhanmu.</p>
    </div>

    <div class="checkout-layout">
        <div class="checkout-form-section">
            <form action="#" method="POST" id="checkout-form">
                @csrf

                <section class="checkout-card">
                    <h2>Informasi Pembeli</h2>

                    <div class="form-group">
                        <label for="buyer_name">Nama Lengkap</label>
                        <input
                            type="text"
                            id="buyer_name"
                            name="buyer_name"
                            value="{{ old('buyer_name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="buyer_email">Alamat Email</label>
                        <input
                            type="email"
                            id="buyer_email"
                            name="buyer_email"
                            value="{{ old('buyer_email') }}"
                            placeholder="nama@email.com"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="buyer_phone">Nomor HP</label>
                        <input
                            type="tel"
                            id="buyer_phone"
                            name="buyer_phone"
                            value="{{ old('buyer_phone') }}"
                            placeholder="Masukkan nomor HP"
                            required
                        >
                    </div>
                </section>

                <section class="checkout-card">
                    <h2>Pilih Jenis Tiket</h2>
                    <p>Kamu bisa memilih lebih dari satu jenis tiket.</p>

                    <div class="checkout-ticket-list">
                        @forelse ($tickets as $ticket)
                            @php
                                $available = max(
                                    0,
                                    $ticket->quota
                                    - ($ticket->reserved_quota ?? 0)
                                    - ($ticket->sold_quota ?? 0)
                                );
                            @endphp

                            <div class="checkout-ticket">
                                <div class="checkout-ticket-info">
                                    <h3>{{ $ticket->name }}</h3>

                                    <p>
                                        @if ($ticket->price > 0)
                                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                        @else
                                            Gratis
                                        @endif
                                    </p>

                                    <small>
                                        @if ($available > 0)
                                            Tersedia {{ $available }} tiket
                                        @else
                                            Tiket habis
                                        @endif
                                    </small>
                                </div>

                                <div class="checkout-ticket-quantity">
                                    <label for="quantity_{{ $ticket->id }}">
                                        Jumlah
                                    </label>

                                    <input
                                        type="number"
                                        id="quantity_{{ $ticket->id }}"
                                        name="quantities[{{ $ticket->id }}]"
                                        class="ticket-quantity"
                                        data-price="{{ $ticket->price }}"
                                        min="0"
                                        max="{{ min($available, $ticket->max_per_order ?? $available) }}"
                                        value="0"
                                        @if ($available <= 0) disabled @endif
                                    >
                                </div>
                            </div>
                        @empty
                            <p>Belum ada tiket tersedia untuk event ini.</p>
                        @endforelse
                    </div>
                </section>
            </form>
        </div>

        <aside class="checkout-summary">
            <h2>Ringkasan Pesanan</h2>

            <h3>{{ $event->title }}</h3>

            <p>
                {{ $event->venue_name }}
                @if ($event->city)
                    , {{ $event->city }}
                @endif
            </p>

            <hr>

            <div id="checkout-selected-tickets">
                <p>Belum ada tiket dipilih.</p>
            </div>

            <hr>

            <div class="checkout-summary-row">
                <span>Total tiket</span>
                <strong id="checkout-total-quantity">0</strong>
            </div>

            <div class="checkout-total">
                <span>Total pembayaran</span>
                <strong id="checkout-total-price">Rp 0</strong>
            </div>

            <button
                type="submit"
                form="checkout-form"
                id="checkout-submit"
                class="btn-buy-ticket"
                disabled
            >
                Lanjutkan Pemesanan
            </button>

            <p>Periksa kembali pilihan tiket dan data pembeli sebelum melanjutkan.</p>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quantityInputs = document.querySelectorAll('.ticket-quantity');
    const selectedTickets = document.getElementById('checkout-selected-tickets');
    const totalQuantityElement = document.getElementById('checkout-total-quantity');
    const totalPriceElement = document.getElementById('checkout-total-price');
    const submitButton = document.getElementById('checkout-submit');

    const rupiah = amount =>
        'Rp ' + amount.toLocaleString('id-ID');

    function updateCheckout() {
        let totalQuantity = 0;
        let totalPrice = 0;
        selectedTickets.replaceChildren();

        quantityInputs.forEach(input => {
            const quantity = Math.max(
                0,
                Math.min(
                    Number(input.value) || 0,
                    Number(input.max) || 0
                )
            );

            input.value = quantity;

            if (quantity === 0) return;

            const price = Number(input.dataset.price) || 0;
            const subtotal = quantity * price;
            const label = document.querySelector(
                'label[for="' + input.id + '"]'
            );
            const ticketCard = input.closest('.checkout-ticket');
            const ticketName = ticketCard.querySelector('h3').textContent;

            const row = document.createElement('p');
            row.textContent =
                ticketName + ' × ' + quantity + ' — ' + rupiah(subtotal);

            selectedTickets.appendChild(row);

            totalQuantity += quantity;
            totalPrice += subtotal;
        });

        if (totalQuantity === 0) {
            selectedTickets.textContent = 'Belum ada tiket dipilih.';
        }

        totalQuantityElement.textContent = totalQuantity + ' tiket';
        totalPriceElement.textContent = rupiah(totalPrice);
        submitButton.disabled = totalQuantity === 0;
    }

    quantityInputs.forEach(input => {
        input.addEventListener('input', updateCheckout);
        input.addEventListener('change', updateCheckout);
    });

    updateCheckout();
});
</script>
@endpush
