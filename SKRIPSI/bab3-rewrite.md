# **BAB III**

## ANALISIS SISTEM

## A. Tinjauan Organisasi

### A.1 Sejarah Singkat Organisasi

[ isi nanti ]

### A.2 Struktur Organisasi

[ isi nanti ]

### A.3 Tugas dan Wewenang

[ isi nanti ]

## B. Metodologi

### B.1 Pengumpulan Data

	Pengumpulan data dilakukan untuk memperoleh informasi yang akurat dan relevan mengenai kondisi sistem E-KAKU V2 serta kebutuhan pengembangan sistem E-KAKU V3. Pengumpulan data dilakukan secara langsung di Mal Pelayanan Publik (MPP) Kabupaten Pandeglang, yang menjadi tempat implementasi sistem pelayanan kartu kuning (AK1) berbasis daring milik Dinas Tenaga Kerja Kabupaten Pandeglang.

	Adapun metode pengumpulan data yang digunakan adalah sebagai berikut:

	1. **B.1.1 Observasi**

	Observasi dilakukan secara langsung di gerai Disnaker pada Mal Pelayanan Publik (MPP) Pandeglang, dengan tujuan mengamati secara nyata bagaimana proses sistem E-KAKU V2 berjalan, baik pada tahap pendaftaran daring maupun pada tahap verifikasi manual dan pencetakan kartu kuning di lokasi pelayanan.

	Kegiatan observasi dilaksanakan berdasarkan kegiatan Praktek Kerja Nyata (PKN) yang telah dilakukan sebelumnya pada bulan Mei-Juni 2025, di mana peneliti berpartisipasi langsung dalam proses pelayanan serta mencatat alur kerja sistem hybrid yang menggabungkan pengisian data daring melalui laman ekaku.pandeglangkab.go.id dan verifikasi tatap muka di MPP.

	Selama observasi, peneliti mengamati secara detail alur kerja petugas MPP dalam melayani pencari kerja, mulai dari penerimaan berkas fisik (KTP, ijazah, pas foto), proses input data ke dalam sistem, verifikasi kelengkapan dokumen, hingga pencetakan Kartu Kuning secara fisik. Peneliti juga mengamati bahwa pencari kerja yang telah mengisi biodata secara daring tetap harus datang ke MPP untuk menyerahkan dokumen fisik dan mengambil Kartu Kuning yang telah dicetak, yang menunjukkan bahwa sistem masih bersifat hybrid dan belum terintegrasi sepenuhnya.

	Hasil pengamatan menunjukkan beberapa kendala pada sistem E-KAKU V2, antara lain proses verifikasi dokumen yang masih dilakukan secara manual oleh petugas, belum adanya mekanisme notifikasi otomatis yang memberitahukan pencari kerja mengenai status verifikasi data mereka, serta pencari kerja tidak memiliki cara untuk mengetahui perkembangan pengajuan mereka tanpa harus datang langsung ke MPP. Kendala-kendala ini menjadi dasar utama dalam merumuskan kebutuhan perancangan sistem baru E-KAKU V3 yang lebih efisien dan terintegrasi penuh.

	2. **B.1.2 Wawancara**

	Wawancara dilakukan dengan Bapak Gunawan (Pak Gugum) selaku Manager On Demand (MOD) di Mal Pelayanan Publik (MPP) Kabupaten Pandeglang. Beliau merupakan pengelola operasional yang memahami sistem pelayanan lintas instansi, termasuk alur kerja sistem E-KAKU V2 yang dijalankan di gerai Dinas Tenaga Kerja.

	Diskusi dengan Bapak Gunawan dilakukan untuk menggali informasi secara mendalam terkait kendala teknis dan administratif yang muncul pada sistem lama, seperti keterbatasan integrasi data antara MPP dan Disnaker, proses verifikasi manual yang memperlambat pelayanan, serta kebutuhan fitur baru seperti notifikasi otomatis dan monitoring data secara real-time.

	Selain itu, wawancara juga berfokus pada harapan pengelola MPP terhadap sistem digital ke depan, khususnya dalam konteks peningkatan efisiensi dan transparansi pelayanan publik. Bapak Gunawan menekankan pentingnya sistem yang mampu mengotomatisasi alur pemberitahuan status kepada pencari kerja sehingga mereka tidak perlu datang berkali-kali untuk mengecek perkembangan pengajuan. Hasil wawancara menjadi masukan utama dalam merancang fitur-fitur inti sistem E-KAKU V3, seperti Smart Reminder, Dashboard Monitoring, dan mekanisme notifikasi otomatis.

	3. **B.1.3 Studi Literatur**

	Metode studi literatur dilakukan dengan mengumpulkan dan menelaah berbagai sumber ilmiah yang relevan sebagai landasan konseptual pengembangan sistem. Literatur yang digunakan meliputi:

	a. Buku dan jurnal terkait sistem informasi publik, rekayasa perangkat lunak, metode Prototyping, serta keamanan data dan verifikasi digital;

	b. Penelitian terdahulu yang membahas digitalisasi layanan publik berbasis web di lingkungan pemerintahan daerah;

	c. Dokumen internal dan laporan hasil PKN yang telah dilakukan di MPP Pandeglang sebagai referensi empiris mengenai implementasi sistem E-KAKU V2.

	Hasil studi literatur digunakan untuk memperkuat dasar teoritis penelitian, memvalidasi hasil observasi dan wawancara, serta memberikan arahan dalam penerapan metode pengembangan sistem yang tepat bagi E-KAKU V3.

