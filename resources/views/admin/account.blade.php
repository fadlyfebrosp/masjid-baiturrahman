@extends('admin.components.app')

@section('title', 'Kelola Akun')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="mb-6">
    <div class="flex justify-between items-center">

        <!-- JUDUL -->
        <h1 class="text-3xl font-bold text-green-700">
            Kelola Akun
        </h1>

        <!-- BREADCRUMB -->
        <nav class="text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}"
               class="hover:text-green-700 hover:underline">
                Home
            </a>
            <span class="mx-1">›</span>
            <span class="font-semibold text-gray-800">
                Kelola Akun
            </span>
        </nav>

    </div>
</div>

@if(session('success'))
  <div id="flashMessage" class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
    {{ session('success') }}
  </div>
@endif

<div class="mb-4">
  <button onclick="openAddModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
    + Tambah Akun
  </button>
</div>

<div class="bg-white shadow-lg rounded-xl p-4 overflow-x-auto">

    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-50 text-gray-600 text-sm">
                <th class="py-3 px-4 font-semibold">Nama</th>
                <th class="py-3 px-4 font-semibold">Email</th>
                <th class="py-3 px-4 font-semibold">Telepon</th>
                <th class="py-3 px-4 font-semibold">Gender</th>
                <th class="py-3 px-4 font-semibold">Role</th>
                <th class="py-3 px-4 font-semibold text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($accounts as $acc)
            <tr class="border-b hover:bg-gray-50 transition">

                <!-- NAMA -->
                <td class="py-4 px-4 font-medium text-gray-800">
                    {{ $acc->name }}
                </td>

                <!-- EMAIL -->
                <td class="py-4 px-4 text-gray-700">
                    {{ $acc->email }}
                </td>

                <!-- TELEPON -->
                <td class="py-4 px-4 text-gray-700">
                    {{ $acc->phone ?? '-' }}
                </td>

                <!-- GENDER -->
                <td class="py-4 px-4 text-gray-700">
                    {{ $acc->gender ?? '-' }}
                </td>

                <!-- ROLE -->
                <td class="py-4 px-4">
                    <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                        {{ $acc->role ?? '-' }}
                    </span>
                </td>

                <!-- AKSI -->
                <td class="py-6 px-4">
                    <div class="flex justify-center gap-3">

                        <!-- EDIT -->
                        <button type="button"
                                class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700 edit-btn"
                                data-account='@json($acc)'>
                            Edit
                        </button>

                        <!-- SET ROLE -->
                        <button type="button"
                                onclick="openRoleModal({{ $acc->id }}, '{{ $acc->role }}')"
                                class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
                            Set Role
                        </button>

                        <!-- DELETE -->
                        <form action="{{ route('admin.account.destroy', $acc->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                            @csrf
                            @method('DELETE')
                            <button
                                class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm shadow hover:bg-green-700">
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



<!-- MODAL ADD / EDIT AKUN -->
<div id="accountModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
  <div class="bg-white w-full max-w-lg rounded-lg shadow-xl p-6 relative">

    <h2 id="modalTitle" class="text-xl font-bold mb-4 text-green-700">Form Akun</h2>

    <form id="accountForm" method="POST">
      @csrf
      <!-- hidden spoof method -->
      <input type="hidden" name="_method" id="formMethod" value="POST">

      <div class="mb-3">
        <label for="modal_name" class="font-semibold">Nama</label>
        <input type="text" name="name" id="modal_name" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label for="modal_email" class="font-semibold">Email</label>
        <input type="email" name="email" id="modal_email" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label for="modal_phone" class="font-semibold">Telepon</label>
        <input type="text" name="phone" id="modal_phone" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label for="modal_gender" class="font-semibold">Gender</label>
        <select name="gender" id="modal_gender" class="w-full border rounded-lg p-2">
          <option value="">Pilih Gender</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="modal_password" class="font-semibold">Password</label>
        <input type="password" name="password" id="modal_password" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label for="modal_password_confirmation" class="font-semibold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="modal_password_confirmation" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label for="modal_role" class="font-semibold">Role</label>
        <select name="role" id="modal_role" class="w-full border rounded-lg p-2" required>
          <option value="jamaah">Jamaah</option>
          <option value="admin">Admin</option>
          <option value="finance">Finance</option>
        </select>
      </div>

      <div class="flex justify-end gap-3 mt-4">
        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
        <button type="submit" id="accountFormSubmit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
      </div>

    </form>
  </div>
