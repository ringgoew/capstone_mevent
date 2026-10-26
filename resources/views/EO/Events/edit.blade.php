@extends('layouts.eo')

@section('title', 'Edit Event - M!VENT')

@section('content')
<div class="container" style="max-width: 1000px; margin: 20px auto;">

    <div class="page-title" style="margin-bottom: 25px;">
        <h1>Edit Event</h1>
        <p style="color: #73778b;">Perbarui informasi event dan jenis tiket.</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-error" style="background: #fff0f0; color: #a52525; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>Periksa kembali data berikut:</strong>
        <ul>
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
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">1. Informasi Event</h3>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Event *</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Kategori Event *</label>
                <select name="category_id" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Deskripsi Event *</label>
                <textarea name="description" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px; min-height: 110px;" required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Banner Event (Opsional)</label>
                @if ($event->banner_image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Banner Event" style="max-height: 120px; border-radius: 8px;">
                </div>
                @endif
                <input type="file" name="banner_image" accept=".jpg,.jpeg,.png" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;">
                <p style="color: #777b8d; font-size: 12px; margin-top: 6px;">Biarkan kosong jika tidak ingin mengubah banner.</p>
            </div>
        </section>

        <!-- 2. LOKASI EVENT -->
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">2. Lokasi Event</h3>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Tempat *</label>
                <input type="text" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Kota *</label>
                <input type="text" name="city" value="{{ old('city', $event->city) }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Alamat Lengkap *</label>
                <textarea name="address" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>{{ old('address', $event->address) }}</textarea>
            </div>
        </section>

        <!-- 3. WAKTU -->
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">3. Jadwal Event</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Waktu Mulai *</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Waktu Selesai *</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                </div>
            </div>
        </section>

        <!-- 4. TIKET DAN KUOTA -->
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">4. Jenis Tiket dan Kuota</h3>

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
                <div class="ticket-card" style="border: 1px solid #e0e1ed; padding: 18px; border-radius: 10px; margin-bottom: 15px; background: #fcfcff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                        <h4 style="margin: 0;">Jenis Tiket <span class="ticket-number">{{ $i + 1 }}</span></h4>
                        <button type="button" class="btn btn-danger remove-ticket" style="background: #ffe8e8; color: #c03939; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer;" {{ count($existingTickets) === 1 ? 'disabled' : '' }}>Hapus</button>
                    </div>

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Tiket *</label>
                        <input type="text" name="tickets[{{ $i }}][name]" value="{{ $ticket['name'] ?? '' }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                        <div class="form-group" style="margin-bottom: 18px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 8px;">Harga Tiket (Rp) *</label>
                            <input type="number" name="tickets[{{ $i }}][price]" value="{{ $ticket['price'] ?? 0 }}" min="0" step="0.01" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 18px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 8px;">Kuota Tiket *</label>
                            <input type="number" name="tickets[{{ $i }}][quota]" value="{{ $ticket['quota'] ?? 100 }}" min="1" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Maksimal Tiket per Transaksi *</label>
                        <input type="number" name="tickets[{{ $i }}][max_per_order]" value="{{ $ticket['max_per_order'] ?? 5 }}" min="1" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-ticket" class="btn btn-secondary" style="background: #ecebff; color: #5148c7; border: none; padding: 10px 16px; border-radius: 7px; cursor: pointer; font-weight: bold;">
                + Tambah Jenis Tiket
            </button>
        </section>

        <!-- TOMBOL -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 40px;">
            <a href="{{ route('eo.events.index') }}" class="btn btn-secondary" style="background: #ecebff; color: #5148c7; padding: 12px 18px; border-radius: 7px; text-decoration: none; font-weight: bold;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="background: #635bdb; color: white; border: none; padding: 12px 18px; border-radius: 7px; font-weight: bold; cursor: pointer;">
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
        const cards = container.querySelectorAll('.ticket-card');
        cards.forEach((card, index) => {
            card.querySelector('.ticket-number').textContent = index + 1;
            const removeButton = card.querySelector('.remove-ticket');
            removeButton.disabled = cards.length === 1;
        });
    }

    addButton.addEventListener('click', function() {
        const card = document.createElement('div');
        card.className = 'ticket-card';
        card.style.cssText = 'border: 1px solid #e0e1ed; padding: 18px; border-radius: 10px; margin-bottom: 15px; background: #fcfcff;';
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <h4 style="margin: 0;">Jenis Tiket <span class="ticket-number"></span></h4>
                <button type="button" class="btn btn-danger remove-ticket" style="background: #ffe8e8; color: #c03939; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer;">Hapus</button>
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Tiket *</label>
                <input type="text" name="tickets[${ticketIndex}][name]" placeholder="Contoh: VIP" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Harga Tiket (Rp) *</label>
                    <input type="number" name="tickets[${ticketIndex}][price]" min="0" step="0.01" value="0" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                </div>
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px;">Kuota Tiket *</label>
                    <input type="number" name="tickets[${ticketIndex}][quota]" min="1" value="100" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Maksimal Tiket per Transaksi *</label>
                <input type="number" name="tickets[${ticketIndex}][max_per_order]" min="1" value="5" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>
        `;
        container.appendChild(card);
        ticketIndex++;
        updateTicketNumbers();
    });

    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-ticket')) {
            const cards = container.querySelectorAll('.ticket-card');
            if (cards.length > 1) {
                event.target.closest('.ticket-card').remove();
                updateTicketNumbers();
            }
        }
    });

    updateTicketNumbers();
</script>
@endsection