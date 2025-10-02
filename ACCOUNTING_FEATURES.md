# Fitur Akuntansi dan Manajemen Persediaan - LELESEGAR

## Ringkasan Fitur yang Telah Diimplementasikan

Sistem akuntansi dan manajemen persediaan ini telah diimplementasikan untuk usaha penjualan ikan lele LELESEGAR. Semua fitur menggunakan bahasa Indonesia dan format mata uang Rupiah.

### 1. Fitur Setup Awal (Initial Setup)

#### A. Manajemen Produk
- **Lokasi:** Menu "Setup Awal" → "Produk"
- **Fitur:**
  - Tambah produk dengan kode produk, nama, harga jual, dan harga beli
  - Set satuan (kg, ekor, dll)
  - Status aktif/tidak aktif
  - **Otomatis:** Saat produk dibuat, sistem otomatis membuat entri inventory

#### B. Manajemen Aset/Peralatan
- **Lokasi:** Menu "Setup Awal" → "Aset/Peralatan"
- **Fitur:**
  - Catat aset seperti peralatan, perlengkapan, kendaraan
  - Harga beli, nilai saat ini, tanggal pembelian
  - Umur ekonomis dan tingkat penyusutan
  - Jenis aset: Peralatan, Perlengkapan, Kendaraan, Lainnya

#### C. Manajemen Modal & Prive
- **Lokasi:** Menu "Setup Awal" → "Modal & Prive"
- **Fitur:**
  - Catat modal awal usaha
  - Tambah modal (additional capital)
  - Catat pengambilan pemilik (prive/owner withdrawal)
  - Kode transaksi otomatis (format: EQ-YYYYMMDD-XXXX)

### 2. Fitur Transaksi Pemasukan

- **Lokasi:** Menu "Transaksi" → "Pemasukan"
- **Fitur:**
  - Catat semua pemasukan (penjualan, tambah modal, lainnya)
  - Kode transaksi otomatis (format: INC-YYYYMMDD-XXXX)
  - Metode pembayaran: Tunai, Transfer Bank, Lainnya
  - Link ke order (opsional) untuk pemasukan dari penjualan

### 3. Fitur Laporan Persediaan

#### A. Persediaan (Inventory)
- **Lokasi:** Menu "Laporan" → "Persediaan"
- **Fitur:**
  - Lihat stok saat ini untuk setiap produk
  - Warning otomatis jika stok di bawah minimum
  - Update jumlah stok
  - Set stok minimum

#### B. Mutasi Persediaan
- **Lokasi:** Menu "Laporan" → "Mutasi Persediaan"
- **Fitur:**
  - Lihat history perubahan persediaan
  - Stok sebelum dan sesudah transaksi
  - Referensi ke transaksi yang menyebabkan mutasi
  - **Catatan:** Mutasi dibuat otomatis oleh sistem (tidak bisa dibuat manual)

### 4. Fitur Transaksi Pengeluaran

- **Lokasi:** Menu "Transaksi" → "Pengeluaran"
- **Fitur:**
  - Catat semua pengeluaran berdasarkan kategori
  - Kategori: Gaji, Bahan Baku, Listrik, Air, Telepon & Internet, dll
  - Kode transaksi otomatis (format: EXP-YYYYMMDD-XXXX)
  - Nama vendor/supplier
  - Metode pembayaran: Tunai, Transfer Bank, Lainnya

### 5. Fitur Jurnal Umum (Buku Besar)

- **Lokasi:** Menu "Sistem" → "Journal Entries"
- **Fitur:**
  - Chart of Accounts (Bagan Akun) sudah di-seed otomatis
  - Akun-akun: Kas, Piutang, Persediaan, Peralatan, Utang, Modal, Pendapatan, Beban
  - **Otomatis:** Journal entries akan dibuat otomatis dari transaksi (dalam development)
  - Double-entry bookkeeping (debit & credit)

### 6. Laporan Laba Rugi (Income Statement)

