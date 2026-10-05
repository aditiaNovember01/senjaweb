# Requirements Document

## Introduction

Website UKM Seni Jayanusa (SENJA) adalah platform digital resmi milik Unit Kegiatan Mahasiswa Seni di Universitas Jayanusa. Website ini dibangun menggunakan Laravel dan melayani dua kelompok pengguna utama: **pengunjung publik** yang ingin mengetahui informasi organisasi, serta **administrator** (pengurus SENJA) yang mengelola seluruh konten melalui dashboard admin.

Website mencakup tiga modul utama:
1. **Pendataan Anggota** — registrasi, profil, dan pengelolaan status keanggotaan.
2. **Pengarsipan Surat Online** — pencatatan, kategorisasi, dan pengarsipan surat masuk dan surat keluar.
3. **Program Kerja & Kegiatan** — publikasi dan pengelolaan program kerja serta agenda kegiatan SENJA.

---

## Glossary

- **Sistem**: Aplikasi web SENJA yang dibangun dengan Laravel.
- **Admin**: Pengguna yang memiliki akses penuh ke dashboard admin; merupakan pengurus atau staf SENJA yang telah terautentikasi.
- **Anggota**: Mahasiswa Universitas Jayanusa yang terdaftar secara resmi di UKM SENJA.
- **Pengunjung**: Pengguna umum yang mengakses website tanpa autentikasi (non-anggota).
- **Dashboard**: Antarmuka admin untuk mengelola seluruh konten website.
- **Surat_Masuk**: Dokumen surat yang diterima oleh organisasi SENJA dari pihak luar.
- **Surat_Keluar**: Dokumen surat yang dikirim oleh organisasi SENJA kepada pihak luar.
- **Arsip_Surat**: Kumpulan surat masuk dan surat keluar yang telah dicatat dan diarsipkan di dalam Sistem.
- **Proker**: Program Kerja yang direncanakan dan dilaksanakan oleh SENJA dalam satu periode kepengurusan.
- **Kegiatan**: Aktivitas atau acara yang diselenggarakan oleh SENJA, baik yang akan datang maupun yang sudah berlangsung.
- **Divisi**: Bagian atau bidang kerja dalam struktur organisasi SENJA (contoh: Divisi Tari, Divisi Musik, Divisi Teater).
- **Periode_Kepengurusan**: Tahun kepengurusan aktif organisasi SENJA (contoh: 2024/2025).
- **Nomor_Surat**: Kode unik yang mengidentifikasi sebuah surat sesuai format tata naskah organisasi (maksimal 50 karakter).
- **Super_Admin**: Admin dengan tingkat akses tertinggi yang dapat mengelola akun pengguna lain.

---

## Requirements

### Requirement 1: Autentikasi Admin

**User Story:** Sebagai Admin, saya ingin dapat masuk dan keluar dari dashboard dengan aman, sehingga pengelolaan data organisasi hanya dapat dilakukan oleh pihak yang berwenang.

#### Acceptance Criteria

1. WHEN Admin mengakses halaman login dan memasukkan email berformat valid serta password yang cocok dengan data di basis data, THE Sistem SHALL mengautentikasi Admin dan mengalihkan ke halaman Dashboard.
2. IF Admin memasukkan email atau password yang tidak cocok dengan data di basis data, THEN THE Sistem SHALL menampilkan pesan kesalahan "Email atau password salah" tanpa merinci kolom mana yang tidak valid, dan tidak mengizinkan akses ke Dashboard.
3. WHEN Admin yang sudah login mengklik tombol logout, THE Sistem SHALL mengakhiri sesi login dan mengalihkan Admin ke halaman login.
4. WHILE Admin belum terautentikasi, THE Sistem SHALL mengalihkan semua permintaan ke halaman-halaman Dashboard ke halaman login.
5. IF pengguna yang belum terautentikasi mencoba mengakses rute manapun di bawah `/admin/*`, THEN THE Sistem SHALL mengalihkan permintaan tersebut ke halaman login.
6. IF Admin gagal login sebanyak 5 kali berturut-turut dalam rentang waktu 10 menit dari alamat IP yang sama, THEN THE Sistem SHALL memblokir percobaan login dari alamat IP tersebut selama 15 menit, menampilkan pesan bahwa akun diblokir sementara, dan menonaktifkan form login selama periode pemblokiran. Counter percobaan login direset setelah login berhasil atau setelah masa blokir 15 menit berakhir.

