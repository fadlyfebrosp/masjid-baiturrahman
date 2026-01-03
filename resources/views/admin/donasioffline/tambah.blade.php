@extends('admin.components.app')

@section('title', 'Tambah Donasi Offline')

@section('content')

<h1 class="text-2xl font-bold text-green-700 mb-6">Tambah Donasi Offline</h1>

<form method="POST" action="{{ route('admin.donasioffline.store') }}">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ================= LEFT ================= --}}
    <div class="space-y-4">

        {{-- PROGRAM CARD --}}
        <div id="programCard"
             class="bg-white rounded-xl shadow p-5 flex gap-4 hidden">
            <img id="programFoto"
                 class="w-24 h-24 rounded-lg object-cover"
                 src=""
                 alt="">

            <div class="flex-1">
                <h4 id="programJudul" class="font-semibold text-lg">judul</h4>
                <p class="text-sm text-gray-500 mt-2">Sisa Hari</p>
                <p id="programSisaHari" class="font-semibold"></p>
            </div>
        </div>

        {{-- PILIH PROGRAM --}}
        <div class="bg-white rounded-xl shadow p-5 space-y-3">
            <h3 class="font-semibold">Program</h3>

            <select id="kategori"
                    class="w-full border rounded-lg px-3 py-2">
                <option value="">Pilih Kategori</option>
                @foreach ($kategoriProgram as $k)
                    <option value="{{ $k->kategori }}">{{ $k->kategori }}</option>
                @endforeach
            </select>

            <select id="subKategori"
                    class="w-full border rounded-lg px-3 py-2 hidden">
                <option value="">Pilih Sub Kategori</option>
            </select>

            <select id="judulProgram"
                    class="w-full border rounded-lg px-3 py-2 hidden">
                <option value="">Pilih Judul Program</option>
            </select>

            {{-- WAJIB SUBMIT --}}
            <input type="hidden" name="program_id" id="program_id">
        </div>
    </div>

    {{-- ================= RIGHT ================= --}}
    <div class="bg-white rounded-xl shadow p-6 space-y-4">

        {{-- KONTAK --}}
        <div class="space-y-3">
            <h3 class="font-semibold">Kontak Donatur</h3>

            <input type="hidden"
                   name="contactdonasioffline_id"
                   id="contact_id">

            <div>
                <label for="" class="text-sm font-medium">Cari Kontak</label>
                <input type="text"
                       id="searchContact"
                       placeholder="Cari nama / email / nomor"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div id="contactResult"
                 class="border rounded-lg bg-white shadow hidden max-h-48 overflow-y-auto"></div>

            <div>
                <label for="" class="text-sm font-medium">Email</label>
                <input type="email"
                       id="email"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label for="" class="text-sm font-medium">Nomor Telepon</label>
                <input type="text"
                       id="no_telp"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label for="" class="text-sm font-medium">Gender</label>
                <select id="gender"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>

        {{-- NOMINAL --}}
        <div>
            <label for="" class="text-sm font-medium">Nominal</label>
            <input type="number"
                   name="nominal"
                   min="0"
                   class="w-full border rounded-lg px-3 py-2 mt-1"
                   required>
        </div>

        {{-- METODE --}}
        <div>
            <label for="" class="text-sm font-medium">Metode Pembayaran</label>
            <select name="metode_pembayaran"
                    class="w-full border rounded-lg px-3 py-2 mt-1">
                <option value="CASH">CASH</option>
                <option value="TRANSFER">TRANSFER</option>
                <option value="QRIS">QRIS</option>
                <option value="DEBIT">DEBIT</option>
            </select>
        </div>

        {{-- TANGGAL --}}
        <div>
            <label for="" class="text-sm font-medium">Tanggal Transaksi</label>
            <input type="datetime-local"
                   name="tanggal_transaksi"
                   class="w-full border rounded-lg px-3 py-2 mt-1"
                   required>
        </div>

        {{-- ✅ STATUS DONASI --}}
        <div>
            <label for="" class="text-sm font-medium">Status Donasi</label>
            <select name="status"
                    class="w-full border rounded-lg px-3 py-2 mt-1"
                    required>
                <option value="SELESAI">Selesai</option>
                <option value="PROSES">Proses</option>
                <option value="PENDING">Pending</option>
                <option value="GAGAL">Gagal</option>
            </select>
        </div>

        {{-- SUBMIT --}}
        <div class="pt-4 text-right">
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Donasi
            </button>
        </div>

    </div>