### B.2 Metode Penyelesaian Masalah

	Metode penyelesaian masalah yang digunakan dalam penelitian ini adalah Metode Analisis Sistem. Metode ini dipilih karena mampu membantu penulis dalam memahami sistem yang sedang berjalan di Dinas Tenaga Kerja Kabupaten Pandeglang melalui Mal Pelayanan Publik (MPP), mengidentifikasi permasalahan yang muncul, serta merumuskan solusi yang tepat melalui pengembangan sistem berbasis web. Dengan pendekatan ini, proses analisis dilakukan secara sistematis agar solusi yang dihasilkan benar-benar sesuai dengan kebutuhan pengguna dan kondisi operasional di lapangan.

	Langkah-langkah analisis sistem dimulai dengan mengamati sistem yang berjalan melalui kegiatan observasi dan wawancara dengan Manager On Demand (MOD) di MPP Pandeglang untuk memahami alur kerja pendaftaran dan penerbitan Kartu Kuning. Selanjutnya dilakukan identifikasi permasalahan utama berdasarkan temuan di lapangan, seperti proses verifikasi yang masih manual, belum adanya mekanisme notifikasi otomatis, serta keterbatasan fitur monitoring data.

	Setelah permasalahan teridentifikasi, langkah selanjutnya adalah analisis kebutuhan sistem baru yang mampu mengatasi kendala tersebut, yaitu melalui pengembangan sistem pendaftaran dan penerbitan Kartu Kuning berbasis web menggunakan framework Laravel dengan arsitektur Model-View-Controller (MVC) dan basis data MySQL. Sistem dirancang untuk mengintegrasikan seluruh proses pelayanan dari pendaftaran daring, verifikasi dokumen, notifikasi status, hingga pencetakan Kartu Kuning dalam satu platform digital.

	Hasil dari metode analisis sistem ini menjadi dasar dalam proses perancangan dan pengembangan sistem E-KAKU V3 yang lebih efisien, terintegrasi, serta mampu meningkatkan efektivitas pelayanan Kartu Kuning di Kabupaten Pandeglang.

### B.3 Metode Pengembangan Sistem

	Metode pengembangan sistem merupakan tahapan penting yang digunakan untuk merancang dan membangun sistem informasi secara terarah, terstruktur, dan sesuai dengan kebutuhan pengguna. Dalam penelitian ini, metode yang digunakan adalah Metode Prototyping, yaitu suatu pendekatan pengembangan sistem yang bersifat iteratif dan adaptif. Metode ini menekankan pada keterlibatan pengguna secara langsung dalam setiap tahap perancangan agar sistem yang dihasilkan benar-benar sesuai dengan kebutuhan di lapangan.

