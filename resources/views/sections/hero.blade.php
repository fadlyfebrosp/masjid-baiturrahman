<section
    class="relative bg-cover bg-center h-[90vh] flex items-center justify-start text-white"
    style="background-image: url('{{ asset('assets/img/masjid_baiturrahman.jpg') }}');">

    <div class="absolute inset-0 bg-green-900/80"></div>

    <div class="relative z-10 max-w-3xl ml-16 md:ml-32 text-left">

        <h1 class="text-4xl md:text-5xl font-bold mb-6">
            Masjid Baiturrahman
        </h1>

        <p
            x-data="typeWriterLoop()"
            x-init="start()"
            class="text-lg leading-relaxed mb-8"
        >
            <span x-text="displayText"></span>
            <span class="animate-pulse">|</span>
        </p>

        <a href="{{ route('berita') }}"
            class="bg-white text-green-700 px-6 py-3 rounded-full font-semibold hover:bg-green-100 transition">
            Lihat Berita & Kegiatan
        </a>

    </div>
</section>

<script>
function typeWriterLoop() {
    return {
        text: `Terletak di Selakopi, Sindangbarang, Bogor Barat, Masjid Baiturrahman hadir sebagai pusat ibadah, pembinaan umat, dan kegiatan sosial masyarakat. Melalui berbagai program kemasjidan dan kegiatan keagamaan, kami berupaya menghadirkan manfaat yang luas bagi jamaah dan lingkungan sekitar.`,
        displayText: '',
        index: 0,
        isDeleting: false,

        typingSpeed: 30,
        deletingSpeed: 20,
        delayAfterTyping: 2000,
        delayAfterDeleting: 800,

        start() {
            this.typeLoop()
        },

        typeLoop() {
            if (!this.isDeleting) {
                // TYPING
                if (this.index < this.text.length) {
                    this.displayText += this.text[this.index]
                    this.index++
                    setTimeout(() => this.typeLoop(), this.typingSpeed)
                } else {
                    // selesai ngetik → tunggu → hapus
                    setTimeout(() => {
                        this.isDeleting = true
                        this.typeLoop()
                    }, this.delayAfterTyping)
                }
            } else {
                // DELETING
                if (this.displayText.length > 0) {
                    this.displayText = this.displayText.slice(0, -1)
                    setTimeout(() => this.typeLoop(), this.deletingSpeed)
                } else {
                    // selesai hapus → tunggu → ketik lagi
                    setTimeout(() => {
                        this.isDeleting = false
                        this.index = 0
                        this.typeLoop()
                    }, this.delayAfterDeleting)
                }
            }
        }
    }
}
</script>