---

### Requirement 2: Halaman Publik — Beranda dan Profil Organisasi

**User Story:** Sebagai Pengunjung, saya ingin melihat informasi umum tentang SENJA, sehingga saya dapat mengenal organisasi ini sebelum memutuskan untuk bergabung.

#### Acceptance Criteria

1. THE Sistem SHALL menampilkan halaman beranda yang dapat diakses oleh Pengunjung tanpa autentikasi.
2. WHEN Pengunjung mengakses halaman beranda, THE Sistem SHALL menampilkan informasi yang meliputi: nama organisasi, deskripsi organisasi (maksimal 500 karakter), visi, misi, dan daftar Divisi.
3. WHEN Pengunjung mengakses halaman beranda, THE Sistem SHALL menampilkan maksimal 5 (lima) Kegiatan yang berstatus "Akan Datang" atau "Sedang Berlangsung" diurutkan berdasarkan tanggal mulai secara menaik.
4. WHEN Pengunjung mengakses halaman beranda dan terdapat Periode_Kepengurusan yang aktif, THE Sistem SHALL menampilkan maksimal 5 (lima) Proker yang berstatus "Direncanakan" atau "Sedang Berjalan" pada Periode_Kepengurusan tersebut.
5. IF tidak ada Periode_Kepengurusan yang aktif saat Pengunjung mengakses halaman beranda, THEN THE Sistem SHALL menampilkan pesan informasi bahwa belum ada program kerja yang tersedia tanpa menampilkan data Proker.
6. THE Sistem SHALL menyediakan navigasi publik dengan tautan yang masing-masing mengarah ke: halaman beranda, halaman kegiatan, halaman program kerja, dan halaman formulir pendaftaran anggota.

---

### Requirement 3: Pendataan Anggota — Pengelolaan oleh Admin

**User Story:** Sebagai Admin, saya ingin mengelola data seluruh anggota SENJA, sehingga organisasi memiliki catatan keanggotaan yang akurat dan mutakhir.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan halaman daftar anggota di Dashboard yang menampilkan: nama lengkap, NIM, Divisi, dan status keanggotaan setiap Anggota.
2. WHEN Admin mengisi formulir tambah anggota baru dengan semua kolom wajib terisi dan format data valid, THE Sistem SHALL menyimpan data anggota baru ke dalam basis data dan menampilkannya di halaman daftar anggota.
3. IF Admin mengirimkan formulir tambah anggota dengan kolom wajib yang kosong atau format data tidak valid, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi spesifik untuk setiap kolom bermasalah tanpa menghapus data yang sudah diisi, dan tidak menyimpan data ke basis data.
4. IF Admin memasukkan NIM yang sudah terdaftar di basis data Anggota pada formulir tambah anggota, THEN THE Sistem SHALL menampilkan pesan kesalahan bahwa NIM tersebut telah digunakan dan tidak menyimpan data anggota baru.
5. WHEN Admin memperbarui data anggota yang sudah ada dengan data yang valid, THE Sistem SHALL menyimpan perubahan dan menampilkan data terbaru di halaman daftar anggota.
6. WHEN Admin mengubah status keanggotaan seorang Anggota, THE Sistem SHALL memperbarui status Anggota tersebut menjadi salah satu dari nilai yang valid: "Aktif", "Tidak Aktif", atau "Alumni".
7. IF Admin menghapus data anggota, THEN THE Sistem SHALL menampilkan konfirmasi penghapusan sebelum data benar-benar dihapus dari basis data.
8. THE Sistem SHALL menyimpan data setiap Anggota yang meliputi kolom wajib: nama lengkap (maksimal 100 karakter), NIM (unik), email (format valid, unik), nomor telepon, Divisi, angkatan (tahun masuk), tanggal bergabung, dan status keanggotaan; serta kolom opsional: foto profil (format JPG/PNG/WebP, maksimal 2 MB).
9. WHEN Admin melakukan pencarian Anggota berdasarkan nama lengkap atau NIM (pencarian parsial, tidak sensitif huruf besar/kecil), THE Sistem SHALL menampilkan semua hasil pencarian yang mengandung kata kunci tersebut dalam waktu tidak lebih dari 2 detik.
10. WHEN Admin menerapkan filter berdasarkan Divisi atau status keanggotaan, THE Sistem SHALL menampilkan daftar Anggota yang memenuhi semua kriteria filter yang dipilih.
11. THE Sistem SHALL menyediakan fitur ekspor data anggota dalam format Excel (.xlsx) atau CSV yang dapat diunduh oleh Admin.

