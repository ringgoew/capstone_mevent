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

                <div id="ticket-container">
                    <div class="ticket-card-item">
                        <div class="ticket-header">
                            <span class="ticket-title">Jenis Tiket #1</span>
                            <button type="button" class="btn-remove-ticket" onclick="this.closest('.ticket-card-item').remove()">Hapus</button>
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
                                <input type="number" name="tickets[0][max_per_transaction]" value="5" min="1" required>
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
    const container = document.getElementById('ticket-container');
    const addButton = document.getElementById('add-ticket');
    let ticketIndex = @json(count($oldTickets ?? []));

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