{{-- fungsinya untuk nampilin data table --}}
@extends('admin.components.app')

@section('content')

<div
     x-data="{
        start: '{{ request('start_date') }}',
        end: '{{ request('end_date') }}',
     }">

    <!-- HEADER + BREADCRUMB -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-green-700">
            Program
        </h1>

        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}"
            class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">
                Program
            </span>
        </nav>
    </div>

    <!-- TOP BAR -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">

        <a href="{{ route('admin.program.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-800">
            + Tambah
        </a>

        <div class="flex items-center gap-3">

            <div>
                <button id="dateRangePickerButton"
                        data-dropdown-toggle="dateRangePicker"
                        class="px-3 py-2 w-64 border rounded-lg shadow text-left bg-white">
                    <span x-text="start ? (start + ' → ' + end) : 'Pilih Rentang Tanggal'"></span>
                </button>

                <!-- DROPDOWN DATE RANGE -->
                <div id="dateRangePicker"
                     class="z-50 hidden bg-white rounded-lg shadow w-72 p-4 mt-2">

                    <!-- QUICK SELECT -->
                    <div class="mb-4">
                        <h4 class="font-medium text-sm mb-2">Quick Select</h4>
                        <div class="grid grid-cols-2 gap-2">

                            <button class="px-2 py-2 bg-gray-100 rounded"
                                    @click="
                                        start = '{{ now()->toDateString() }}';
                                        end = '{{ now()->toDateString() }}';
                                    ">
                                Today
                            </button>

                            <button class="px-2 py-2 bg-gray-100 rounded"
                                    @click="
                                        start = '{{ now()->startOfWeek()->toDateString() }}';
                                        end = '{{ now()->endOfWeek()->toDateString() }}';
                                    ">
                                This Week
                            </button>

                            <button class="px-2 py-2 bg-gray-100 rounded"
                                    @click="
                                        start = '{{ now()->startOfMonth()->toDateString() }}';
                                        end = '{{ now()->endOfMonth()->toDateString() }}';
                                    ">
                                This Month
                            </button>

                            <button class="px-2 py-2 bg-gray-100 rounded"
                                    @click="
                                        start = '{{ now()->subMonth()->startOfMonth()->toDateString() }}';
                                        end = '{{ now()->subMonth()->endOfMonth()->toDateString() }}';
                                    ">
                                Last Month
                            </button>

                        </div>
                    </div>

                    <!-- CUSTOM RANGE -->
                    <div>
                        <h4 class="font-medium text-sm mb-2">Custom Range</h4>
                        <input type="date" x-model="start" class="border rounded px-2 py-1 w-full mb-2">
                        <input type="date" x-model="end" class="border rounded px-2 py-1 w-full">
                    </div>

                    <!-- APPLY BUTTON -->
                    <form method="GET" class="mt-3">
                        <input type="hidden" name="start_date" :value="start">
                        <input type="hidden" name="end_date" :value="end">

                        <button type="submit"
                                class="mt-3 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                            Apply
                        </button>
                    </form>

                </div>
            </div>

            <!-- 🔍 SEARCH -->
            <form>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Pencarian"
                       class="px-3 py-2 border rounded-lg shadow w-48">
            </form>

        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white shadow-lg rounded-xl p-4">

        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600 text-sm">
                    <th class="py-3 px-4 font-semibold">Gambar</th>
                    <th class="py-3 px-4 font-semibold">Judul</th>
                    <th class="py-3 px-4 font-semibold">Kategori</th>
                    <th class="py-3 px-4 font-semibold">Target Dana</th>
                    <th class="py-3 px-4 font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $p)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="py-4 px-4">
                        <img alt="" src="{{ $p->foto ? asset('storage/'.$p->foto) : 'https://via.placeholder.com/80' }}"
                             class="w-14 h-14 rounded-md object-cover shadow-sm">
                    </td>

                    <td class="py-4 px-4">
                        <a href="{{ route('admin.program.edit', $p->id) }}"
                           class="text-blue-600 font-medium hover:underline">
                            {{ $p->judul }}
                        </a>
                    </td>

                    <td class="py-4 px-4">
                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                            {{ $p->kategori }}
                        </span>
                    </td>

                    <td class="py-4 px-4 font-semibold text-gray-800">
                        Rp {{ number_format($p->target_dana, 0, ',', '.') }}
                    </td>

                    <!-- BUTTONS -->
                    <td class="py-6 px-4 flex gap-3 items-center">
                        <a href="{{ route('admin.program.edit', $p->id) }}"
                           class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
                           Edit
                        </a>

                        <a href="{{ route('admin.program.show', $p->id) }}"
                           class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
                           Show
                        </a>

                        <form action="{{ route('admin.program.destroy', $p->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus program ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
                                Hapus
                            </button>
                        </form>

                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $data->links() }}
        </div>

    </div>

</div>

@endsection