</div>


<!-- MODAL SET ROLE -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
  <div class="bg-white w-full max-w-sm rounded-lg p-6 shadow-xl">

    <h2 class="text-xl font-bold text-green-700 mb-4">Set Role</h2>

    <input type="hidden" id="role_user_id">

    <label for="role_select_modal" class="sr-only">Pilih Role</label>
    <select id="role_select_modal" class="w-full border rounded-lg p-2 mb-4">
      <option value="jamaah">Jamaah</option>
      <option value="admin">Admin</option>
      <option value="finance">Finance</option>
    </select>

    <div class="flex justify-end gap-3">
      <button type="button" onclick="closeRoleModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
      <button type="button" onclick="saveRole()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
    </div>

  </div>
</div>


<script>
const accountModal = document.getElementById('accountModal');
const roleModal = document.getElementById('roleModal');
const accountForm = document.getElementById('accountForm');
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Utility: ensure hidden _method input exists and set its value
function ensureMethodInput(value = 'POST') {
  let methodInput = accountForm.querySelector('input[name="_method"]');
  if (!methodInput) {
    methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    accountForm.prepend(methodInput);
  }
  methodInput.value = value;
  return methodInput;
}

function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Tambah Akun Baru';
    accountForm.action = "{{ route('admin.account.store') }}";
    ensureMethodInput('POST');
    accountForm.reset();
    accountModal.classList.replace('hidden', 'flex');
}

// openEditModal now expects a JS object (account)
function openEditModal(acc) {
    document.getElementById('modalTitle').innerText = 'Edit Akun';

    // set action to update route (PUT)
    accountForm.action = "/admin/account/" + acc.id;
    ensureMethodInput('PUT');

    document.getElementById('modal_name').value = acc.name ?? '';
    document.getElementById('modal_email').value = acc.email ?? '';
    document.getElementById('modal_phone').value = acc.phone ?? '';
    document.getElementById('modal_gender').value = acc.gender ?? '';
    document.getElementById('modal_role').value = acc.role ?? 'jamaah';
    document.getElementById('modal_password').value = '';
    document.getElementById('modal_password_confirmation').value = '';

    accountModal.classList.replace('hidden', 'flex');
}

function closeModal() {
    accountModal.classList.replace('flex', 'hidden');
}

/* ========== ROLE MODAL ========== */
function openRoleModal(id, role) {
  document.getElementById('role_user_id').value = id;
  document.getElementById('role_select_modal').value = role ?? 'jamaah';
  roleModal.classList.replace('hidden', 'flex');
}

function closeRoleModal() {
  roleModal.classList.replace('flex', 'hidden');
}
async function saveRole() {
    const id = document.getElementById('role_user_id').value;
    const role = document.getElementById('role_select_modal').value;

    try {
        const res = await fetch(`/admin/account/${id}/role`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ role })
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({ message: 'Gagal' }));

            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: err.message || "Gagal mengubah role"
            });

            return;
        }

        const data = await res.json();

        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: data.message || "Role berhasil diubah",
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            closeRoleModal();
            location.reload();
        });

    } catch (e) {
        console.error(e);

        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Terjadi kesalahan jaringan"
        });
    }
}


/* ========== Attach click listeners for Edit buttons (safe JSON parsing) ========== */
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      const raw = btn.getAttribute('data-account');
      let acc = null;
      try {
        acc = JSON.parse(raw);
      } catch (err) {
        console.error('Failed parse account JSON', err, raw);
        alert('Terjadi kesalahan saat mengambil data akun.');
        return;
      }
      openEditModal(acc);
    });
  });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
