{{-- admin/program/form/step1 --}}

<h2 class="text-xl font-bold mb-4">Informasi Produk</h2>

<!-- Judul -->
<div class="mb-4">
    <label for="judul" class="block text-sm font-semibold mb-1">Judul</label>
    <input id="judul" x-model="form.nama_produk" name="judul"
           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<!-- Kategori -->
<div class="mb-4">
    <label for="kategori" class="block text-sm font-semibold mb-1">Kategori</label>
    <select id="kategori" name="kategori" x-model="form.kategori"
        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Pilih kategori</option>
        @foreach($kategori as $k)
            <option value="{{ $k }}">{{ $k }}</option>
        @endforeach
    </select>
</div>

<!-- Minimal Donasi -->
<div class="mb-4">
    <label for="min_donasi" class="block text-sm font-semibold mb-1">Minimal Donasi</label>
    <input id="min_donasi" name="min_donasi" type="number" x-model="form.min_donasi"
           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<!-- Custom Nominal -->
<fieldset class="mb-4">
    <legend class="text-sm font-semibold mb-1">Custom Nominal</legend>

    <div class="grid grid-cols-2 gap-2 mt-2">
        <template x-for="(n,i) in form.custom_nominal" :key="i">
            <input
                :id="'custom_nominal_' + i"
                type="number"
                :name="'custom_nominal['+i+']'"
                x-model="form.custom_nominal[i]"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </template>
    </div>
</fieldset>

<!-- Target Dana -->
<div class="mb-4">
    <label for="target_dana" class="block text-sm font-semibold mb-1">Target Dana</label>
    <input id="target_dana"
           type="number"
           name="target_dana"
           x-model="form.target_dana"
           @input="form.target_dana = Number($event.target.value)"
           placeholder="Contoh: 50000000"
           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<!-- Target Waktu (Preline UI Datepicker) -->
<div class="mb-4">
    <label for="target_date" class="block text-sm font-semibold mb-1">Target Waktu</label>

    <div class="relative">
        <input
            id="target_date"
            name="target_waktu"
            type="date"
            x-model="form.target_date"
            :disabled="form.open_goals"
            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                   transition disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"/>
    </div>
</div>


<!-- Toggle Open Goals -->
<div class="mb-4">
    <label class="flex items-center justify-between cursor-pointer">

        <button
            type="button"
            @click="form.open_goals = !form.open_goals"
            :class="form.open_goals ? 'bg-blue-600' : 'bg-gray-300'"
            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none"
        >
            <span
                :class="form.open_goals ? 'translate-x-6' : 'translate-x-1'"
                class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300"
            ></span>
        </button>

        <input type="hidden" name="open_goals" :value="form.open_goals ? 1 : 0">

        <span class="text-sm font-semibold">Open Goals (tanpa target waktu)</span>
    </label>
</div>

<!-- Gambar -->
<div class="mb-4">
    <label for="foto" class="block text-sm font-semibold mb-1">Gambar Program</label>
    <input id="foto"
           name="foto"
           type="file"
           @change="previewImage"
           accept="image/jpeg,image/png"
           class="w-full border px-3 py-2 rounded-lg cursor-pointer bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<!-- Deskripsi -->
<div class="mb-4">
    <label for="produk_deskripsi" class="block text-sm font-semibold mb-1">Deskripsi Produk</label>
    <textarea id="produk_deskripsi"
              name="deskripsi"
              class="w-full border rounded-lg px-3 py-2 h-28 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
</div>
