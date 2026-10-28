@extends('layouts.eo')

@section('title', 'Buat Event Baru - M!VENT')

@section('content')
<div class="form-container-wide">

    <div class="page-title" style="margin-bottom: 25px;">
        <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 24px;">Buat Event Baru</h1>
        <p style="color: #73778b;">Isi informasi event dan atur tiket sebelum diajukan ke admin.</p>
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

    @if (session('success'))
    <div class="alert alert-success" style="background: #eaf8ee; color: #246b39; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('eo.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. INFORMASI EVENT -->
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">1. Informasi Event</h3>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Event *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: E-Fest 2027" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="category_id" style="display: block; font-weight: bold; margin-bottom: 8px;">Kategori Event *</label>
                <select id="category_id" name="category_id" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px;">Deskripsi Event *</label>
                <textarea id="description" name="description" placeholder="Jelaskan informasi dan kegiatan event..." style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px; min-height: 110px;" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="banner_image" style="display: block; font-weight: bold; margin-bottom: 8px;">Banner Event *</label>
                <input type="file" id="banner_image" name="banner_image" accept=".jpg,.jpeg,.png" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                <p style="color: #777b8d; font-size: 12px; margin-top: 6px;">Format JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
            </div>
        </section>

        <!-- 2. LOKASI EVENT -->
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">2. Lokasi Event</h3>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="venue_name" style="display: block; font-weight: bold; margin-bottom: 8px;">Nama Tempat *</label>
                <input type="text" id="venue_name" name="venue_name" value="{{ old('venue_name') }}" placeholder="Contoh: Gedung Serbaguna" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="city" style="display: block; font-weight: bold; margin-bottom: 8px;">Kota *</label>
                <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="Contoh: Jember" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="address" style="display: block; font-weight: bold; margin-bottom: 8px;">Alamat Lengkap *</label>
                <textarea id="address" name="address" placeholder="Masukkan alamat lengkap lokasi event" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>{{ old('address') }}</textarea>
            </div>
        </section>

        <!-- 3. WAKTU -->
        <section class="card" style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.04);">
            <h3 style="margin-top: 0; padding-bottom: 14px; border-bottom: 1px solid #eeeef4;">3. Jadwal Event</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="start_time" style="display: block; font-weight: bold; margin-bottom: 8px;">Waktu Mulai *</label>
                    <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label for="end_time" style="display: block; font-weight: bold; margin-bottom: 8px;">Waktu Selesai *</label>
                    <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" style="width: 100%; padding: 12px; border: 1px solid #d9dbea; border-radius: 7px;" required>
                </div>
            </div>
        </section>

        <!-- 4. TIKET DAN KUOTA -->
        <section>
            <div class="form-section-card">
                <h3 class="form-section-title">4. Jenis Tiket dan Kuota</h3>

                <div id="ticket-container" data-initial-count="{{ count(old('tickets', [])) > 0 ? count(old('tickets', [])) : 1 }}">
                    <div class="ticket-card-item">
                        <div class="ticket-header">
                            <span class="ticket-title">Jenis Tiket #1</span>
                        </div>

                        <div class="ticket-form-grid">
                            <div class="form-group">
                                <label>Nama Tiket <span style="color:red;">*</span></label>
                                <input type="text" name="tickets[0][name]" placeholder="Contoh: Reguler / VIP" required>
                            </div>

                            <div class="form-group">
                                <label>Harga Tiket (Rp) <span style="color:red;">*</span></label>
                                <input type="number" name="tickets[0][price]" placeholder="0 jika Gratis" min="0" required>
                            </div>

                            <div class="form-group">
                                <label>Kuota Tiket <span style="color:red;">*</span></label>
                                <input type="number" name="tickets[0][quota]" placeholder="Contoh: 100" min="1" required>
                            </div>

                            <div class="form-group">
                                <label>Maksimal Tiket / Transaksi <span style="color:red;">*</span></label>
                                <input type="number" name="tickets[0][max_per_order]" value="5" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-add-ticket" id="add-ticket">
                    + Tambah Jenis Tiket
                </button>
            </div>
        </section>

        <!-- TOMBOL -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 40px;">
            <a href="{{ route('eo.events.index') }}" class="btn btn-secondary" style="background: #ecebff; color: #5148c7; padding: 12px 18px; border-radius: 7px; text-decoration: none; font-weight: bold;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="background: #635bdb; color: white; border: none; padding: 12px 18px; border-radius: 7px; font-weight: bold; cursor: pointer;">
                Simpan & Ajukan Event
            </button>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('ticket-container');
        const addButton = document.getElementById('add-ticket');

        // Membaca jumlah tiket dari atribut HTML (aman dari format error VS Code)
        let ticketIndex = parseInt(container.getAttribute('data-initial-count')) || 1;

        window.updateTicketNumbers = function() {
            const cards = container.querySelectorAll('.ticket-card-item');
            cards.forEach((card, index) => {
                const titleSpan = card.querySelector('.ticket-title');
                if (titleSpan) {
                    titleSpan.textContent = 'Jenis Tiket #' + (index + 1);
                }
                const removeButton = card.querySelector('.btn-remove-ticket');
                if (removeButton) {
                    if (cards.length === 1) {
                        removeButton.disabled = true;
                        removeButton.style.opacity = '0.5';
                        removeButton.style.cursor = 'not-allowed';
                    } else {
                        removeButton.disabled = false;
                        removeButton.style.opacity = '1';
                        removeButton.style.cursor = 'pointer';
                    }
                }
            });
        };

        window.removeTicketItem = function(button) {
            const cards = container.querySelectorAll('.ticket-card-item');
            if (cards.length > 1) {
                button.closest('.ticket-card-item').remove();
                window.updateTicketNumbers();
            }
        };

        if (addButton) {
            addButton.addEventListener('click', function() {
                const card = document.createElement('div');
                card.className = 'ticket-card-item';

                card.innerHTML = `
                    <div class="ticket-header">
                        <span class="ticket-title">Jenis Tiket #${ticketIndex + 1}</span>
                        <button type="button" class="btn-remove-ticket" onclick="removeTicketItem(this)">Hapus</button>
                    </div>

                    <div class="ticket-form-grid">
                        <div class="form-group">
                            <label>Nama Tiket <span style="color:red;">*</span></label>
                            <input type="text" name="tickets[${ticketIndex}][name]" placeholder="Contoh: Reguler / VIP" required>
                        </div>

                        <div class="form-group">
                            <label>Harga Tiket (Rp) <span style="color:red;">*</span></label>
                            <input type="number" name="tickets[${ticketIndex}][price]" placeholder="0 jika Gratis" min="0" required>
                        </div>

                        <div class="form-group">
                            <label>Kuota Tiket <span style="color:red;">*</span></label>
                            <input type="number" name="tickets[${ticketIndex}][quota]" placeholder="Contoh: 100" min="1" required>
                        </div>

                        <div class="form-group">
                            <label>Maksimal Tiket / Transaksi <span style="color:red;">*</span></label>
                            <input type="number" name="tickets[${ticketIndex}][max_per_order]" value="5" min="1" required>
                        </div>
                    </div>
                `;

                container.appendChild(card);
                ticketIndex++;
                window.updateTicketNumbers();
            });
        }

        window.updateTicketNumbers();
    });
</script>
@endsection