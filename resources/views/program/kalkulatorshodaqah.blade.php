@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 p-6 flex justify-center">
        <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md">

            <h2 class="text-xl font-bold text-center mb-4">Kalkulator Sedekah</h2>

            <!-- NOMINAL -->
            <label class="font-semibold">Nominal Sedekah</label>
            <input id="SedekahInput" type="text" oninput="formatRupiah(this)" class="w-full p-3 border rounded mb-6 mt-2"
                placeholder="Masukkan nominal (Rp)">

            <button onclick="hitungSedekah()" class="w-full bg-blue-600 text-white p-3 mt-4 rounded-lg">
                Hitung
            </button>

            <button onclick="tampilRiwayat()" class="w-full mt-4 bg-gray-800 text-white p-3 rounded-lg">
                Lihat Riwayat Pembayaran
            </button>

            <button onclick="resetData()" class="w-full mt-3 bg-red-600 text-white p-3 rounded-lg">
                Reset Semua Data
            </button>

            <div id="hasilSedekah" class="hidden mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
            </div>

            <!-- FORM LANJUTAN -->
            <div id="formLanjutan" class="hidden mt-6 p-4 bg-gray-50 border rounded-lg">

                <label class="font-semibold">Nama</label>
                <input id="namaInput" type="text" class="w-full p-2 border rounded mb-3">

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
                    <option value="Mandiri">Mandiri</option>
                    <option value="BCA">BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="OVO">OVO</option>
                    <option value="GoPay">GoPay</option>
                    <option value="Dana">Dana</option>
                </select>

                <button onclick="bayarSedekah()" class="w-full bg-green-600 text-white p-3 rounded-lg">
                    Pembayaran
                </button>

                <button id="btnPDF" onclick="downloadPDF()"
                    class="hidden w-full bg-purple-600 text-white p-3 mt-3 rounded-lg">
                    Download Struk PDF
                </button>

                <button id="btnExcel" onclick="downloadExcel()"
                    class="hidden w-full bg-yellow-600 text-white p-3 mt-3 rounded-lg">
                    Download Riwayat Excel
                </button>
            </div>

            <div id="riwayatBox" class="hidden mt-4"></div>

        </div>
    </div>

    <!-- ================== JAVASCRIPT ================== -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // ===============================
        // FORMAT RUPIAH
        // ===============================
        function formatRupiah(el) {
            el.value = el.value.replace(/[^0-9]/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // ===============================
        // Hamba Allah otomatis
        // ===============================
        function isiHambaAllah() {
            const check = document.getElementById("hambaAllah");
            const input = document.getElementById("namaInput");
            input.value = check.checked ? "Hamba Allah" : "";
        }

        // ===============================
        // Hitung Sedekah
        // ===============================
        function hitungSedekah() {
            let nilai = document.getElementById("SedekahInput").value;
            let angka = nilai.replace(/\./g, "");

            if (!angka || angka <= 0) {
                alert("Masukkan nominal valid!");
                return;
            }

            document.getElementById("hasilSedekah").classList.remove("hidden");
            document.getElementById("hasilSedekah").innerText =
                "Anda berniat berSedekah sebesar: Rp " +
                parseInt(angka).toLocaleString("id-ID");

            document.getElementById("formLanjutan").classList.remove("hidden");
        }

        // ===============================
        // Nomor Transaksi
        // ===============================
        function buatNomorTransaksi() {
            let t = new Date();
            let d = t.toISOString().slice(0, 10).replace(/-/g, "");
            let r = Math.floor(100000 + Math.random() * 900000);
            return `INV-${d}-${r}`;
        }

        // ===============================
        // Simpan Riwayat
        // ===============================
        function simpanRiwayat(obj) {
            let data = JSON.parse(localStorage.getItem("riwayatSedekah") || "[]");
            data.push(obj);
            localStorage.setItem("riwayatSedekah", JSON.stringify(data));
        }

        // ===============================
        // Pembayaran
        // ===============================
        function bayarSedekah() {
            let nama = document.getElementById("namaInput").value || "Hamba Allah";
            let gender = document.getElementById("genderInput").value;
            let metode = document.getElementById("pembayaranInput").value;
            let nominal = document.getElementById("SedekahInput").value;
            let angka = nominal.replace(/\./g, "");

            if (!gender || !metode) {
                alert("Lengkapi data terlebih dahulu!");
                return;
            }

            let nomor = buatNomorTransaksi();
            let waktu = new Date().toLocaleString("id-ID");

            alert("Jazakallah Khairan, Pembayaran berhasil!");

            let dataObj = {
                nomor,
                nama,
                gender,
                metode,
                nominal: angka,
                waktu
            };

            simpanRiwayat(dataObj);
            window.dataPDF = dataObj;

            document.getElementById("btnPDF").classList.remove("hidden");
            document.getElementById("btnExcel").classList.remove("hidden");
            document.getElementById("btnPrint").classList.remove("hidden");
            document.getElementById("btnPDFAll").classList.remove("hidden");
        }

        // ===============================
        // Tampilkan Riwayat
        // ===============================
        function tampilRiwayat() {
            let data = JSON.parse(localStorage.getItem("riwayatSedekah") || "[]");
            let box = document.getElementById("riwayatBox");
            box.classList.remove("hidden");

            if (data.length === 0) {
                box.innerHTML = "<p class='text-gray-600'>Belum ada riwayat pembayaran.</p>";
                return;
            }

            let html = "";
            data.reverse().forEach(r => {
                html += `
      <div class="p-3 bg-white border rounded mb-2 shadow">
        <p class="font-bold">${r.nomor}</p>
        <p>Nama: ${r.nama}</p>
        <p>Gender: ${r.gender}</p>
        <p>Metode: ${r.metode}</p>
        <p>Nominal: Rp ${parseInt(r.nominal).toLocaleString("id-ID")}</p>
        <p>Waktu: ${r.waktu}</p>
      </div>
    `;
            });

            box.innerHTML = html;
        }

        // ===============================
        // STRUK PDF
        // ===============================
        function downloadPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            let d = window.dataPDF;

            doc.setFontSize(18);
            doc.text("STRUK PEMBAYARAN SEDEKAH", 20, 20);

            doc.setFontSize(12);
            doc.text("Nomor Transaksi : " + d.nomor, 20, 40);
            doc.text("Nama : " + d.nama, 20, 50);
            doc.text("Jenis Kelamin : " + d.gender, 20, 60);
            doc.text("Metode Bayar : " + d.metode, 20, 70);
            doc.text("Nominal Sedekah : Rp " + parseInt(d.nominal).toLocaleString("id-ID"), 20, 80);
            doc.text("Waktu Transaksi : " + d.waktu, 20, 90);

            let pdf = doc.output("blob");
            let url = URL.createObjectURL(pdf);

            let a = document.createElement("a");
            a.href = url;
            a.download = d.nomor + ".pdf";
            a.click();

            URL.revokeObjectURL(url);
        }

        // ===============================
        // CETAK LANGSUNG KE PRINTER
        // ===============================
        function cetakPrinter() {
            let d = window.dataPDF;

            let printWindow = window.open("", "_blank");
            printWindow.document.write(`
    <html><body>
      <h2>STRUK PEMBAYARAN SEDEKAH</h2>
      <p>Nomor Transaksi: ${d.nomor}</p>
      <p>Nama: ${d.nama}</p>
      <p>Jenis Kelamin: ${d.gender}</p>
      <p>Metode: ${d.metode}</p>
      <p>Nominal: Rp ${parseInt(d.nominal).toLocaleString("id-ID")}</p>
      <p>Waktu: ${d.waktu}</p>
      <script>window.print();<\/script>
    </body></html>
  `);
        }

        // ===============================
        // EXPORT EXCEL
        // ===============================
        function downloadExcel() {
            let data = JSON.parse(localStorage.getItem("riwayatSedekah") || "[]");
            if (data.length === 0) {
                alert("Belum ada riwayat.");
                return;
            }

            let ws = XLSX.utils.json_to_sheet(data);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Riwayat");
            XLSX.writeFile(wb, "riwayat-Sedekah.xlsx");
        }

        // ===============================
        // PDF SELURUH RIWAYAT
        // ===============================
        function downloadPDFAll() {
            const {
                jsPDF
            } = window.jspdf;
            let data = JSON.parse(localStorage.getItem("riwayatSedekah") || "[]");

            if (data.length === 0) {
                alert("Belum ada riwayat.");
                return;
            }

            const doc = new jsPDF();
            let y = 20;

            doc.setFontSize(16);
            doc.text("Laporan Riwayat Sedekah", 20, 15);

            doc.setFontSize(12);

            data.forEach((r, i) => {
                if (y > 270) {
                    doc.addPage();
                    y = 20;
                }

                doc.text(`${i+1}. ${r.nomor} | ${r.nama} | Rp ${parseInt(r.nominal).toLocaleString("id-ID")}`, 20,
                    y);
                y += 10;
            });

            doc.save("riwayat-Sedekah.pdf");
        }

        // ===============================
        // RESET SEMUA DATA
        // ===============================
        function resetRiwayat() {
            if (confirm("Hapus semua riwayat?")) {
                localStorage.removeItem("riwayatSedekah");
                alert("Riwayat berhasil direset!");
                location.reload();
            }
        }
    </script>
@endsection
