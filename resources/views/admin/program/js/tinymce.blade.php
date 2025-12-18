{{-- resources/views/admin/program/js/tinymce.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: '#produk_deskripsi',
        height: 300,
        plugins: 'image media link code lists table',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image media | code',

        automatic_uploads: true,
        images_upload_url: "{{ route('admin.tinymce.upload') }}",
        images_upload_credentials: true,

        file_picker_types: 'image',
        file_picker_callback(callback) {
            let input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';

            input.onchange = () => {
                let file = input.files[0];
                let fd = new FormData();
                fd.append('file', file);

                fetch("{{ route('admin.tinymce.upload') }}", {
                    method: "POST",
                    body: fd,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => callback(data.location));
            };
            input.click();
        },

        setup(editor) {
            editor.on('init', () => {
                document.dispatchEvent(new Event('tinymce-ready'));
            });
        }
    });
});
</script>