#### B.3.1 Alasan Pemilihan Metode Prototyping

	Metode Prototyping dipilih karena memiliki keunggulan dalam hal fleksibilitas dan kecepatan proses pengembangan. Dalam konteks penelitian ini, sistem E-KAKU V3 dirancang untuk menggantikan sistem sebelumnya (E-KAKU V2) yang masih bersifat manual-hybrid dan belum memiliki fitur otomatisasi yang memadai. Oleh karena itu, dibutuhkan metode pengembangan yang mampu menyesuaikan rancangan sistem dengan kondisi nyata di lapangan melalui proses komunikasi berkelanjutan antara peneliti dan pengguna sistem.

	Proses pengembangan sistem E-KAKU V3 melibatkan Manager On Demand (MOD) Mal Pelayanan Publik Pandeglang sebagai pihak yang memahami mekanisme operasional pelayanan kartu kuning. Melalui metode ini, peneliti dapat mengembangkan prototype awal, kemudian melakukan evaluasi langsung dengan pengguna untuk mendapatkan umpan balik terhadap rancangan sistem yang dibuat. Proses evaluasi yang dilakukan secara bertahap ini memungkinkan peneliti untuk melakukan perbaikan secepat mungkin tanpa harus menunggu seluruh sistem selesai dibangun.

	Selain itu, metode Prototyping juga memberikan keuntungan dalam hal efisiensi waktu dan biaya. Karena penelitian ini dilakukan dengan skala instansi pelayanan publik, diperlukan pendekatan yang mampu menyeimbangkan kecepatan pengembangan dengan keakuratan kebutuhan sistem. Setiap iterasi dari prototype yang dibuat berfungsi untuk memvalidasi rancangan, sehingga kesalahan dapat dideteksi lebih awal sebelum tahap implementasi akhir dilakukan. Dengan demikian, Prototyping menjadi metode yang paling sesuai untuk penelitian ini karena mampu menghasilkan sistem yang relevan, mudah diadaptasi, dan siap diuji langsung oleh pengguna di lapangan.

#### B.3.2 Tahapan Pengembangan Sistem

	Metode Prototyping dilaksanakan melalui beberapa tahapan yang saling berhubungan dan dilakukan secara berulang (iteratif) hingga menghasilkan sistem akhir yang memenuhi kebutuhan pengguna. Adapun tahapan pengembangan sistem yang diterapkan dalam penelitian ini adalah sebagai berikut:

	1. **B.3.2.1 Pengumpulan Kebutuhan (Requirement Gathering)**

	Tahap awal ini dilakukan untuk mengidentifikasi kebutuhan pengguna dan sistem yang akan dibangun. Pengumpulan data dilakukan melalui observasi langsung dan wawancara dengan Manager On Demand (MOD) di MPP Pandeglang. Informasi yang diperoleh meliputi alur pelayanan kartu kuning, kendala yang terjadi pada sistem lama, serta kebutuhan fitur baru seperti pendaftaran daring terintegrasi, notifikasi otomatis, dan dashboard monitoring. Hasil dari tahap ini berupa daftar kebutuhan sistem (requirement list) yang akan digunakan sebagai dasar penyusunan rancangan awal prototype.

	2. **B.3.2.2 Pembuatan Prototype (Build Prototype)**

	Berdasarkan kebutuhan yang telah diidentifikasi, peneliti merancang model awal sistem (prototype). Model ini terdiri dari rancangan antarmuka pengguna (user interface), struktur menu utama, dan alur proses pendaftaran hingga verifikasi data. Pembuatan prototype dilakukan menggunakan Framework Laravel sebagai platform pengembangan berbasis web karena mendukung struktur Model-View-Controller (MVC) dan integrasi dengan basis data MySQL. Tahap ini menghasilkan model visual sistem yang dapat diakses oleh pengguna untuk memberikan gambaran awal terhadap fungsi dan tampilan sistem.

	3. **B.3.2.3 Evaluasi Prototype (Evaluation by User)**

	Prototype yang telah dibuat diuji langsung oleh pihak pengguna, yaitu pegawai dan pengelola MPP Pandeglang. Evaluasi dilakukan dengan mengamati fungsionalitas sistem, kecepatan proses, dan kemudahan penggunaan antarmuka. Hasil evaluasi berupa masukan mengenai bagian sistem yang perlu diperbaiki, baik dari sisi tampilan maupun alur kerja. Keterlibatan langsung pengguna dalam tahap ini menjadi keunggulan utama metode Prototyping karena peneliti dapat menyesuaikan sistem dengan kebutuhan riil pelayanan publik.

	4. **B.3.2.4 Penyempurnaan Prototype (Refinement)**

	Tahap ini merupakan proses penyempurnaan berdasarkan hasil evaluasi pengguna. Beberapa perbaikan dilakukan seperti penambahan fitur Smart Reminder untuk pengingat pembaruan status, Dashboard Monitoring untuk memantau aktivitas pengguna, serta notifikasi otomatis untuk memberitahukan pencari kerja mengenai status verifikasi. Proses penyempurnaan dilakukan secara bertahap hingga sistem mencapai tingkat kesesuaian tinggi terhadap kebutuhan operasional di MPP Pandeglang.

	5. **B.3.2.5 Implementasi dan Pengujian (Implementation and Testing)**

	Tahap terakhir dilakukan setelah sistem dianggap stabil dan sesuai dengan kebutuhan. Prototype akhir kemudian diimplementasikan menjadi sistem yang siap digunakan. Pengujian dilakukan untuk memastikan seluruh fungsi berjalan dengan baik, mulai dari proses login, pendaftaran, unggah dokumen, verifikasi digital, hingga pencetakan kartu. Uji coba dilakukan bersama pihak MPP Pandeglang untuk menilai performa sistem dalam kondisi pelayanan sesungguhnya serta memastikan sistem dapat dioperasikan secara efektif oleh petugas dan pengguna.

