@extends('admin.components.app')

@section('title', 'Berita & Kegiatan')

@section('content')
<!-- HEADER -->
<div class="mb-6">

    <!-- BARIS ATAS: TITLE (KIRI) + BREADCRUMB (KANAN) -->
    <div class="flex items-center justify-between">

        <!-- TITLE -->
        <h1 class="text-2xl font-bold text-green-700">
            Berita & Kegiatan
        </h1>

        <!-- BREADCRUMB -->
        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}"
               class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">
                Berita & Kegiatan
            </span>
        </nav>

    </div>

    <!-- BUTTON DI BAWAH (KIRI) -->
    <div class="mt-3">
        <button onclick="openModal()"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            + Tambah
        </button>
    </div>

</div>



@if (session('success'))
  <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
    {{ session('success') }}
  </div>
@endif

<!-- Tabel Data -->
<div class="bg-white shadow-lg rounded-xl p-4">

    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-50 text-gray-600 text-sm">
                <th class="py-3 px-4 font-semibold">Gambar</th>
                <th class="py-3 px-4 font-semibold">Judul</th>
                <th class="py-3 px-4 font-semibold">Kategori</th>
                <th class="py-3 px-4 font-semibold">Tanggal</th>
                <th class="py-3 px-4 font-semibold">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
            <tr class="border-b hover:bg-gray-50 transition">

                <!-- GAMBAR -->
                <td class="py-4 px-4">
                    <img
                        src="{{ $item->foto ? asset('storage/'.$item->foto) : 'https://via.placeholder.com/80' }}"
                        alt=""
                        class="w-14 h-14 rounded-md object-cover shadow-sm"
                    >
                </td>

                <!-- JUDUL -->
                <td class="py-4 px-4">
                    <span class="font-medium text-gray-800">
                        {{ $item->judul }}
                    </span>
                </td>

                <!-- KATEGORI -->
                <td class="py-4 px-4">
                    <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                        {{ $item->kategori }}
                    </span>
                </td>

                <!-- TANGGAL -->
                <td class="py-4 px-4 text-gray-700">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                </td>

                <!-- AKSI -->
                <td class="py-6 px-4 flex gap-3 items-center">

                    <button
                        data-item='@json($item)'
                        onclick="openEdit(this)"
                        class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
                        Edit
                    </button>

                    <form action="{{ route('admin.beritadankegiatan.destroy', $item->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button
                            class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
                            Hapus
                        </button>
                    </form>

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-6 text-center text-gray-500">
                    Belum ada data.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
<!-- Modal Form Tambah/Edit -->
<div id="dataModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
  <div class="bg-white w-full max-w-lg rounded-lg shadow-xl p-6 relative border border-gray-200">
    <h2 id="modalTitle" class="text-xl font-bold text-green-700 mb-4">Tambah Data</h2>

    <form id="dataForm" action="{{ route('admin.beritadankegiatan.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="dataId">

      <!-- Judul -->
      <div class="mb-4">
        <label for="judul" class="block font-medium mb-1 text-gray-700">Judul</label>
        <input type="text" name="judul" id="judul"
          class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
          required>
      </div>

      <!-- Nama Masjid -->
      <div class="mb-4">
        <label for="namamasjid" class="block font-medium mb-1 text-gray-700">Nama Masjid</label>
        <input type="text" name="namamasjid" id="namamasjid"
          class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
          required>
      </div>

      <!-- Tanggal -->
      <div class="mb-4">
        <label for="tanggal" class="block font-medium mb-1 text-gray-700">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal"
          class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
          required>
      </div>

      <!-- Kategori (Dropdown) -->
      <div class="mb-4">
        <label for="kategori" class="block font-medium mb-1 text-gray-700">Kategori</label>
        <select name="kategori" id="kategori"
          class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
          required>
          <option value="" disabled selected>Pilih Kategori</option>
          <option value="Berita">Berita</option>
          <option value="Kegiatan">Kegiatan</option>
        </select>
      </div>

      <!-- Deskripsi -->
      <div class="mb-4">
        <label for="deskripsi" class="block font-medium mb-1 text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4"
          class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
          required></textarea>
      </div>

      <!-- Foto -->
      <div class="mb-4">
        <label for="foto" class="block font-medium mb-1 text-gray-700">Foto</label>
        <input type="file" name="foto" id="foto" accept="image/png,image/jpeg"
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">

        @error('foto')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Tombol Aksi -->
      <div class="flex justify-end mt-6">
        <button type="button" onclick="closeModal()"
          class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition mr-2">
          Batal
        </button>
        <button type="submit"
          class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Script Modal -->
<script>
  const dataModal = document.getElementById('dataModal');
  const dataForm = document.getElementById('dataForm');

  function openModal() {
    document.getElementById('modalTitle').innerText = 'Tambah Data';
    dataForm.action = "{{ route('admin.beritadankegiatan.store') }}";
    removeMethodInput();

    document.getElementById('dataId').value = '';
    document.getElementById('judul').value = '';
    document.getElementById('namamasjid').value = '';
    document.getElementById('tanggal').value = '';
    document.getElementById('kategori').value = '';
    document.getElementById('deskripsi').value = '';

    const fotoInput = document.getElementById('foto');
    if (fotoInput) fotoInput.value = '';

    dataModal.classList.remove('hidden');
    dataModal.classList.add('flex');
  }

  function closeModal() {
    dataModal.classList.add('hidden');
    dataModal.classList.remove('flex');
  }

  function openEdit(btn) {
    const raw = btn.getAttribute('data-item');
    let item;
    try {
      item = JSON.parse(raw);
    } catch (e) {
      console.error('Gagal parse data-item', e);
      alert('Data tidak valid.');
      return;
    }

    document.getElementById('modalTitle').innerText = 'Edit Data';
    dataForm.action = `/admin/beritadankegiatan/${item.id}`;
    addMethodInput('PUT');

    document.getElementById('dataId').value = item.id ?? '';
    document.getElementById('judul').value = item.judul ?? '';
    document.getElementById('namamasjid').value = item.namamasjid ?? '';
    document.getElementById('tanggal').value = item.tanggal ?? '';
    document.getElementById('kategori').value = item.kategori ?? '';
    document.getElementById('deskripsi').value = item.deskripsi ?? '';

    const fotoInput = document.getElementById('foto');
    if (fotoInput) fotoInput.value = '';

    dataModal.classList.remove('hidden');
    dataModal.classList.add('flex');
  }

  function addMethodInput(method = 'PUT') {
    removeMethodInput();
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = method;
    methodInput.id = '__method_hidden';
    dataForm.appendChild(methodInput);
  }

  function removeMethodInput() {
    const existing = document.getElementById('__method_hidden');
    if (existing) existing.remove();
  }
</script>
@endsection
