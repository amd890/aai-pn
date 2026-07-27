# Asosiasi Arsiparis Indonesia - Pengurus Nasional (AAI-PN)

Sistem Informasi Terpadu dan Portal Resmi Pengurus Nasional Asosiasi Arsiparis Indonesia (AAI-PN). Aplikasi ini dibangun dengan menggunakan framework Laravel dan Livewire.

## Fitur Utama

*   **Portal Publik & CMS:** Berita, artikel, agenda, galeri, FAQ, dan manajemen konten (halaman dinamis).
*   **Manajemen Keanggotaan:** Pendaftaran anggota baru, verifikasi berjenjang, dan penerbitan Kartu Tanda Anggota (KTA) digital.
*   **Sertifikasi LSP:** Pengelolaan skema sertifikasi, pendaftaran uji kompetensi, penilaian asesor, dan penerbitan sertifikat profesi.
*   **Manajemen Event & Diklat:** Pendaftaran acara, seminar, diklat, pencatatan kehadiran (QR Code), dan penerbitan e-sertifikat kegiatan.
*   **E-Voting & Pemilu:** Sistem pemilihan ketua dan pengurus yang aman dan transparan secara online.
*   **E-Office & Tata Naskah:** Penomoran otomatis dan pengelolaan surat masuk serta surat keluar secara digital.
*   **Keuangan & Iuran:** Pembayaran iuran keanggotaan dan verifikasi bukti bayar.

## Persyaratan Sistem

*   PHP 8.2 atau lebih baru
*   Composer
*   Node.js & NPM
*   Database (MySQL/PostgreSQL)

## Panduan Instalasi (Development)

1.  **Clone Repository**
    ```bash
    git clone https://github.com/amd890/aai-pn.git
    cd aai-pn
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Copy file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Migrasi & Seeding Database**
    Aplikasi dilengkapi dengan dummy data dan konfigurasi dasar (Roles, Permissions, Menu) menggunakan seeder.
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Compile Assets (Tailwind & Vite)**
    ```bash
    npm run build
    # atau untuk development: npm run dev
    ```

6.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

## Kredensial Default (Testing)

*   **Super Admin:** `superadmin@aai.or.id` / password: `password`
*   **Administrator:** `admin@aai.or.id` / password: `password`

## Lisensi

Hak Cipta &copy; Pengurus Nasional Asosiasi Arsiparis Indonesia. All rights reserved.