#### B.3.3 Hasil Penerapan Metode Prototyping

	Penerapan metode Prototyping pada penelitian ini menghasilkan sistem E-KAKU V3 berbasis web yang telah disesuaikan dengan kebutuhan lapangan. Sistem ini memiliki alur pelayanan yang lebih efisien, fitur verifikasi digital yang aman, serta kemampuan pengingat status pencari kerja secara otomatis. Proses pengembangan yang iteratif memungkinkan sistem diuji dan diperbaiki secara terus-menerus sehingga hasil akhirnya lebih akurat dan siap diterapkan.

	Metode ini terbukti efektif dalam konteks pelayanan publik karena mampu mengakomodasi perubahan kebutuhan pengguna selama proses pengembangan tanpa mengganggu jalannya penelitian. Dengan demikian, metode Prototyping tidak hanya berfungsi sebagai teknik pengembangan, tetapi juga sebagai pendekatan adaptif untuk memastikan sistem yang dihasilkan benar-benar memenuhi kebutuhan instansi terkait.

## C. Analisis Sistem Berjalan

	Analisis sistem berjalan dilakukan untuk mengetahui kondisi aktual sistem EKAKU V2 yang diterapkan oleh Dinas Tenaga Kerja Kabupaten Pandeglang dalam pelayanan Kartu Kuning (AK/1). Berdasarkan hasil observasi dan wawancara di Mal Pelayanan Publik (MPP) Pandeglang, sistem yang digunakan masih bersifat hybrid, di mana proses pendaftaran dilakukan secara daring melalui situs ekaku.pandeglangkab.go.id, namun verifikasi dokumen dan pencetakan kartu masih harus dilakukan secara langsung di lokasi pelayanan.

	Kondisi ini menunjukkan bahwa sistem yang berjalan belum terintegrasi sepenuhnya karena kurangnya otomatisasi pada tahap verifikasi dan pemberitahuan status kepada pencari kerja. Selain itu, belum adanya fitur monitoring data dan notifikasi otomatis menyebabkan pencari kerja harus datang langsung ke MPP untuk mengecek perkembangan pengajuan mereka, yang mengurangi efisiensi pelayanan.

### C.1 Analisis Masukan

	Masukan yang dihasilkan dari sistem berjalan adalah sebagai berikut:

<!-- TABLE_C1 -->
| Nama Masukan | : | Data Biodata Pencari Kerja |
| Fungsi | : | Mencatat informasi data diri pencari kerja meliputi NIK, nama lengkap, tanggal lahir, jenis kelamin, alamat, riwayat pendidikan, serta keterampilan yang dimiliki. |
| Media | : | Form daring yang tersedia pada situs ekaku.pandeglangkab.go.id, dapat diakses oleh pencari kerja melalui peramban web kapan saja dan dari mana saja selama terhubung dengan jaringan internet. |
| Hasil Analisis | : | Pencari kerja sudah dapat mengisi biodata secara daring tanpa harus datang ke kantor Disnaker. Namun, proses verifikasi data masih dilakukan secara manual oleh petugas di MPP, sehingga memerlukan kehadiran pencari kerja untuk menyerahkan dokumen fisik guna pencocokan data. |

| Nama Masukan | : | Dokumen Pendukung |
| Fungsi | : | Menyimpan berkas persyaratan penerbitan Kartu Kuning, meliputi pas foto dengan background merah, scan KTP, scan Ijazah terakhir,sertifikat tambahan (opsional). |
| Media | : | Fitur unggah dokumen daring pada situs ekaku.pandeglangkab.go.id yang mendukung format JPG, JPEG, PNG, dan PDF dengan ukuran maksimal 2 MB per berkas. |
| Hasil Analisis | : | Berkas sudah diunggah secara daring melalui situs EKAKU V2, sehingga pencari kerja tidak perlu membawa salinan fisik berkas saat pendaftaran. Akan tetapi, proses verifikasi keabsahan dokumen masih memerlukan kehadiran pencari kerja di MPP untuk penyerahan dan pencocokan dokumen fisik oleh petugas. |

| Nama Masukan | : | Data Pendaftaran Akun |
| Fungsi | : | Pendaftaran awal pencari kerja untuk mendapatkan akun guna mengakses sistem EKAKU V2, meliputi nama, alamat email, dan password. |
| Media | : | Halaman pendaftaran akun yang tersedia pada situs ekaku.pandeglangkab.go.id, memungkinkan pencari kerja membuat akun baru sebelum mengakses fitur pengisian biodata. |
| Hasil Analisis | : | Pendaftaran akun berhasil dilakukan secara daring dan pencari kerja dapat login ke dalam sistem. Namun, status verifikasi akun belum terintegrasi dengan alur pelayanan Kartu Kuning, sehingga pencari kerja masih harus datang ke MPP untuk melanjutkan proses verifikasi dan pencetakan kartu secara manual. |

### C.2 Analisis Keluaran

	Keluaran yang dihasilkan dari sistem yang sedang berjalan adalah sebagai berikut:

<!-- TABLE_C2 -->
| Nama Keluaran | : | Kartu Kuning (AK/1) |
| Fungsi | : | Sebagai dokumen resmi bukti pendaftaran pencari kerja yang dikeluarkan oleh Dinas Tenaga Kerja, yang memuat data identitas, riwayat pendidikan, dan keterangan pencari kerja. |
| Media | : | Pencetakan dilakukan secara fisik oleh petugas di loket MPP atau kantor Dinas Tenaga Kerja menggunakan printer yang tersedia, setelah seluruh proses verifikasi dokumen selesai. |
| Hasil Analisis | : | Kartu Kuning dicetak secara fisik oleh petugas setelah proses verifikasi dokumen selesai dilakukan. Pencari kerja harus datang langsung ke MPP untuk mengambil Kartu Kuning yang telah dicetak. Belum ada opsi download digital atau pencetakan mandiri oleh pencari kerja, sehingga ketersediaan kartu sangat bergantung pada kehadiran fisik pencari kerja di loket pelayanan. |

| Nama Keluaran | : | Data Pencari Kerja (Database) |
| Fungsi | : | Sebagai arsip digital data pencari kerja untuk keperluan verifikasi, pencatatan, dan pelaporan kepada instansi terkait. |
| Media | : | Database sistem EKAKU V2 yang tersimpan pada server Dinas Tenaga Kerja, memuat seluruh data pencari kerja yang telah mendaftar dan dapat diakses oleh petugas untuk keperluan verifikasi serta pelaporan. |
| Hasil Analisis | : | Data pencari kerja tersimpan dalam database sistem dan dapat diakses oleh petugas untuk keperluan verifikasi dan pelaporan. Namun, data ini belum terintegrasi dengan mekanisme notifikasi otomatis, sehingga pencari kerja tidak mendapatkan pemberitahuan mengenai status verifikasi pengajuan mereka secara otomatis. |

