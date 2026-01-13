@extends('finance.components.app')

@section('title', 'Alokasi Donasi')

@section('content')

<!-- ================= HEADER ================= -->
<div class="mb-6 space-y-4">

  <!-- JUDUL + BREADCRUMB -->
  <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold text-green-700">Alokasi Donasi</h1>

    <div class="text-sm text-gray-600 flex items-center space-x-2">
        <a href="{{ route('finance.dashboard') }}"
        class="hover:underline text-gray-600">
            Home
        </a>

        <i class="bi bi-chevron-right text-xs"></i>

        <span class="text-gray-500">
            Master Data
        </span>

        <i class="bi bi-chevron-right text-xs"></i>

        <span class="font-semibold text-gray-800">
            Alokasi Donasi
        </span>
    </div>
  </div>

  <!-- ACTION BAR -->
  <div class="flex flex-wrap justify-between items-end gap-4">

    <!-- LEFT : BUTTON TAMBAH -->
    <button
      onclick="openCreateModal()"
      class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg shadow">
      + Tambah Alokasi
    </button>

    <!-- RIGHT : FILTER -->
    <div class="bg-white rounded-xl shadow p-4">
      <form method="GET" class="flex flex-wrap items-end gap-4">

        <div>
          <label class="text-sm text-gray-600">Dari</label>
          <input type="date" name="start_date"
                 value="{{ request('start_date') }}"
                 class="border rounded px-3 py-2">
        </div>

        <div>
          <label class="text-sm text-gray-600">Sampai</label>
          <input type="date" name="end_date"
                 value="{{ request('end_date') }}"
                 class="border rounded px-3 py-2">
        </div>

        <div class="flex gap-2">
          <button class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded">
            Filter
          </button>

          <a href="{{ route('finance.alokasidonasi.index') }}"
             class="px-4 py-2 border rounded hover:bg-gray-50">
            Reset
          </a>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- ================= ALERT ================= -->
@if(session('success'))
<div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
  {{ session('success') }}
</div>
@endif

<!-- ================= TABLE ================= -->
<div class="bg-white rounded-xl shadow overflow-hidden">
  <table class="min-w-full text-sm">
    <thead class="bg-green-700 text-white">
      <tr>
        <th class="px-4 py-3">No</th>
        <th class="px-4 py-3">Tanggal</th>
        <th class="px-4 py-3">Program</th>
        <th class="px-4 py-3">Kegiatan</th>
        <th class="px-4 py-3 text-right">Jumlah</th>
        <th class="px-4 py-3">Keterangan</th>
        <th class="px-4 py-3 text-center">Aksi</th>
      </tr>
    </thead>

    <tbody class="divide-y">
      @forelse($alokasi as $item)
      <tr>
        <td class="px-4 py-3">{{ $loop->iteration }}</td>
        <td class="px-4 py-3">{{ $item->tanggal }}</td>
        <td class="px-4 py-3">{{ $item->program->judul }}</td>
        <td class="px-4 py-3">{{ $item->nama_kegiatan }}</td>
        <td class="px-4 py-3 text-right font-semibold">
          Rp {{ number_format($item->jumlah, 0, ',', '.') }}
        </td>
        <td class="px-4 py-3">{{ $item->keterangan ?? '-' }}</td>
        <td class="px-4 py-3 text-center space-x-2">
          <button onclick='openEditModal(@json($item))'
                  class="text-blue-600 hover:underline">
            Edit
          </button>

          <form action="{{ route('finance.alokasidonasi.destroy', $item->id) }}"
                method="POST"
                class="inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Hapus alokasi ini?')"
                    class="text-red-600 hover:underline">
              Hapus
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-6 text-gray-500">
          Belum ada data alokasi
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- ================= MODAL ================= -->
<div id="modal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
  <div class="bg-white w-full max-w-md rounded-xl p-6">

    <h2 id="modalTitle" class="text-xl font-bold mb-4">title</h2>

    <form id="form"
          method="POST"
          data-store="{{ route('finance.alokasidonasi.store') }}"
          data-update="{{ url('finance/alokasidonasi') }}">
      @csrf
      <input type="hidden" name="_method" id="method">

      <div class="mb-3">
        <label class="text-sm">Program</label>
        <select name="program_id" class="w-full border rounded p-2" required>
          @foreach($programs as $p)
            <option value="{{ $p->id }}">{{ $p->judul }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="text-sm">Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan"
               class="w-full border rounded p-2" required>
      </div>

      <div class="mb-3">
        <label class="text-sm">Tanggal</label>
        <input type="date" name="tanggal"
               class="w-full border rounded p-2" required>
      </div>

      <div class="mb-3">
        <label class="text-sm">Jumlah</label>
        <input type="text"
               id="jumlah_display"
               class="w-full border rounded p-2"
               oninput="formatRupiah(this)" required>
        <input type="hidden" name="jumlah" id="jumlah">
      </div>

      <div class="mb-3">
        <label class="text-sm">Keterangan</label>
        <textarea name="keterangan"
                  class="w-full border rounded p-2"></textarea>
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal()">Batal</button>
        <button class="bg-green-700 text-white px-4 py-2 rounded">
          Simpan
        </button>
      </div>
    </form>

  </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
function openCreateModal() {
  const form = document.getElementById('form');
  document.getElementById('modalTitle').innerText = 'Tambah Alokasi Donasi';
  form.action = form.dataset.store;
  document.getElementById('method').value = '';
  form.reset();
  document.getElementById('modal').classList.remove('hidden');
}

function openEditModal(data) {
  const form = document.getElementById('form');
  document.getElementById('modalTitle').innerText = 'Edit Alokasi Donasi';
  form.action = form.dataset.update + '/' + data.id;
  document.getElementById('method').value = 'PUT';

  form.program_id.value = data.program_id;
  form.nama_kegiatan.value = data.nama_kegiatan;
  form.tanggal.value = data.tanggal;

  document.getElementById('jumlah_display').value =
    data.jumlah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  document.getElementById('jumlah').value = data.jumlah;

  form.keterangan.value = data.keterangan ?? '';

  document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
  document.getElementById('modal').classList.add('hidden');
}

function formatRupiah(input) {
  let value = input.value.replace(/\D/g, '');
  document.getElementById('jumlah').value = value;
  input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>

@endsection