- **Lokasi:** Menu "Laporan" → "Laporan Laba Rugi"
- **Fitur:**
  - Filter berdasarkan periode (tanggal mulai - tanggal akhir)
  - Tampilkan:
    - Total Pendapatan (Penjualan + Lainnya)
    - Total Beban (dikelompokkan per kategori)
    - Laba/Rugi Bersih
  - Format Rupiah
  - Warna hijau untuk laba, merah untuk rugi

### 7. Laporan Perubahan Ekuitas (Statement of Changes in Equity)

- **Lokasi:** Menu "Laporan" → "Laporan Perubahan Ekuitas"
- **Fitur:**
  - Filter berdasarkan periode
  - Tampilkan:
    - Modal Awal
    - Tambahan Modal
    - Laba/Rugi Bersih
    - Prive (Pengambilan Pemilik)
    - Modal Akhir
  - Perhitungan otomatis: Modal Awal + Tambahan Modal +/- Laba/Rugi - Prive

### 8. Laporan Posisi Keuangan (Balance Sheet/Neraca)

- **Lokasi:** Menu "Laporan" → "Laporan Posisi Keuangan"
- **Fitur:**
  - Filter per tanggal tertentu
  - **ASET:**
    - Kas (dari transaksi pemasukan - pengeluaran)
    - Persediaan Barang (nilai stok)
    - Peralatan (nilai aset tetap)
  - **KEWAJIBAN & EKUITAS:**
    - Utang
    - Modal
    - Prive
    - Laba Ditahan
  - Balancing: Total Aset = Total Kewajiban + Total Ekuitas

## Fitur Tambahan

### Chart of Accounts (Bagan Akun)
Sistem sudah dilengkapi dengan chart of accounts standar:
- **1-xxxx**: Aset (Kas, Piutang, Persediaan, Perlengkapan, Peralatan)
- **2-xxxx**: Kewajiban (Utang Usaha, Utang Gaji)
- **3-xxxx**: Ekuitas (Modal, Prive)
- **4-xxxx**: Pendapatan (Penjualan, Pendapatan Lain)
- **5-xxxx**: Beban (HPP, Gaji, Listrik, Telepon, Penyusutan, dll)

### Kategori Pengeluaran Default
Sistem sudah dilengkapi dengan kategori pengeluaran standar:
- Gaji Karyawan
- Pembelian Bahan Baku
- Listrik
- Air
- Telepon & Internet
- Transportasi
- Pemeliharaan
- Sewa
- Lain-lain

## Cara Menggunakan

### Setup Awal
1. Login ke admin panel di `/adminpanel`
2. Buat produk di menu "Produk"
3. Catat aset/peralatan di menu "Aset/Peralatan"
4. Catat modal awal di menu "Modal & Prive"

### Transaksi Harian
1. **Pemasukan:** Catat setiap penjualan atau pemasukan lain di menu "Pemasukan"
2. **Pengeluaran:** Catat setiap biaya di menu "Pengeluaran"
3. **Stok:** Cek dan update persediaan di menu "Persediaan"

### Laporan Bulanan
1. Buka "Laporan Laba Rugi" untuk melihat profit/loss
2. Buka "Laporan Perubahan Ekuitas" untuk melihat perubahan modal
3. Buka "Laporan Posisi Keuangan" untuk melihat neraca

## Fitur yang Akan Datang (In Progress)

1. **Auto Journal Entries:** Sistem akan otomatis membuat journal entries saat ada transaksi
2. **Inventory Auto-Reduction:** Stok otomatis berkurang saat ada penjualan
3. **Depreciation Calculation:** Perhitungan penyusutan aset otomatis
4. **Cash Flow Report:** Laporan arus kas

## Teknologi

- **Framework:** Laravel 12 + Filament 3.3
- **Database:** SQLite (development) / MySQL (production)
- **Language:** PHP 8.2+
- **UI:** Filament Admin Panel (Tailwind CSS)

## Catatan Penting

- Semua kode transaksi di-generate otomatis dengan format standar
- Format tanggal: DD MMM YYYY (contoh: 02 Okt 2025)
- Format mata uang: Rp X.XXX.XXX
- Filter tanggal menggunakan format ISO (YYYY-MM-DD)
- Sistem menggunakan double-entry bookkeeping untuk akuntansi yang akurat
