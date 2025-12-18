@extends('admin.components.app')

@section('title', 'Edit Program')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">Edit Program</h1>

    <form action="{{ route('admin.program.update', $program->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- KATEGORI -->
        <div class="mb-4">
            <label for="kategori" class="font-semibold text-sm">Kategori</label>
            <select name="kategori" class="input">
                @foreach($kategori as $k)
                    <option value="{{ $k }}" {{ $program->kategori == $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- JUDUL -->
        <div class="mb-4">
            <label for="judul" class="font-semibold text-sm">Judul Program</label>
            <input type="text" name="judul" class="input" value="{{ $program->judul }}">
        </div>

        <!-- FOTO -->
        <div class="mb-4">
            <label for="foto" class="font-semibold text-sm block mb-1">Foto</label>

            <img alt="img" src="{{ $program->foto_url ?? 'https://via.placeholder.com/200' }}"
                 class="w-40 h-40 rounded object-cover mb-3 border">

            <input type="file" name="foto" class="input">
        </div>

        <!-- TARGET DANA -->
        <div class="mb-4">
            <label for="target_dana" class="font-semibold text-sm">Target Dana</label>
            <input type="number" name="target_dana" class="input" value="{{ $program->target_dana }}">
        </div>

        <!-- MIN DONASI -->
        <div class="mb-4">
            <label for="min_donasi" class="font-semibold text-sm">Min Donasi</label>
            <input type="number" name="min_donasi" class="input" value="{{ $program->min_donasi }}">
        </div>

        <!-- CUSTOM NOMINAL -->
        <div class="mb-4">
            <label for="custom" class="font-semibold text-sm block">Custom Nominal (opsional)</label>
            <input type="text" name="custom_nominal[]"
                   class="input mb-2"
                   value="{{ implode(',', $program->custom_nominal ?? []) }}">
            <small class="text-gray-500">Pisahkan dengan koma: contoh 10000,20000,50000</small>
        </div>

        <!-- OPEN GOALS -->
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="open_goals"
                       {{ $program->open_goals ? 'checked' : '' }}>
                Tanpa batas waktu (Open Goals)
            </label>
        </div>

        <!-- TARGET WAKTU -->
        <div class="mb-4">
            <label for="" class="font-semibold text-sm">Target Waktu</label>
            <input type="date"
                   name="target_waktu"
                   class="input"
                   value="{{ $program->target_waktu }}">
        </div>

        <!-- DESKRIPSI -->
        <div class="mb-4">
            <label for="" class="font-semibold text-sm">Deskripsi</label>
            <textarea name="deskripsi" rows="6" class="input">{{ $program->deskripsi }}</textarea>
        </div>

        <button class="px-4 py-2 bg-green-600 text-white rounded-lg">Update Program</button>
    </form>

</div>

@endsection