</div>
</form>

{{-- DATA --}}
<script>
    const programs = @json($programs);
    const contacts = @json($contacts);
</script>

{{-- ================= JAVASCRIPT ================= --}}
<script>
/* ================= PROGRAM ================= */
const kategori = document.getElementById('kategori');
const subKategori = document.getElementById('subKategori');
const judulProgram = document.getElementById('judulProgram');
const card = document.getElementById('programCard');
const programId = document.getElementById('program_id');

function resetProgram() {
    subKategori.innerHTML = '<option value="">Pilih Sub Kategori</option>';
    judulProgram.innerHTML = '<option value="">Pilih Judul Program</option>';
    subKategori.classList.add('hidden');
    judulProgram.classList.add('hidden');
    card.classList.add('hidden');
    programId.value = '';
}

kategori.addEventListener('change', () => {
    resetProgram();
    if (!kategori.value) return;

    const isZakat = kategori.value.toLowerCase() === 'zakat';

    if (isZakat) {
        const subs = [...new Set(
            programs.filter(p => p.kategori === kategori.value)
                    .map(p => p.sub_kategori)
                    .filter(Boolean)
        )];

        subs.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub;
            opt.textContent = sub.replace(/_/g, ' ').toUpperCase();
            subKategori.appendChild(opt);
        });

        subKategori.classList.remove('hidden');
        return;
    }

    programs.filter(p => p.kategori === kategori.value)
        .forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.judul;
            opt.dataset.program = JSON.stringify(p);
            judulProgram.appendChild(opt);
        });

    judulProgram.classList.remove('hidden');
});

subKategori.addEventListener('change', () => {
    judulProgram.innerHTML = '<option value="">Pilih Judul Program</option>';
    judulProgram.classList.add('hidden');
    card.classList.add('hidden');
    programId.value = '';

    if (!subKategori.value) return;

    programs.filter(p =>
        p.kategori === kategori.value &&
        p.sub_kategori === subKategori.value
    ).forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = p.judul;
        opt.dataset.program = JSON.stringify(p);
        judulProgram.appendChild(opt);
    });

    judulProgram.classList.remove('hidden');
});

judulProgram.addEventListener('change', () => {
    const opt = judulProgram.selectedOptions[0];
    if (!opt || !opt.dataset.program) return;

    const p = JSON.parse(opt.dataset.program);
    document.getElementById('programFoto').src =
        p.foto_url ?? '/assets/img/Image-not-found.png';
    document.getElementById('programJudul').innerText = p.judul;
    document.getElementById('programSisaHari').innerText = p.sisa_hari ?? '-';
    programId.value = p.id;
    card.classList.remove('hidden');
});

/* ================= CONTACT ================= */
const searchInput = document.getElementById('searchContact');
const resultBox = document.getElementById('contactResult');
const contactId = document.getElementById('contact_id');
const emailInput = document.getElementById('email');
const phoneInput = document.getElementById('no_telp');
const genderInput = document.getElementById('gender');

searchInput.addEventListener('input', () => {
    const key = searchInput.value.toLowerCase().trim();
    resultBox.innerHTML = '';

    if (key.length < 2) {
        resultBox.classList.add('hidden');
        return;
    }

    const filtered = contacts.filter(c =>
        c.name.toLowerCase().includes(key) ||
        (c.email && c.email.toLowerCase().includes(key)) ||
        (c.phone && c.phone.includes(key))
    );

    if (!filtered.length) {
        resultBox.classList.add('hidden');
        return;
    }

    filtered.forEach(c => {
        const div = document.createElement('div');
        div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer border-b';
        div.innerHTML = `
            <div class="font-medium">${c.name}</div>
            <div class="text-xs text-gray-500">
                ${c.email ?? '-'} | ${c.phone ?? '-'}
            </div>
        `;

        div.onclick = () => {
            searchInput.value = c.name;
            contactId.value = c.id;
            emailInput.value = c.email ?? '';
            phoneInput.value = c.phone ?? '';
            genderInput.value = (c.gender ?? '').toUpperCase();
            resultBox.classList.add('hidden');
        };

        resultBox.appendChild(div);
    });

    resultBox.classList.remove('hidden');
});
</script>

@endsection
