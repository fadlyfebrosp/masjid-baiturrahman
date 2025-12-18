@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 p-6 flex justify-center">
        <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md">

            <h2 class="text-xl font-bold text-center mb-4">Kalkulator Zakat Sesuai Syariat</h2>

            <!-- PILIH JENIS ZAKAT -->
            <label class="font-semibold">Jenis Zakat:</label>
            <select id="jenisZakat" class="w-full p-3 border rounded-lg mb-4">
                <option value="fitrah">Zakat Fitrah</option>
                <option value="penghasilan">Zakat Penghasilan / Mal</option>
                <option value="emas">Zakat Emas</option>
                <option value="pertanian">Zakat Pertanian</option>
                <option value="peternakan">Zakat Peternakan</option>
            </select>

            <!-- INPUT NILAI -->
            <label class="font-semibold">Masukkan Nilai / Jumlah:</label>
            <input id="nilai" type="number" placeholder="(Rp / gram / kg / ekor)"
                class="w-full p-3 border rounded-lg mb-4" />

            <button id="hitungBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold">
                Hitung Zakat
            </button>

            <!-- HASIL -->
            <div id="hasilZakat" class="hidden mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded"></div>

            <!-- FORM LANJUTAN -->
            <div id="formLanjutan" class="hidden mt-6 p-4 bg-gray-50 border rounded-lg">

                <label class="font-semibold">Nama Muzakki</label>
                <input id="namaInput" type="text" class="w-full p-2 border rounded mb-3" placeholder="Masukkan nama">

                <label class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="hambaAllah" onclick="isiHambaAllah()"> Hamba Allah
                </label>

                <label class="font-semibold">Jenis Kelamin</label>
                <select id="genderInput" class="w-full p-2 border rounded mb-3">
                    <option value="">Pilih</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

                <label class="font-semibold">Metode Pembayaran</label>
                <select id="pembayaranInput" class="w-full p-2 border rounded mb-4">
                    <option value="">Pilih Metode</option>
                    <option value="Mandiri">M-Banking Mandiri</option>
                    <option value="BCA">M-Banking BCA</option>
                    <option value="BNI">M-Banking BNI</option>
                    <option value="BRI">M-Banking BRI</option>
                    <option value="OVO">OVO</option>
                    <option value="Dana">Dana</option>
                </select>

                <button onclick="bayarZakat()" class="w-full bg-green-600 text-white p-3 rounded-lg">
                    Pembayaran
                </button>

                <button onclick="exportPDF()" class="hidden w-full bg-purple-600 text-white p-3 mt-3 rounded-lg"
                    id="btnPDF">
                    Export PDF
                </button>

                <button onclick="exportExcel()" class="hidden w-full bg-yellow-600 text-white p-3 mt-3 rounded-lg"
                    id="btnExcel">
                    Export Excel
                </button>

                <button onclick="cetakStruk()" class="hidden w-full bg-gray-800 text-white p-3 mt-3 rounded-lg"
                    id="btnPrint">
                    Cetak Struk
                </button>

                <button onclick="resetData()" class="w-full bg-red-600 text-white p-3 mt-3 rounded-lg">
                    Reset Riwayat
                </button>
            </div>

            <!-- RIWAYAT -->
            <div id="riwayatZakat" class="hidden mt-4"></div>

        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // ========================
        // KONSTANTA
        // ========================
        const HARGA_BERAS = 10000;
        const HARGA_EMAS = 1200000;
        const NISAB_EMAS = 85;
        const NISAB_MAL_RUPIAH = HARGA_EMAS * NISAB_EMAS;
        const NISAB_GABAH = 653;

        // ========================
        // HITUNG ZAKAT
        // ========================
        document.getElementById("hitungBtn").addEventListener("click", hitungZakat);

        function hitungZakat() {
            const jenis = document.getElementById("jenisZakat").value;
            let nilai = parseFloat(document.getElementById("nilai").value);

            if (!nilai || nilai <= 0) {
                alert("Masukkan nilai yang benar!");
                return;
            }

            let hasil = 0;
            let keterangan = "";

            if (jenis === "fitrah") {
                hasil = 2.5 * HARGA_BERAS;
                keterangan = "Zakat Fitrah = 2.5 kg beras";
            } else if (jenis === "penghasilan") {
                if (nilai < NISAB_MAL_RUPIAH) return tampilkan("Belum mencapai nisab: Rp " + NISAB_MAL_RUPIAH
                    .toLocaleString("id-ID"));
                hasil = nilai * 0.025;
                keterangan = "Zakat Penghasilan = 2.5%";
            } else if (jenis === "emas") {
                if (nilai < NISAB_EMAS) return tampilkan("Emas belum mencapai nisab (85 gram).");
                hasil = nilai * HARGA_EMAS * 0.025;
                keterangan = "Zakat Emas = 2.5%";
            } else if (jenis === "pertanian") {
                if (nilai < NISAB_GABAH) return tampilkan("Belum mencapai nisab (653 kg gabah).");
                let tanpaIrigasi = confirm("Panen tanpa irigasi? (OK = 10%, Cancel = 5%)");
                hasil = nilai * (tanpaIrigasi ? 0.10 : 0.05);
                keterangan = "Zakat Pertanian = " + (tanpaIrigasi ? "10%" : "5%");
            } else if (jenis === "peternakan") {
                if (nilai < 30) return tampilkan("Belum mencapai nisab 30 ekor sapi / 40 kambing.");
                if (nilai >= 30 && nilai < 40) hasil = "1 ekor sapi (Tabi')";
                else if (nilai >= 40 && nilai < 120) hasil = "1 ekor kambing";
                else if (nilai >= 120) hasil = Math.floor(nilai / 40) + " ekor kambing";
                keterangan = "Zakat Peternakan";
            }

            tampilkan(
                `Total Zakat:\n${typeof hasil === "number" ? "Rp " + hasil.toLocaleString("id-ID") : hasil}\n\n${keterangan}`
                );

            document.getElementById("formLanjutan").classList.remove("hidden");
        }

        // ========================
        // TAMPILKAN HASIL
        // ========================
        function tampilkan(teks) {
            let box = document.getElementById("hasilZakat");
            box.classList.remove("hidden");
            box.innerText = teks;
        }

        // ========================
        // Hamba Allah Auto-fill
        // ========================
        function isiHambaAllah() {
            document.getElementById("namaInput").value = document.getElementById("hambaAllah").checked ? "Hamba Allah" : "";
        }

        // ========================
        // NOMOR TRANSAKSI
        // ========================
        function generateNoTransaksi() {
            return "TRX-" + Date.now();
        }

        // ========================
        // SIMPAN RIWAYAT
        // ========================
        function simpanRiwayat(data) {
            let riwayat = JSON.parse(localStorage.getItem("riwayat_zakat") || "[]");
            riwayat.push(data);
            localStorage.setItem("riwayat_zakat", JSON.stringify(riwayat));
        }

        // ========================
        // PEMBAYARAN
        // ========================
        function bayarZakat() {
            const nama = document.getElementById("namaInput").value || "Hamba Allah";
            const gender = document.getElementById("genderInput").value;
            const bayar = document.getElementById("pembayaranInput").value;
            const hasil = document.getElementById("hasilZakat").innerText;

            if (!bayar) return alert("Pilih metode pembayaran!");

            let transaksiID = generateNoTransaksi();

            simpanRiwayat({
                id: transaksiID,
                nama,
                gender,
                metode: bayar,
                hasil,
                tanggal: new Date().toLocaleString()
            });

            alert("Jazakallah Khair — Pembayaran Berhasil\nNomor Transaksi: " + transaksiID);

            document.getElementById("btnPDF").classList.remove("hidden");
            document.getElementById("btnExcel").classList.remove("hidden");
            document.getElementById("btnPrint").classList.remove("hidden");

            tampilkanRiwayat();
        }

        // ========================
        // TAMPILKAN RIWAYAT
        // ========================
        function tampilkanRiwayat() {
            let data = JSON.parse(localStorage.getItem("riwayat_zakat") || "[]");
            let container = document.getElementById("riwayatZakat");
            if (data.length === 0) {
                container.classList.add("hidden");
                container.innerHTML = "";
                return;
            }
            container.classList.remove("hidden");

            let html = "";
            data.reverse().forEach(r => {
                html += `
            <div class="p-3 bg-white border rounded mb-2 shadow">
                <p class="font-bold">${r.id}</p>
                <p>Nama: ${r.nama}</p>
                <p>Gender: ${r.gender}</p>
                <p>Metode: ${r.metode}</p>
                <p>Hasil Zakat: ${r.hasil}</p>
                <p>Tanggal: ${r.tanggal}</p>
            </div>
        `;
            });
            container.innerHTML = html;
        }

        window.onload = tampilkanRiwayat;

        // ========================
        // EXPORT PDF & EXCEL
        // ========================
        function exportPDF() {
            let riwayat = JSON.parse(localStorage.getItem("riwayat_zakat") || "[]");
            if (!riwayat.length) return alert("Belum ada riwayat.");

            let text = "RIWAYAT PEMBAYARAN ZAKAT\n\n";
            riwayat.forEach(r => {
                text +=
                    `ID: ${r.id}\nNama: ${r.nama}\nMetode: ${r.metode}\nTanggal: ${r.tanggal}\nZakat: ${r.hasil}\n\n`;
            });
            let blob = new Blob([text], {
                type: "application/pdf"
            });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "riwayat_zakat.pdf";
            link.click();
        }

        function exportExcel() {
            let data = JSON.parse(localStorage.getItem("riwayat_zakat") || "[]");
            if (!data.length) return alert("Belum ada riwayat.");

            let csv = "ID,Nama,Gender,Metode,Tanggal,Hasil\n";
            data.forEach(r => {
                csv += `${r.id},${r.nama},${r.gender},${r.metode},${r.tanggal},"${r.hasil}"\n`;
            });
            let blob = new Blob([csv], {
                type: "text/csv"
            });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "riwayat_zakat.csv";
            link.click();
        }

        // ========================
        // CETAK STRUK
        // ========================
        function cetakStruk() {
            const hasil = document.getElementById("hasilZakat").innerText;
            const nama = document.getElementById("namaInput").value;
            let win = window.open("", "Print", "width=600,height=700");
            win.document.write("<h2>Struk Pembayaran Zakat</h2>");
            win.document.write("<p>Nama: " + (nama || "Hamba Allah") + "</p>");
            win.document.write("<pre>" + hasil + "</pre>");
            win.print();
            win.close();
        }

        // ========================
        // RESET DATA
        // ========================
        function resetData() {
            localStorage.removeItem("riwayat_zakat");
            alert("Riwayat zakat berhasil direset!");
            tampilkanRiwayat();
        }
    </script>
@endsection
