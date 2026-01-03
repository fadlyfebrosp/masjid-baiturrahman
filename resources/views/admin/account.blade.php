@extends('admin.components.app')

@section('title', 'Kelola Akun')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ================= HEADER ================= -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">

        <h1 class="text-2xl md:text-3xl font-bold text-green-700">
            Kelola Akun
        </h1>

        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">Kelola Akun</span>
        </nav>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
    {{ session('success') }}
</div>
@endif

<!-- ================= ADD BUTTON ================= -->
<div class="mb-4">
    <button onclick="openAddModal()"
        class="w-full md:w-auto bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        + Tambah Akun
    </button>
</div>

<!-- ================= DESKTOP TABLE ================= -->
<div class="hidden md:block bg-white shadow-lg rounded-xl p-4 overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b bg-gray-50 text-gray-600">
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Telepon</th>
                <th class="py-3 px-4">Gender</th>
                <th class="py-3 px-4">Role</th>
                <th class="py-3 px-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $acc)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-4 px-4 font-medium">{{ $acc->name }}</td>
                <td class="py-4 px-4">{{ $acc->email }}</td>
                <td class="py-4 px-4">{{ $acc->phone ?? '-' }}</td>
                <td class="py-4 px-4">{{ $acc->gender ?? '-' }}</td>
                <td class="py-4 px-4">
                    <x-role-badge :role="$acc->role" />
                </td>
                <td class="py-4 px-4">
                    <div class="flex justify-center gap-2">
                        <button
                            class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm edit-btn"
                            data-account='@json($acc)'>
                            Edit
                        </button>

                        <button
                            onclick="openRoleModal({{ $acc->id }}, '{{ $acc->role }}')"
                            class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm">
                            Set Role
                        </button>

                        <form method="POST"
                              action="{{ route('admin.account.destroy', $acc->id) }}"
                              onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-6 text-center text-gray-500">
                    Belum ada data akun.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ================= MOBILE CARD ================= -->
<div class="md:hidden space-y-4">
    @forelse($accounts as $acc)
    <div class="bg-white shadow rounded-xl p-4">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="font-semibold text-gray-800">{{ $acc->name }}</p>
                <p class="text-sm text-gray-500">{{ $acc->email }}</p>
            </div>
            <x-role-badge :role="$acc->role" />
        </div>

        <div class="text-sm text-gray-700 space-y-1 mb-3">
            <p><span class="text-gray-500">Telepon:</span> {{ $acc->phone ?? '-' }}</p>
            <p><span class="text-gray-500">Gender:</span> {{ $acc->gender ?? '-' }}</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button
                class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg text-sm edit-btn"
                data-account='@json($acc)'>
                Edit
            </button>

            <button
                onclick="openRoleModal({{ $acc->id }}, '{{ $acc->role }}')"
                class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg text-sm">
                Set Role
            </button>

            <form method="POST"
                  action="{{ route('admin.account.destroy', $acc->id) }}"
                  onsubmit="return confirm('Yakin ingin menghapus akun ini?')"
                  class="w-full">
                @csrf
                @method('DELETE')
                <button class="w-full px-3 py-2 bg-red-600 text-white rounded-lg text-sm">
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white shadow rounded-xl p-6 text-center text-gray-500">
        Belum ada data akun.
    </div>
    @endforelse
</div>

<!-- ================= MODAL ADD / EDIT ================= -->
<div id="accountModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-xl p-6">
        <h2 id="modalTitle" class="text-xl font-bold text-green-700 mb-4">Form Akun</h2>

        <form id="accountForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod">

            <div class="mb-3">
                <label class="font-semibold">Nama</label>
                <input type="text" name="name" id="modal_name" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="font-semibold">Email</label>
                <input type="email" name="email" id="modal_email" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="font-semibold">Telepon</label>
                <input type="text" name="phone" id="modal_phone" class="w-full border rounded-lg p-2">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Gender</label>
                <select name="gender" id="modal_gender" class="w-full border rounded-lg p-2">
                    <option value="">Pilih Gender</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="font-semibold">Password</label>
                <input type="password" name="password" id="modal_password" class="w-full border rounded-lg p-2">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="modal_password_confirmation" class="w-full border rounded-lg p-2">
            </div>

            <div class="mb-3">
                <label class="font-semibold">Role</label>
                <select name="role" id="modal_role" class="w-full border rounded-lg p-2" required>
                    <option value="jamaah">Jamaah</option>
                    <option value="admin">Admin</option>
                    <option value="finance">Finance</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL SET ROLE ================= -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-sm rounded-lg p-6 shadow-xl">
        <h2 class="text-xl font-bold text-green-700 mb-4">Set Role</h2>

        <input type="hidden" id="role_user_id">

        <select id="role_select_modal" class="w-full border rounded-lg p-2 mb-4">
            <option value="jamaah">Jamaah</option>
            <option value="admin">Admin</option>
            <option value="finance">Finance</option>
        </select>

        <div class="flex justify-end gap-3">
            <button onclick="closeRoleModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
            <button onclick="saveRole()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
        </div>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const accountModal = document.getElementById('accountModal');
const roleModal = document.getElementById('roleModal');
const accountForm = document.getElementById('accountForm');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function ensureMethod(method) {
    document.getElementById('formMethod').value = method;
}

function openAddModal() {
    accountForm.reset();
    accountForm.action = "{{ route('admin.account.store') }}";
    ensureMethod('POST');
    accountModal.classList.replace('hidden', 'flex');
}

function openEditModal(acc) {
    accountForm.action = `/admin/account/${acc.id}`;
    ensureMethod('PUT');

    modal_name.value = acc.name ?? '';
    modal_email.value = acc.email ?? '';
    modal_phone.value = acc.phone ?? '';
    modal_gender.value = acc.gender ?? '';
    modal_role.value = acc.role ?? 'jamaah';

    accountModal.classList.replace('hidden', 'flex');
}

function closeModal() {
    accountModal.classList.replace('flex', 'hidden');
}

function openRoleModal(id, role) {
    role_user_id.value = id;
    role_select_modal.value = role;
    roleModal.classList.replace('hidden', 'flex');
}

function closeRoleModal() {
    roleModal.classList.replace('flex', 'hidden');
}

async function saveRole() {
    const id = role_user_id.value;
    const role = role_select_modal.value;

    const res = await fetch(`/admin/account/${id}/role`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ role })
    });

    if (res.ok) location.reload();
}

document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        openEditModal(JSON.parse(btn.dataset.account));
    });
});
</script>
@endsection
