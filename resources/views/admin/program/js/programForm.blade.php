{{-- resources/views/admin/program/js/programForm.blade.php --}}
<script>
function programForm() {
  return {
    form: {
      nama_produk: '',
      kategori: '',
      min_donasi: 0,
      custom_nominal: ['10000','20000','50000','100000'],
      target_dana: '',
      target_date: '', // <-- YYYY-MM-DD (default native)
      open_goals: false,
      produk_deskripsi: '',
      foto_file: null,
    },

    preview: null,
    defaultImage: '{{ asset("assets/img/Image-not-found.png") }}',
    shortDescription: '',
    hasLongDescription: false,

    /* ===============================
       INIT
    =============================== */
    initProgram(mode) {
      const input = document.getElementById('programDataEdit') || document.getElementById('programDataShow');
      const program = input ? JSON.parse(input.value) : null;

      if (program) this.loadData(program);

      this.initTinyMCE();

      // mode "show"
      if (mode === 'show') {
        setTimeout(() => {
          document.querySelectorAll('input, textarea, select, button[type=submit]')
            .forEach(el => el.setAttribute('disabled', true));
        }, 200);
      }
    },

    /* ===============================
       LOAD DATA (EDIT/SHOW)
    =============================== */
    loadData(program) {
      this.form.nama_produk = program.judul ?? '';
      this.form.kategori = program.kategori ?? '';
      this.form.min_donasi = program.min_donasi ?? 0;
      this.form.custom_nominal = program.custom_nominal ?? this.form.custom_nominal;
      this.form.target_dana = program.target_dana ?? '';
      this.form.open_goals = program.open_goals ? true : false;

      // ⬅️ langsung ke YYYY-MM-DD (native)
      this.form.target_date = program.target_waktu ?? '';
      // 🔥 WAJIB !!!!!
      this.form.produk_deskripsi = program.deskripsi ?? '';

      // 🔥 Update short description preview
      this.generateShortDesc(this.form.produk_deskripsi);


      if (program.foto) {
        this.preview = '/storage/' + program.foto;
      }
    },

    /* ===============================
       VALIDASI TARGET DATE
    =============================== */
    validateTargetDate() {
      if (this.form.open_goals) return true;
      if (!this.form.target_date) return true;

      const target = new Date(this.form.target_date);
      const today = new Date();
      today.setHours(0,0,0,0);

      if (target <= today) {
        alert('Target waktu harus lebih dari hari ini.');
        this.form.target_date = '';
        return false;
      }
      return true;
    },

    /* ===============================
       SISA WAKTU (native format)
    =============================== */
    remainingDays() {
      if (!this.form.target_date) return 0;
      const target = new Date(this.form.target_date);
      const now = new Date();
      const diff = target - now;
      return diff > 0 ? Math.ceil(diff / (1000*60*60*24)) : 0;
    },

    /* ===============================
       IMAGE PREVIEW
    =============================== */
    previewImage(e) {
      const file = e.target.files?.[0];
      if (!file) return;
      this.form.foto_file = file;
      this.preview = URL.createObjectURL(file);
    },

    /* ===============================
       SHORT DESCRIPTION
    =============================== */
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
       FORMAT RUPIAH
    =============================== */
    formatRupiah(angka) {
      if (!angka) return 'Rp 0';
      return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    /* ===============================
       TINYMCE
    =============================== */
    initTinyMCE() {
      const wait = setInterval(() => {
        if (window.tinymce) {
          clearInterval(wait);
          this.startTiny();
        }
      }, 150);
    },

    startTiny() {
        if (tinymce.get('produk_deskripsi')) return;

        tinymce.init({
            selector: '#produk_deskripsi',
            height: 300,
            plugins: 'link image media code lists table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image media | code',

            setup: (ed) => {
                ed.on('init', () => {
                    // 🔥 SET VALUE DARI DATABASE KE EDITOR
                    ed.setContent(this.form.produk_deskripsi || '');

                    // 🔥 Generate preview short desc
                    this.generateShortDesc(this.form.produk_deskripsi);
                });

                ed.on('keyup change', () => {
                    this.form.produk_deskripsi = ed.getContent();
                    this.generateShortDesc(ed.getContent());
                });
            }
        });
    }
  }
}
</script>
