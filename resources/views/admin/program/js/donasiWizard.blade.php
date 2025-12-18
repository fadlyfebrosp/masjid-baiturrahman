{{-- resources/views/admin/program/js/donasiWizard.blade.php --}}
<script>
function donasiWizard() {
    return {

        // HANYA PREVIEW PROPERTY
        previewMode: 'desktop',
        defaultImage: '{{ asset('assets/img/Image-not-found.png') }}',
        preview: null,
        showFullDesc: false,
        shortDescription: '',
        hasLongDescription: false,

        /* ===============================
           INIT — Tidak lagi gunakan form!
        =============================== */
        init() {},

        /* ===============================
           FORMAT RUPIAH
        =============================== */
        formatRupiah(angka) {
            if (!angka) return "Rp 0";
            return "Rp " + angka.toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        /* ===============================
           SISA WAKTU
        =============================== */
        remainingDays(targetDate) {
            if (!targetDate) return 0;

            let p = targetDate.split('/');
            let target = new Date(p[2], p[1]-1, p[0]);
            let now = new Date();
            let diff = target - now;

            return diff <= 0 ? 0 :
                Math.ceil(diff / (1000*60*60*24));
        },
        generateShortDesc(html) {
            if (!html) {
                this.shortDescription = "Belum ada deskripsi";
                this.hasLongDescription = false;
                return;
            }

            // FIX: bersihkan newline supaya UL/OL bisa dirender
            html = html.replace(/[\n\r\t]/g, "").trim();

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            let blocks = [...doc.body.children];
            let result = "";

            // Ambil paragraf pertama
            if (blocks[0]) {
                result += blocks[0].outerHTML;
            }

            // Ambil list (UL/OL)
            const listBlock = blocks.find(b => b.tagName === "UL" || b.tagName === "OL");

            if (listBlock) {
                const clone = listBlock.cloneNode(true);
                const items = clone.querySelectorAll("li");

                items.forEach((li, i) => {
                    if (i >= 3) li.remove();
                });

                result += clone.outerHTML;
            }

            this.shortDescription = result.trim();
            this.hasLongDescription = true;
        },
        /* ===============================
           IMAGE PREVIEW
        =============================== */
        previewImage(file) {
            if (!file) return;
            this.preview = URL.createObjectURL(file);
        }
    }
}
</script>