---

### Requirement 4: Pendaftaran Anggota oleh Pengunjung

**User Story:** Sebagai Pengunjung yang ingin bergabung, saya ingin mengisi formulir pendaftaran anggota secara online, sehingga saya dapat mendaftarkan diri tanpa harus datang langsung ke sekretariat.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan halaman formulir pendaftaran anggota yang dapat diakses oleh Pengunjung tanpa autentikasi.
2. WHEN Pengunjung mengisi dan mengirimkan formulir pendaftaran dengan semua kolom wajib terisi dan format data valid (nama lengkap, NIM, email, nomor telepon, Divisi pilihan, angkatan), THE Sistem SHALL menyimpan data pendaftaran dengan status "Menunggu Verifikasi".
3. IF Pengunjung mengirimkan formulir pendaftaran dengan kolom wajib yang kosong atau format data yang tidak valid, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi yang spesifik untuk setiap kolom bermasalah tanpa menghapus data yang sudah diisi.
4. IF Pengunjung memasukkan NIM yang sudah terdaftar di basis data Anggota atau di daftar pendaftaran yang berstatus "Menunggu Verifikasi", THEN THE Sistem SHALL menampilkan pesan kesalahan bahwa NIM tersebut telah digunakan dan tidak menyimpan data pendaftaran.
5. WHEN Admin menyetujui data pendaftaran yang berstatus "Menunggu Verifikasi", THE Sistem SHALL membuat data Anggota baru dari data pendaftaran tersebut dengan status "Aktif" dan menghapus entri pendaftaran dari daftar tunggu.
6. WHEN Admin menolak data pendaftaran yang berstatus "Menunggu Verifikasi", THE Sistem SHALL menampilkan konfirmasi penolakan, lalu setelah dikonfirmasi menghapus data pendaftaran tersebut secara permanen dari basis data tanpa menyimpan riwayat penolakan.
7. THE Sistem SHALL menampilkan daftar pendaftaran yang berstatus "Menunggu Verifikasi" di halaman Dashboard Admin.

---

### Requirement 5: Pengarsipan Surat — Pengelolaan oleh Admin

