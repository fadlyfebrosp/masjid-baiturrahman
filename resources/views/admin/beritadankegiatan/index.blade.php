@extends('admin.components.app')

@section('title', 'Berita & Kegiatan')

@section('content')

<!-- HEADER -->
<div class="mb-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h1 class="text-xl md:text-2xl font-bold text-green-700">
            Berita & Kegiatan
        </h1>

        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">
                Berita & Kegiatan
            </span>
        </nav>
    </div>

    <div class="mt-4">
        <button onclick="openModal()"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            + Tambah
        </button>
    </div>
</div>

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    confirmButtonColor: '#16a34a'
});
</script>
@endif

<!-- TABLE -->
<div class="hidden md:block bg-white shadow rounded-xl p-4">
<table class="w-full">
<thead>
<tr class="border-b bg-gray-50 text-sm text-gray-600">
    <th class="p-3">Gambar</th>
    <th class="p-3">Judul</th>
    <th class="p-3">Kategori</th>
    <th class="p-3">Tanggal</th>
    <th class="p-3">Aksi</th>
</tr>
</thead>

<tbody>
@foreach($data as $item)
<tr class="border-b hover:bg-gray-50">
    <td class="p-3">
        <img
            src="{{ $item->fotos->first()
                ? asset('storage/'.$item->fotos->first()->path)
                : 'https://via.placeholder.com/80' }}"
            class="w-14 h-14 rounded object-cover">
    </td>

    <td class="p-3 font-medium">{{ $item->judul }}</td>

    <td class="p-3">
        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">
            {{ $item->kategori }}
        </span>
    </td>

    <td class="p-3">
        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
    </td>

    <td class="p-3">
        <div class="flex gap-2">
            <button
                data-item='@json($item)'
                onclick="openEdit(this)"
                class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                Edit
            </button>

            <form action="{{ route('admin.beritadankegiatan.destroy', $item->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin hapus data?')">
                @csrf
                @method('DELETE')
                <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Hapus
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- MODAL -->
<div id="dataModal"
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

<div class="bg-white w-full md:max-w-xl rounded-lg flex flex-col max-h-[90vh]">

<!-- HEADER -->
<div class="p-6 border-b">
    <h2 id="modalTitle" class="text-xl font-bold text-green-700">
        Tambah Data
    </h2>
</div>

<!-- BODY -->
<div class="p-6 overflow-y-auto">
<form id="dataForm" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-4">
    <label>Judul</label>
    <input type="text" name="judul" id="judul" class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label>Nama Masjid</label>
    <input type="text" name="namamasjid" id="namamasjid" class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label>Tanggal</label>
    <input type="date" name="tanggal" id="tanggal" class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label>Kategori</label>
    <select name="kategori" id="kategori" class="w-full border rounded p-2">
        <option value="Berita">Berita</option>
        <option value="Kegiatan">Kegiatan</option>
    </select>
</div>

<div class="mb-4">
    <label>Deskripsi</label>
    <textarea name="deskripsi" id="deskripsi" rows="4"
        class="w-full border rounded p-2"></textarea>
</div>

<div class="mb-4">
    <label>Foto (multi)</label>
    <input type="file" name="foto[]" multiple class="w-full border rounded p-2">
</div>

<!-- EXISTING FOTO -->
<div id="existingPhotos" class="grid grid-cols-3 gap-3 mb-6"></div>

<div class="flex justify-end gap-2">
    <button type="button" onclick="closeModal()" class="bg-gray-400 text-white px-4 py-2 rounded">
        Batal
    </button>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
        Simpan
    </button>
</div>

</form>
</div>
</div>
</div>

<!-- SCRIPT -->
<script>
const modal = document.getElementById('dataModal');
const form  = document.getElementById('dataForm');
const photosBox = document.getElementById('existingPhotos');

function openModal() {
    form.action = "{{ route('admin.beritadankegiatan.store') }}";
    removeMethod();
    form.reset();
    photosBox.innerHTML = '';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openEdit(btn) {
    const item = JSON.parse(btn.dataset.item);

    document.getElementById('modalTitle').innerText = 'Edit Data';
    form.action = `/admin/beritadankegiatan/${item.id}`;
    addMethod('PUT');

    judul.value = item.judul;
    namamasjid.value = item.namamasjid;
    tanggal.value = item.tanggal;
    kategori.value = item.kategori;
    deskripsi.value = item.deskripsi;

    photosBox.innerHTML = '';
    item.fotos.forEach(foto => {
        photosBox.innerHTML += `
            <div class="relative" id="foto-${foto.id}">
                <img src="/storage/${foto.path}" class="w-full h-24 rounded object-cover">
                <button type="button"
                    onclick="deletePhoto(${foto.id})"
                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 text-xs">
                    ✕
                </button>
            </div>
        `;
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function deletePhoto(id) {
    Swal.fire({
        title: 'Hapus foto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/admin/beritadankegiatan/foto/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            document.getElementById(`foto-${id}`).remove();
        });
    });
}

function addMethod(method) {
    removeMethod();
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_method';
    input.value = method;
    input.id = '__method';
    form.appendChild(input);
}

function removeMethod() {
    const m = document.getElementById('__method');
    if (m) m.remove();
}
</script>

@endsection
