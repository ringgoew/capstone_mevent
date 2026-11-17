@extends('layouts.eo')

@section('title', 'Edit Event — ' . $event->title)

@section('content')
<div class="form-container-wide">

    <div class="page-title" style="margin-bottom: 28px;">
        <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 6px;">Edit Event</h1>
        <p style="color: #64748B; font-size: 15px;">Perbarui informasi event dan konfigurasi jenis tiket.</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-error">
        <strong>Periksa kembali data berikut:</strong>
        <ul style="margin-top: 6px; padding-left: 18px; list-style: disc;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('eo.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. INFORMASI EVENT -->
        <section class="form-section-card">
            <h3 class="form-section-title">1. Informasi Event</h3>

            <div class="form-group">
                <label for="title">Nama Event *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required>
            </div>

            <div class="form-group">
                <label for="category_id">Kategori Event *</label>
                <select id="category_id" name="category_id" required>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Event *</label>
                <textarea id="description" name="description" required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="banner_image">Banner Event (Opsional)</label>
                @if ($event->banner_image)
                <div style="margin-bottom: 12px;">
                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Banner Event" style="max-height: 140px; border-radius: 8px; border: 1.5px solid #E2E8F0; object-fit: cover;">
                </div>
                @endif
                <input type="file" id="banner_image" name="banner_image" accept=".jpg,.jpeg,.png">
                <p style="color: #94A3B8; font-size: 12px; margin-top: 6px;">Biarkan kosong jika tidak ingin mengubah banner.</p>
            </div>
        </section>

        <!-- 2. LOKASI EVENT -->
        <section class="form-section-card">
            <h3 class="form-section-title">2. Lokasi Event</h3>

            <div class="form-group">
                <label for="venue_name">Nama Tempat / Venue *</label>
                <input type="text" id="venue_name" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required>
            </div>

            <div class="form-group">
                <label for="city">Kota *</label>
                <input type="text" id="city" name="city" value="{{ old('city', $event->city) }}" required>
            </div>

            <div class="form-group">
                <label for="address">Alamat Lengkap *</label>
                <textarea id="address" name="address" required>{{ old('address', $event->address) }}</textarea>
            </div>
        </section>

        <!-- 3. WAKTU -->
        <section class="form-section-card">
            <h3 class="form-section-title">3. Jadwal Event</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <div class="form-group">
                    <label for="start_time">Waktu Mulai *</label>
                    <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group">
                    <label for="end_time">Waktu Selesai *</label>
                    <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>
        </section>

        <!-- 4. TIKET DAN KUOTA -->
        <section class="form-section-card">
            <h3 class="form-section-title">4. Jenis Tiket dan Kuota</h3>

            <div id="ticket-container">
                @php
                $existingTickets = old('tickets', $tickets->map(function($t) {
                    return [
                        'name' => $t->name,
                        'price' => $t->price,
                        'quota' => $t->quota,
                        'max_per_order' => $t->max_per_order,
                        'start_sales' => $t->start_sales ? \Carbon\Carbon::parse($t->start_sales)->format('Y-m-d\TH:i') : '',
                        'end_sales' => $t->end_sales ? \Carbon\Carbon::parse($t->end_sales)->format('Y-m-d\TH:i') : '',
                    ];
                })->toArray());
                @endphp

                @foreach ($existingTickets as $i => $ticket)
                <div class="ticket-card-item">
                    <div class="ticket-header">
                        <span class="ticket-title">Jenis Tiket #{{ $i + 1 }}</span>
                        <button type="button" class="btn-remove-ticket remove-ticket" {{ count($existingTickets) === 1 ? 'disabled' : '' }}>Hapus</button>
                    </div>

                    <div class="ticket-form-grid">
                        <div class="form-group">
                            <label>Nama Tiket <span style="color:#EF4444;">*</span></label>
                            <input type="text" name="tickets[{{ $i }}][name]" value="{{ $ticket['name'] ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label>Harga Tiket (Rp) <span style="color:#EF4444;">*</span></label>
                            <input type="number" name="tickets[{{ $i }}][price]" value="{{ $ticket['price'] ?? 0 }}" min="0" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label>Kuota Tiket <span style="color:#EF4444;">*</span></label>
                            <input type="number" name="tickets[{{ $i }}][quota]" value="{{ $ticket['quota'] ?? 100 }}" min="1" required>
                        </div>

                        <div class="form-group">
                            <label>Maksimal Tiket / Transaksi <span style="color:#EF4444;">*</span></label>
                            <input type="number" name="tickets[{{ $i }}][max_per_order]" value="{{ $ticket['max_per_order'] ?? 5 }}" min="1" required>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-ticket" class="btn-add-ticket">
                + Tambah Jenis Tiket
            </button>
        </section>

        <!-- TOMBOL AKSI -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; margin-bottom: 20px;">
            <a href="{{ route('eo.events.index') }}" class="btn" style="background: #F1F5F9; color: #475569; padding: 12px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid #E2E8F0;">
                Batal
            </a>
            <button type="submit" class="btn" style="background: #38BDF8; color: #0C4A6E; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; width: auto;">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
    const container = document.getElementById('ticket-container');
    const addButton = document.getElementById('add-ticket');
    let ticketIndex = @json(count($existingTickets));

    function updateTicketNumbers() {
        const cards = container.querySelectorAll('.ticket-card-item');
        cards.forEach((card, index) => {
            const titleSpan = card.querySelector('.ticket-title');
            if (titleSpan) titleSpan.textContent = 'Jenis Tiket #' + (index + 1);
            const removeButton = card.querySelector('.remove-ticket');
            if (removeButton) {
                removeButton.disabled = cards.length === 1;
                removeButton.style.opacity = cards.length === 1 ? '0.5' : '1';
                removeButton.style.cursor = cards.length === 1 ? 'not-allowed' : 'pointer';
            }
        });
    }

    addButton.addEventListener('click', function() {
        const card = document.createElement('div');
        card.className = 'ticket-card-item';
        card.innerHTML = `
            <div class="ticket-header">
                <span class="ticket-title">Jenis Tiket #${ticketIndex + 1}</span>
                <button type="button" class="btn-remove-ticket remove-ticket">Hapus</button>
            </div>
            <div class="ticket-form-grid">
                <div class="form-group">
                    <label>Nama Tiket <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="tickets[${ticketIndex}][name]" placeholder="Contoh: VIP" required>
                </div>
                <div class="form-group">
                    <label>Harga Tiket (Rp) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="tickets[${ticketIndex}][price]" min="0" step="0.01" value="0" required>
                </div>
                <div class="form-group">
                    <label>Kuota Tiket <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="tickets[${ticketIndex}][quota]" min="1" value="100" required>
                </div>
                <div class="form-group">
                    <label>Maksimal Tiket / Transaksi <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="tickets[${ticketIndex}][max_per_order]" min="1" value="5" required>
                </div>
            </div>
        `;
        container.appendChild(card);
        ticketIndex++;
        updateTicketNumbers();
    });

    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-ticket')) {
            const cards = container.querySelectorAll('.ticket-card-item');
            if (cards.length > 1) {
                event.target.closest('.ticket-card-item').remove();
                updateTicketNumbers();
            }
        }
    });

    updateTicketNumbers();
</script>
@endsection