**User Story:** Sebagai Admin, saya ingin mengelola arsip surat masuk dan surat keluar secara digital, sehingga seluruh korespondensi organisasi dapat ditemukan dengan mudah dan tidak hilang.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan modul Arsip_Surat di Dashboard Admin yang mencakup pengelolaan Surat_Masuk dan Surat_Keluar secara terpisah.
2. WHEN Admin menambahkan entri Surat_Masuk baru dengan semua kolom wajib terisi, THE Sistem SHALL menyimpan data surat yang meliputi kolom wajib: Nomor_Surat (maksimal 50 karakter), tanggal surat, tanggal diterima, nama pengirim (maksimal 100 karakter), perihal (maksimal 255 karakter), kategori; serta kolom opsional: berkas digital PDF.
3. WHEN Admin menambahkan entri Surat_Keluar baru dengan semua kolom wajib terisi, THE Sistem SHALL menyimpan data surat yang meliputi kolom wajib: Nomor_Surat (maksimal 50 karakter), tanggal surat, tanggal dikirim, nama penerima (maksimal 100 karakter), perihal (maksimal 255 karakter), kategori; serta kolom opsional: berkas digital PDF.
4. IF Admin memasukkan Nomor_Surat yang sudah ada pada jenis surat yang sama (Surat_Masuk atau Surat_Keluar), THEN THE Sistem SHALL menampilkan pesan kesalahan bahwa Nomor_Surat tersebut telah digunakan dan tidak menyimpan data surat tersebut.
5. WHEN Admin melakukan pencarian surat berdasarkan Nomor_Surat, perihal, atau nama pengirim maupun penerima, THE Sistem SHALL menampilkan semua entri surat yang mengandung kata kunci tersebut (pencocokan parsial, tidak sensitif huruf besar/kecil) dalam waktu tidak lebih dari 2 detik.
6. WHEN Admin menerapkan filter Arsip_Surat berdasarkan jenis surat, kategori, atau rentang tanggal, THE Sistem SHALL menampilkan daftar surat yang memenuhi semua kriteria filter yang dipilih.
7. WHEN Admin mengklik entri surat di daftar arsip, THE Sistem SHALL menampilkan detail lengkap surat yang meliputi: Nomor_Surat, tanggal surat, tanggal diterima/dikirim, nama pengirim/penerima, perihal, kategori, dan (jika tersedia) tautan untuk mengunduh berkas PDF.
8. WHEN Admin memperbarui data surat yang sudah diarsipkan dengan Nomor_Surat yang berbeda, THE Sistem SHALL memeriksa keunikan Nomor_Surat baru pada jenis surat yang sama, dan IF Nomor_Surat baru sudah digunakan oleh surat lain, THEN THE Sistem SHALL menampilkan pesan kesalahan dan tidak menyimpan perubahan.
9. WHEN Admin memperbarui data surat yang sudah diarsipkan dengan Nomor_Surat yang lolos validasi keunikan, THE Sistem SHALL menyimpan perubahan dan memperbarui tampilan detail surat.
10. IF Admin menghapus entri surat dari Arsip_Surat, THEN THE Sistem SHALL menampilkan konfirmasi penghapusan sebelum data surat dan berkas terkait dihapus secara permanen.
11. THE Sistem SHALL menerima unggahan berkas surat dalam format PDF dengan ukuran maksimum 10 MB per berkas.
12. THE Sistem SHALL menyediakan pengelolaan kategori surat oleh Admin yang mencakup operasi tambah, ubah, dan hapus kategori; dengan kategori bawaan minimal: "Undangan", "SK (Surat Keputusan)", "Permohonan", "Pemberitahuan", dan "Umum".

---

### Requirement 6: Program Kerja (Proker) — Pengelolaan oleh Admin

**User Story:** Sebagai Admin, saya ingin mengelola daftar program kerja SENJA, sehingga Anggota dan Pengunjung dapat mengetahui rencana kegiatan organisasi secara transparan.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan halaman pengelolaan Proker di Dashboard Admin yang memungkinkan Admin melakukan operasi: tambah Proker baru, ubah data Proker, hapus Proker, dan filter daftar Proker.
2. WHEN Admin menambahkan Proker baru dengan semua kolom wajib terisi dan valid, THE Sistem SHALL menyimpan data yang meliputi kolom wajib: nama proker (maksimal 150 karakter), deskripsi, Divisi penanggung jawab, Periode_Kepengurusan, tanggal mulai estimasi, tanggal selesai estimasi, dan status proker; serta kolom opsional: target peserta (bilangan bulat ≥ 1) dan estimasi anggaran (desimal ≥ 0).
3. IF Admin memasukkan tanggal selesai estimasi yang lebih awal dari tanggal mulai estimasi pada formulir Proker, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi dan tidak menyimpan data Proker tersebut, dengan data form tetap dipertahankan.
4. IF Admin mengirimkan formulir tambah atau ubah Proker dengan kolom wajib yang kosong atau format data tidak valid, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi spesifik untuk setiap kolom bermasalah tanpa menghapus data yang sudah diisi, dan tidak menyimpan data.
5. WHEN Admin mengubah status Proker, THE Sistem SHALL memperbarui status Proker tersebut menjadi salah satu dari nilai yang valid: "Direncanakan", "Sedang Berjalan", "Selesai", atau "Dibatalkan".
6. WHEN Pengunjung mengakses halaman program kerja publik, THE Sistem SHALL menampilkan daftar Proker yang berstatus "Direncanakan" atau "Sedang Berjalan", diurutkan berdasarkan tanggal mulai estimasi secara menaik, beserta informasi Divisi penanggung jawab.
7. WHEN Pengunjung mengakses halaman detail Proker, THE Sistem SHALL menampilkan: nama proker, deskripsi lengkap, Divisi penanggung jawab, tanggal mulai estimasi, dan tanggal selesai estimasi.
8. WHEN Admin menerapkan filter pada daftar Proker berdasarkan Divisi atau Periode_Kepengurusan, THE Sistem SHALL menampilkan Proker yang memenuhi semua kriteria filter yang dipilih.
9. IF Admin mengkonfirmasi penghapusan Proker pada dialog konfirmasi, THEN THE Sistem SHALL menghapus data Proker secara permanen dari basis data dan menghilangkannya dari daftar Proker.
10. WHEN Admin membatalkan penghapusan pada dialog konfirmasi, THE Sistem SHALL membiarkan data Proker tidak berubah dan tetap menampilkannya di daftar Proker.

