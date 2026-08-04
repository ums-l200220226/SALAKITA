**SALAKITA — Marketplace Digital Agribisnis Salak**
Platform marketplace digital yang dikembangkan sebagai solusi transformasi digital untuk agribisnis salak, menghubungkan petani/penjual dengan pembeli secara lebih transparan dan efisien. Dikembangkan sebagai proyek Tugas Akhir (Skripsi).

**📋 Latar Belakang**
Minimnya ketersediaan informasi stok dan harga di sektor agribisnis salak menyulitkan pembeli dalam mengambil keputusan, sekaligus menghambat efisiensi transaksi antara petani dan pembeli. SALAKITA dikembangkan sebagai solusi yang tidak hanya menyediakan informasi, tetapi juga memfasilitasi transaksi jual-beli secara menyeluruh — mencakup aspek manajemen dan bisnis, sejalan dengan pendekatan pengembangan sistem yang lebih luas dibanding sistem informasi konvensional.

**✨ Fitur Utama**
- Informasi stok dan harga salak secara real-time
- Transaksi jual-beli secara online dengan integrasi pembayaran QRIS (split payment via Xendit)
- Dukungan transaksi offline
- Sistem rating untuk pembeli
- Status tracking pesanan (diproses, dikirim, selesai)
- Dashboard super admin untuk memantau pendapatan agribisnis desa secara keseluruhan
- Manajemen produk dan pesanan untuk penjual/petani

**🛠️ Tech Stack**
- **Backend:** Laravel, PHP
- **Frontend:** JavaScript, Tailwind CSS
- **Database:** MySQL
- **Payment Gateway:** Xendit (QRIS)

**💡 Pertimbangan Teknis**
Sistem melibatkan banyak penjual (petani) dengan satu super admin yang memantau pendapatan agribisnis secara keseluruhan di tingkat desa. Oleh karena itu, dibutuhkan payment gateway dengan fitur **split payment**, agar dana dari pembeli dapat langsung diteruskan ke masing-masing penjual tanpa harus melalui proses pencairan manual oleh super admin. Xendit dipilih karena mendukung fitur ini, berbeda dengan beberapa payment gateway lain yang hanya mendukung transaksi satu arah (penjual tunggal ke pembeli).

**🎯 Tujuan Proyek**
Membantu digitalisasi sektor agribisnis salak dengan menyediakan platform yang tidak hanya informatif, tetapi juga fungsional secara transaksional — mendukung efisiensi proses jual-beli antara petani dan pembeli, sekaligus transparansi pendapatan di tingkat desa.

**📌 Catatan**
Proyek ini dikembangkan sebagai bagian dari Tugas Akhir (Skripsi) di Program Studi Teknik Informatika, Universitas Muhammadiyah Surakarta.
