
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event - POOKIEMAX</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #252b42;
        }

        .navbar {
            background: #29264b;
            color: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1000px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h1 {
            margin-bottom: 8px;
        }

        .page-title p {
            color: #73778b;
            margin-top: 0;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        }

        .card h3 {
            margin-top: 0;
            padding-bottom: 14px;
            border-bottom: 1px solid #eeeef4;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d9dbea;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        textarea {
            resize: vertical;
            min-height: 110px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .ticket-card {
            border: 1px solid #e0e1ed;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 15px;
            background: #fcfcff;
        }

        .ticket-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .ticket-heading h4 {
            margin: 0;
        }

        .btn {
            border: none;
            border-radius: 7px;
            padding: 12px 18px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #635bdb;
            color: white;
        }

        .btn-secondary {
            background: #ecebff;
            color: #5148c7;
        }

        .btn-danger {
            background: #ffe8e8;
            color: #c03939;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 40px;
        }

        .error-box {
            background: #fff0f0;
            color: #a52525;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success-box {
            background: #eaf8ee;
            color: #246b39;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .hint {
            color: #777b8d;
            font-size: 12px;
            margin-top: 6px;
        }

        @media (max-width: 650px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .card {
                padding: 18px;
            }

            .actions {
                flex-direction: column;
            }

            .actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h2>M!VENT</h2>
        <a href="{{ route('eo.events.index') }}">Daftar Event</a>
    </nav>

    <main class="container">

        <div class="page-title">
            <h1>Buat Event Baru</h1>
            <p>Isi informasi event dan atur tiket sebelum diajukan ke admin.</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="success-box">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('eo.events.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <!-- INFORMASI EVENT -->
            <section class="card">
                <h3>1. Informasi Event</h3>

                <div class="form-group">
                    <label for="title">Nama Event *</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: E-Fest 2027"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori Event *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Event *</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Jelaskan informasi dan kegiatan event..."
                        required
                    >{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="banner_image">Banner Event *</label>
                    <input
                        type="file"
                        id="banner_image"
                        name="banner_image"
                        accept=".jpg,.jpeg,.png"
                        required
                    >
                    <p class="hint">Format JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
                </div>
            </section>

            <!-- LOKASI -->
            <section class="card">
                <h3>2. Lokasi Event</h3>

                <div class="form-group">
                    <label for="venue_name">Nama Tempat *</label>
                    <input
                        type="text"
                        id="venue_name"
                        name="venue_name"
                        value="{{ old('venue_name') }}"
                        placeholder="Contoh: Gedung Serbaguna"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="city">Kota *</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city') }}"
                        placeholder="Contoh: Jember"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="address">Alamat Lengkap *</label>
                    <textarea
                        id="address"
                        name="address"
                        placeholder="Masukkan alamat lengkap lokasi event"
                        required
                    >{{ old('address') }}</textarea>
                </div>
            </section>

            <!-- WAKTU -->
            <section class="card">
                <h3>3. Jadwal Event</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">Waktu Mulai *</label>
                        <input
                            type="datetime-local"
                            id="start_time"
                            name="start_time"
                            value="{{ old('start_time') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="end_time">Waktu Selesai *</label>
                        <input
                            type="datetime-local"
                            id="end_time"
                            name="end_time"
                            value="{{ old('end_time') }}"
                            required
                        >
                    </div>
                </div>
            </section>

            <!-- TIKET DAN KUOTA -->
            <section class="card">
                <h3>4. Jenis Tiket dan Kuota</h3>

                <p class="hint">
                    Tambahkan jenis tiket, harga, jumlah kuota, dan periode penjualannya.
                </p>

                <div id="ticket-container">
                    @php
                        $oldTickets = old('tickets', [
                            [
                                'name' => 'Regular',
                                'price' => 0,
                                'quota' => 100,
                                'max_per_order' => 5,
                                'start_sales' => '',
                                'end_sales' => '',
                            ]
                        ]);
                    @endphp

                    @foreach ($oldTickets as $i => $ticket)
                        <div class="ticket-card">
                            <div class="ticket-heading">
                                <h4>Jenis Tiket <span class="ticket-number">{{ $i + 1 }}</span></h4>

                                <button
                                    type="button"
                                    class="btn btn-danger remove-ticket"
                                    {{ count($oldTickets) === 1 ? 'disabled' : '' }}
                                >
                                    Hapus
                                </button>
                            </div>

                            <div class="form-group">
                                <label>Nama Tiket *</label>
                                <input
                                    type="text"
                                    name="tickets[{{ $i }}][name]"
                                    value="{{ $ticket['name'] ?? '' }}"
                                    placeholder="Contoh: Early Bird"
                                    required
                                >
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Harga Tiket (Rp) *</label>
                                    <input
                                        type="number"
                                        name="tickets[{{ $i }}][price]"
                                        value="{{ $ticket['price'] ?? 0 }}"
                                        min="0"
                                        step="0.01"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label>Kuota Tiket *</label>
                                    <input
                                        type="number"
                                        name="tickets[{{ $i }}][quota]"
                                        value="{{ $ticket['quota'] ?? 100 }}"
                                        min="1"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Maksimal Tiket per Transaksi *</label>
                                <input
                                    type="number"
                                    name="tickets[{{ $i }}][max_per_order]"
                                    value="{{ $ticket['max_per_order'] ?? 5 }}"
                                    min="1"
                                    required
                                >
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Mulai Penjualan</label>
                                    <input
                                        type="datetime-local"
                                        name="tickets[{{ $i }}][start_sales]"
                                        value="{{ $ticket['start_sales'] ?? '' }}"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>Akhir Penjualan</label>
                                    <input
                                        type="datetime-local"
                                        name="tickets[{{ $i }}][end_sales]"
                                        value="{{ $ticket['end_sales'] ?? '' }}"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-ticket" class="btn btn-secondary">
                    + Tambah Jenis Tiket
                </button>
            </section>

            <!-- TOMBOL -->
            <div class="actions">
                <a href="{{ route('eo.events.index') }}"
                   class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan sebagai Draft
                </button>
            </div>

        </form>
    </main>

    <script>
        const container = document.getElementById('ticket-container');
        const addButton = document.getElementById('add-ticket');

        let ticketIndex = {{ count($oldTickets) }};

        function updateTicketNumbers() {
            const cards = container.querySelectorAll('.ticket-card');

            cards.forEach((card, index) => {
                card.querySelector('.ticket-number').textContent = index + 1;

                const removeButton = card.querySelector('.remove-ticket');
                removeButton.disabled = cards.length === 1;
            });
        }

        addButton.addEventListener('click', function () {
            const card = document.createElement('div');

            card.className = 'ticket-card';

            card.innerHTML = `
                <div class="ticket-heading">
                    <h4>Jenis Tiket <span class="ticket-number"></span></h4>
                    <button type="button" class="btn btn-danger remove-ticket">
                        Hapus
                    </button>
                </div>

                <div class="form-group">
                    <label>Nama Tiket *</label>
                    <input type="text" name="tickets[${ticketIndex}][name]"
                           placeholder="Contoh: VIP" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Harga Tiket (Rp) *</label>
                        <input type="number" name="tickets[${ticketIndex}][price]"
                               min="0" step="0.01" value="0" required>
                    </div>

                    <div class="form-group">
                        <label>Kuota Tiket *</label>
                        <input type="number" name="tickets[${ticketIndex}][quota]"
                               min="1" value="100" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Maksimal Tiket per Transaksi *</label>
                    <input type="number"
                           name="tickets[${ticketIndex}][max_per_order]"
                           min="1" value="5" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Mulai Penjualan</label>
                        <input type="datetime-local"
                               name="tickets[${ticketIndex}][start_sales]">
                    </div>

                    <div class="form-group">
                        <label>Akhir Penjualan</label>
                        <input type="datetime-local"
                               name="tickets[${ticketIndex}][end_sales]">
                    </div>
                </div>
            `;

            container.appendChild(card);
            ticketIndex++;

            updateTicketNumbers();
        });

        container.addEventListener('click', function (event) {
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

</body>
</html>