---

### Requirement 7: Kegiatan — Pengelolaan oleh Admin

**User Story:** Sebagai Admin, saya ingin mengelola informasi kegiatan dan acara SENJA, sehingga Anggota dan Pengunjung dapat mengetahui agenda terbaru organisasi.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan halaman pengelolaan Kegiatan di Dashboard Admin.
2. WHEN Admin menambahkan Kegiatan baru dengan semua kolom wajib terisi dan valid, THE Sistem SHALL menyimpan data yang meliputi kolom wajib: nama kegiatan, deskripsi, tanggal mulai (termasuk jam), tanggal selesai (termasuk jam), lokasi, Divisi penyelenggara, dan status kegiatan; serta kolom opsional: gambar poster.
3. IF Admin memasukkan tanggal selesai Kegiatan yang lebih awal dari tanggal mulai, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi, tidak menyimpan data Kegiatan tersebut, dan mempertahankan data form yang sudah diisi.
4. IF Admin mengirimkan formulir tambah atau ubah Kegiatan dengan kolom wajib yang kosong atau format tidak valid (selain validasi tanggal), THEN THE Sistem SHALL menampilkan pesan kesalahan validasi spesifik per kolom tanpa menghapus data yang sudah diisi, dan tidak menyimpan data.
5. IF Admin mengunggah gambar poster Kegiatan dengan format bukan JPG/PNG/WebP atau ukuran melebihi 5 MB, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi file dan tidak menyimpan data Kegiatan tersebut.
6. WHEN Admin mengubah status Kegiatan, THE Sistem SHALL memperbarui status Kegiatan menjadi salah satu dari nilai yang valid: "Akan Datang", "Sedang Berlangsung", "Selesai", atau "Dibatalkan".
7. IF Admin menghapus Kegiatan, THEN THE Sistem SHALL menampilkan konfirmasi penghapusan, dan setelah dikonfirmasi menghapus data Kegiatan serta semua foto galeri yang terkait secara permanen.
8. WHEN Pengunjung mengakses halaman kegiatan publik, THE Sistem SHALL menampilkan daftar Kegiatan yang diurutkan berdasarkan tanggal mulai secara menaik untuk Kegiatan yang berstatus "Akan Datang" atau "Sedang Berlangsung".
9. WHEN Pengunjung mengakses halaman detail Kegiatan, THE Sistem SHALL menampilkan: nama kegiatan, deskripsi lengkap, tanggal mulai dan tanggal selesai (termasuk jam), lokasi, gambar poster (jika tersedia), dan Divisi penyelenggara.

---

### Requirement 8: Dashboard dan Statistik Admin

**User Story:** Sebagai Admin, saya ingin melihat ringkasan informasi penting organisasi di satu halaman dashboard, sehingga saya dapat memantau kondisi organisasi dengan cepat tanpa harus membuka setiap modul satu per satu.

#### Acceptance Criteria

