{{-- admin/program/form.blade.php --}}
@extends('admin.components.app')

@section('title',
    $mode === 'create' ? 'Buat Program' :
    ($mode === 'edit' ? 'Edit Program' : 'Lihat Program')
)

@section('content')
@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        class="mb-4 px-4 py-3 rounded-lg bg-green-500 text-white shadow"
    >
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        class="mb-4 px-4 py-3 rounded-lg bg-red-500 text-white shadow"
    >
        {{ session('error') }}
    </div>
@endif


<!-- TinyMCE -->
<script
    src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/8/tinymce.min.js"
    referrerpolicy="origin">
</script>

<!-- Alpine.js -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div
    class="max-w-full px-6 py-6"
    x-data="{ ...donasiWizard(), ...programForm() }"
    x-init="init(); initProgram('{{ $mode }}');"
>

    {{-- =============================== --}}
    {{-- MODE: SHOW → HANYA PREVIEW --}}
    {{-- =============================== --}}
    @if ($mode === 'show')
        <input type="hidden" id="programDataShow" value='@json($program)'>

        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-center">Preview Program</h2>
            @include('admin.program.preview.program')
        </div>

    @else

    {{-- =============================== --}}
    {{-- MODE CREATE / EDIT --}}
    {{-- =============================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: FORM --}}
        <div class="bg-white rounded-2xl shadow p-6">

            {{-- DATA PROGRAM UNTUK EDIT --}}
            @if ($mode === 'edit')
                <input type="hidden" id="programDataEdit" value='@json($program)'>
            @endif

            {{-- =========================== --}}
            {{-- CREATE MODE --}}
            {{-- =========================== --}}
            @if ($mode === 'create')
            <form id="programCreateForm"
            action="{{ route('admin.program.store') }}"
            method="POST"
            enctype="multipart/form-data"
            @submit.prevent="
                if(validateTargetDate()) $el.submit()
            ">
                @csrf

                {{-- STEP FORM --}}
                @include('admin.program.form.step1')

                <button
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
                    Simpan Program
                </button>

            </form>
            @endif


            {{-- =========================== --}}
            {{-- EDIT MODE --}}
            {{-- =========================== --}}
            @if ($mode === 'edit')
            <form id="programEditForm"
            action="{{ route('admin.program.update', $program->id) }}"
            method="POST"
            enctype="multipart/form-data"
            @submit.prevent="
                if(validateTargetDate()) $el.submit()
            ">
                @csrf
                @method('PUT')

                {{-- STEP FORM --}}
                @include('admin.program.form.step1')

                <button
                    class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg"
                >
                    Update Program
                </button>

            </form>
            @endif

        </div>


        {{-- RIGHT: PREVIEW --}}
        <div class="bg-white rounded-2xl shadow p-4">
            <h2 class="text-xl font-bold mb-3">Preview Program</h2>
            @include('admin.program.preview.program')
        </div>

    </div>
    @endif

</div>

{{-- =============================== --}}
{{-- LOAD JAVASCRIPT --}}
{{-- =============================== --}}
@include('admin.program.js.tinymce')
@include('admin.program.js.donasiWizard')
@include('admin.program.js.programForm')

@endsection