| Nama Keluaran | : | Status Verifikasi Pengajuan |
| Fungsi | : | Menyampaikan informasi hasil verifikasi dokumen pencari kerja kepada petugas untuk keperluan pengambilan keputusan penerbitan Kartu Kuning. |
| Media | : | Catatan manual oleh petugas di MPP yang tidak terintegrasi dengan sistem notifikasi digital, sehingga informasi status hanya dapat diketahui oleh petugas yang menangani langsung. |
| Hasil Analisis | : | Status verifikasi hanya diketahui oleh petugas yang menangani proses verifikasi di MPP. Pencari kerja tidak memiliki cara untuk mengetahui status pengajuan mereka secara daring dan harus datang langsung ke MPP untuk mengecek perkembangannya. Keterbatasan ini menyebabkan pencari kerja sering kali harus melakukan kunjungan berulang yang tidak perlu. |

## D. Analisis Kebutuhan

### D.1 Kebutuhan Organisasi

	Berdasarkan analisis terhadap sistem EKAKU V2 yang sedang berjalan, teridentifikasi beberapa kebutuhan organisasi yang perlu dipenuhi:

	1. **Verifikasi Dokumen yang Lebih Efisien** - Proses verifikasi saat ini masih dilakukan secara manual oleh petugas, yang membutuhkan waktu dan tenaga kerja yang cukup besar. Diperlukan sistem yang mampu melakukan verifikasi awal secara otomatis untuk mengurangi beban kerja petugas.

	2. **Penerbitan Kartu Kuning dengan QR Code** - Kartu Kuning yang dicetak saat ini belum dilengkapi dengan fitur keamanan digital seperti QR code. Diperlukan mekanisme verifikasi digital yang dapat memastikan keabsahan dokumen.

	3. **Notifikasi dan Pengingat Otomatis** - Belum adanya sistem notifikasi yang mengingatkan pencari kerja mengenai status verifikasi, jadwal pengambilan Kartu Kuning, atau pembaruan data yang diperlukan.

	4. **Dashboard Monitoring** - Diperlukan tampilan ringkas yang menampilkan data pendaftaran, status verifikasi, serta statistik penerbitan Kartu Kuning secara real-time untuk mendukung pengambilan keputusan oleh pimpinan.

	5. **Laporan dan Rekapitulasi** - Sistem harus mampu menghasilkan laporan secara otomatis dalam berbagai format untuk mendukung pelaporan kepada instansi terkait.

### D.2 Output yang Diharapkan dari Sistem

	Sistem EKAKU V3 yang dikembangkan diharapkan mampu menghasilkan output sebagai berikut:

	1. **Kartu Kuning Digital dengan QR Code** - Kartu Kuning yang dilengkapi kode QR sebagai mekanisme verifikasi digital, sehingga keabsahan dokumen dapat diverifikasi secara cepat dan akurat.

	2. **Dashboard Monitoring Real-Time** - Tampilan visual yang menampilkan data pendaftaran, status verifikasi, serta statistik penerbitan Kartu Kuning secara langsung.

	3. **Notifikasi Otomatis** - Sistem pengingat yang secara otomatis memberitahukan pencari kerja mengenai status verifikasi, jadwal pengambilan, serta pembaruan data.

	4. **Download Kartu Kuning PDF** - Fitur yang memungkinkan pencari kerja mengunduh Kartu Kuning dalam format PDF setelah proses verifikasi selesai.

	5. **Laporan Administrasi** - Sistem yang mampu menghasilkan laporan dalam format CSV atau PDF untuk keperluan administrasi dan pelaporan.

## E. Analisis Proses

### E.1 Urutan Prosedur

	Urutan prosedur ini menjelaskan langkah-langkah aktivitas yang terjadi dalam proses penerbitan Kartu Kuning (AK/1) pada sistem EKAKU V2 yang diterapkan oleh Dinas Tenaga Kerja Kabupaten Pandeglang. Proses ini melibatkan dua aktor utama, yaitu pencari kerja dan petugas Disnaker, serta dimulai dari tahap pendaftaran daring hingga pencetakan dan pengambilan Kartu Kuning secara fisik di Mal Pelayanan Publik (MPP). Berikut adalah urutan prosedur sistem berjalan saat ini:

![Gambar 3.1 Flowmap Urutan Prosedur](C:/Users/chiyo/Desktop/PROSKIP%20ALBAR/ekaku-v3/SKRIPSI/uml-new/FlowMap.drawio.png)

	1. **Pencari Kerja**
		a. Mengakses situs web EKAKU V2 melalui peramban.
		b. Membuat akun baru dengan mengisi nama, alamat email, dan password.
		c. Login ke dalam sistem menggunakan akun yang telah dibuat.
		d. Mengisi biodata lengkap meliputi NIK, tanggal lahir, jenis kelamin, alamat, riwayat pendidikan, serta keterampilan.
		e. Mengunggah dokumen pendukung berupa pas foto dengan background merah, scan KTP, scan Ijazah terakhir,sertifikat tambahan (opsional).
		f. Mengirimkan permohonan pendaftaran Kartu Kuning.
		g. Mengambil Kartu Kuning yang telah terverifikasi dan terlegalisasi di loket MPP atau kantor Dinas Tenaga Kerja.

	2. **Petugas Disnaker**
		a. Menerima pengajuan pendaftaran Kartu Kuning dari pencari kerja.
		b. Memeriksa kelengkapan dokumen yang telah diunggah oleh pencari kerja.
		c. Melakukan verifikasi keabsahan dokumen fisik yang diserahkan oleh pencari kerja.
		d. Mencetak Kartu Kuning menggunakan printer yang tersedia di loket MPP atau kantor Dinas Tenaga Kerja.
		e. Menandatangani dan memberikan cap resmi pada Kartu Kuning yang telah dicetak.
		f. Menyerahkan Kartu Kuning kepada pencari kerja.

### E.2 UML Sistem Berjalan

	Modelling sistem EKAKU V2 menggunakan Unified Modelling Language (UML) untuk menggambarkan interaksi antara aktor dan sistem serta alur aktivitas dalam proses penerbitan Kartu Kuning.

### E.2.1 Use Case Diagram

	Use Case Diagram menggambarkan interaksi antara pengguna (aktor) dan sistem EKAKU V2. Berdasarkan analisis, terdapat dua aktor utama:

	1. **Pencari Kerja** - Mengakses situs EKAKU V2 untuk membuat akun, login, mengisi biodata, mengunggah dokumen, mengirim permohonan, serta mengambil Kartu Kuning yang telah terverifikasi.

	2. **Petugas Disnaker** - Memeriksa kelengkapan dokumen, memverifikasi keabsahan dokumen, mencetak Kartu Kuning, serta menyerahkan Kartu Kuning kepada pencari kerja.

![Gambar 3.1 Use Case Diagram Sistem E-KAKU V2](C:/Users/chiyo/Desktop/PROSKIP%20ALBAR/ekaku-v3/SKRIPSI/uml-new/UseCase-V2.drawio.png)

### E.2.2 Activity Diagram

	Activity Diagram menggambarkan alur aktivitas dalam sistem EKAKU V2 untuk proses penerbitan Kartu Kuning dengan pembagian peran menggunakan swimlane.

	1. **Pencari Kerja Membuat Akun dan Login**:

![Gambar 3.2 Activity Diagram Membuat Akun dan Login](C:/Users/chiyo/Desktop/PROSKIP%20ALBAR/ekaku-v3/SKRIPSI/uml-new/Login.drawio.png)

	2. **Pencari Kerja Mengisi Biodata dan Upload Dokumen**:

![Gambar 3.3 Activity Diagram Mengisi Biodata dan Upload Dokumen](C:/Users/chiyo/Desktop/PROSKIP%20ALBAR/ekaku-v3/SKRIPSI/uml-new/Upload.drawio.png)

	3. **Petugas Disnaker Memverifikasi Pengajuan**:

![Gambar 3.4 Activity Diagram Memverifikasi Pengajuan](C:/Users/chiyo/Desktop/PROSKIP%20ALBAR/ekaku-v3/SKRIPSI/uml-new/Pengajuan.drawio.png)