1. WHEN Admin berhasil login, THE Sistem SHALL mengalihkan Admin ke halaman Dashboard utama.
2. WHEN Admin mengakses halaman Dashboard, THE Sistem SHALL menampilkan kartu statistik yang mencakup: total Anggota dengan status "Aktif", total Surat_Masuk, total Surat_Keluar, dan jumlah Proker dengan status "Direncanakan" atau "Sedang Berjalan".
3. WHEN Admin mengakses halaman Dashboard, THE Sistem SHALL menampilkan hingga 5 (lima) pendaftaran Anggota terbaru yang berstatus "Menunggu Verifikasi" diurutkan berdasarkan tanggal pendaftaran terbaru; jika kurang dari 5 entri tersedia, THE Sistem SHALL menampilkan semua entri yang ada.
4. WHEN Admin mengakses halaman Dashboard, THE Sistem SHALL menampilkan hingga 5 (lima) Kegiatan yang memiliki tanggal mulai paling awal dan lebih besar atau sama dengan tanggal hari ini; jika kurang dari 5 Kegiatan tersedia, THE Sistem SHALL menampilkan semua Kegiatan yang memenuhi kriteria.
5. THE Sistem SHALL menyediakan menu navigasi di Dashboard yang menghubungkan ke seluruh modul: Anggota, Arsip Surat, Program Kerja, dan Kegiatan.

---

### Requirement 9: Pengelolaan Pengguna Admin

**User Story:** Sebagai Super_Admin, saya ingin mengelola akun pengguna yang memiliki akses ke dashboard, sehingga hak akses pengelolaan website dapat dikontrol dengan baik.

#### Acceptance Criteria

1. THE Sistem SHALL mendukung dua tingkat peran pengguna admin: "Super_Admin" dan "Admin".
2. WHEN Super_Admin menambahkan akun Admin baru dengan nama lengkap dan alamat email yang unik di basis data, THE Sistem SHALL membuat akun baru dan mengirimkan email berisi tautan untuk mengatur password ke alamat email yang didaftarkan; tautan tersebut SHALL kedaluwarsa dalam 24 jam.
3. WHEN Super_Admin menonaktifkan akun Admin, THE Sistem SHALL segera mengakhiri semua sesi aktif akun tersebut dan mencabut akses login akun tersebut tanpa menghapus data yang telah dibuat oleh akun Admin tersebut.
4. THE Sistem SHALL membatasi operasi penambahan, penonaktifan, pengaktifan kembali, dan penghapusan akun pengguna admin hanya untuk pengguna dengan peran "Super_Admin".
5. IF Super_Admin mencoba menghapus atau menonaktifkan akun Super_Admin terakhir yang masih aktif, THEN THE Sistem SHALL menampilkan pesan kesalahan dan tidak mengizinkan tindakan tersebut.
6. WHEN Super_Admin mengaktifkan kembali akun Admin yang sebelumnya dinonaktifkan, THE Sistem SHALL memulihkan akses login akun tersebut sehingga Admin dapat kembali masuk ke Dashboard.

---

### Requirement 10: Galeri Foto Kegiatan

**User Story:** Sebagai Admin, saya ingin mengunggah foto dokumentasi dari kegiatan yang sudah berlangsung, sehingga rekam jejak kegiatan SENJA dapat dilihat oleh publik.

#### Acceptance Criteria

1. THE Sistem SHALL menyediakan fitur galeri foto yang dapat dikaitkan dengan sebuah Kegiatan.
2. WHEN Admin mengunggah satu atau beberapa foto ke galeri sebuah Kegiatan dalam format JPG, PNG, atau WebP dengan ukuran tidak melebihi 5 MB per foto, THE Sistem SHALL menyimpan setiap foto dan mengaitkannya dengan Kegiatan yang bersangkutan.
3. IF Admin mengunggah foto dengan format bukan JPG/PNG/WebP atau ukuran melebihi 5 MB, THEN THE Sistem SHALL menampilkan pesan kesalahan validasi file dan tidak menyimpan foto tersebut, sementara foto-foto yang valid dalam unggahan yang sama tetap diproses.
4. WHEN Pengunjung mengakses halaman detail Kegiatan yang memiliki foto galeri, THE Sistem SHALL menampilkan foto-foto tersebut dalam tata letak galeri yang dapat dilihat secara penuh (lightbox).
5. IF Pengunjung mengakses halaman detail Kegiatan yang tidak memiliki foto galeri, THEN THE Sistem SHALL menampilkan pesan atau indikator bahwa belum ada foto yang tersedia, tanpa menampilkan area galeri kosong.
6. WHEN Admin menghapus foto dari galeri sebuah Kegiatan, THE Sistem SHALL menampilkan konfirmasi penghapusan, lalu setelah dikonfirmasi menghapus berkas foto tersebut dari penyimpanan dan menghilangkannya dari tampilan galeri publik.
