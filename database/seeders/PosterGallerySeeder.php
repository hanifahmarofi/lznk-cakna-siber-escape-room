<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosterGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posters = [
            [
                'title' => 'Ancaman Pengintip Siber: Panggilan Senyap',
                'image_path' => 'unnamed (1).jpg', // Pastikan nama fail sejajar dengan fail di folder storage anda
                'description' => "Dalam era digital hari ini, ancaman siber tidak hanya tertumpu pada penggodaman sistem yang kompleks, tetapi juga mensasarkan privasi peribadi secara terus melalui taktik panggilan senyap. Poster ini memberi amaran visual yang jelas bahawa penjenayah siber sentiasa memerhatikan gerak-geri anda di alam maya, walaupun anda tidak menyedari kehadiran mereka di sebalik skrin. Mereka berselindung dalam bayangan untuk mengeksploitasi kelalaian mangsa.\n\nTaktik panggilan senyap atau 'silent calls' sering digunakan oleh penggodam untuk mengesahkan sama ada nombor telefon anda masih aktif, atau lebih teruk lagi, merekodkan sebutan suara anda untuk tujuan penipuan identiti menggunakan teknologi kecerdasan buatan (AI Deepfake). Apabila anda menjawab panggilan yang tidak dikenali dan tiada suara di hujung talian, anda secara tidak langsung telah mendedahkan privasi anda kepada pihak pengintip.\n\nOleh itu, seluruh warga kerja dan masyarakat dinasihatkan agar sentiasa berwaspada dan memupuk sikap keraguan yang tinggi apabila menerima panggilan daripada nombor antarabangsa atau yang tidak dikenali. Jangan sesekali menjawab panggillan rawak dan manfaatkan ciri sekatan (block) pada telefon pintar anda demi memastikan privasi diri kekal terpelihara.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Perangkap Jualan Hujung Tahun',
                'image_path' => 'Gemini_Generated_Image_wgufspwgufspwguf.png',
                'description' => "Musim jualan hujung tahun sentiasa menjadi detik yang dinantikan oleh para pembeli untuk mendapatkan tawaran harga terendah. Namun begitu, kemeriahan ini turut membuka peluang keemasan kepada penjenayah siber untuk merancang penipuan berskala besar. Poster ini mengetengahkan realiti pahit di mana tawaran diskaun yang kelihatan terlalu murah sering kali menyembunyikan 'mata kail' yang bakal merugikan pengguna.\n\nModus operandi penipuan ini biasanya melibatkan taktik 'Phishing' di mana scammer menyiarkan iklan palsu berserta pautan yang membawa mangsa ke laman web e-dagang tiruan. Apabila pembeli memasukkan butiran kad kredit untuk menikmati potongan harga 70% tersebut, mereka secara tidak sedar telah melanggan caj pembaharuan automatik (auto-renewal hidden charge) yang diikat secara tersembunyi tanpa persetujuan telus.\n\nPengguna dinasihatkan untuk tidak mudah gelap mata dengan promosi luar biasa yang dihantar melalui e-mel, WhatsApp, atau media sosial. Sentiasa semak kesahihan url laman web sebelum membuat sebarang pembayaran, baca terma dan syarat dengan teliti, dan jangan biarkan niat untuk berjimat hari ini bertukar menjadi beban hutang kad kredit sepanjang tahun hadapan.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Ancaman Perisian Hasad Tersembunyi (Trojan)',
                'image_path' => 'Gemini_Generated_Image_c31468c31468c314.png',
                'description' => "Dalam dunia lambakan aplikasi mudah alih, tidak semua perisian yang kelihatan comel dan berguna itu selamat untuk dimuat turun. Poster ini mengilustrasikan konsep serangan 'Trojan Horse' moden, di mana penjenayah siber menggunakan antaramuka yang tidak berbahaya, seperti aplikasi pembersih memori (Cleaner App), untuk menyembunyikan entiti digital yang berniat jahat di belakang tadbir.\n\nAplikasi palsu ini beroperasi di bawah radar pengguna melalui teknik 'Silent Scam'. Sebaik sahaja kebenaran (permissions) diberikan oleh pengguna semasa proses pemasangan, aplikasi ini akan mula menyedut data peribadi, membaca kata laluan atau mesej OTP, dan memindahkan maklumat sensitif ke pelayan penggodam tanpa sebarang amaran. Pengguna sering kali hanya menyedari mereka digodam apabila wang di dalam akaun bank telah lesap.\n\nSebagai langkah pencegahan yang kritikal, pastikan anda hanya memuat turun aplikasi daripada gedung rasmi yang disahkan seperti Google Play Store atau Apple App Store. Jangan sesekali memuat turun fail berformat .APK melalui pautan rawak di platform permesejan, dan sentiasa semak senarai kebenaran yang diminta oleh aplikasi sebelum menekan butang setuju.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Keselamatan Siber Bermula Dari Rumah',
                'image_path' => 'image_62278b.jpg',
                'description' => "Ibu bapa memainkan peranan yang sangat penting sebagai benteng pertahanan pertama dalam mendidik generasi muda mengenai dunia digital. Poster bersempena Hari Ibu ini mengangkat darjat seorang ibu bukan sahaja sebagai pelindung fizikal, tetapi juga sebagai pendidik utama yang membimbing anak-anak dalam menavigasi ancaman keselamatan siber. Asas kesedaran siber yang kukuh bermula dari ruang tamu rumah kita sendiri.\n\nPoster ini menekankan empat rukun utama yang perlu diterapkan kepada kanak-kanak: menjaga maklumat peribadi daripada dikongsi sewenang-wenangnya, membina kata laluan yang kuat dan rahsia, memupuk amalan berfikir secara kritis sebelum menekan sebarang pautan mencurigakan, dan menggalakkan anak-anak untuk segera melapor kepada ibu bapa jika berlaku insiden pelik dalam talian. Taktik 'grooming' dan manipulasi siber boleh dipatahkan jika anak-anak mempunyai panduan ini.\n\nSempena meraikan pengorbanan ibu, mesej ini mengajak seluruh komuniti untuk menjadikan literasi digital sebagai sebahagian daripada silibus didikan keluarga. Layari internet dengan selamat, sentiasa pantau aktiviti anak-anak di dunia maya, dan teguhkan kerjasama bersama Divisyen Keselamatan Siber untuk memastikan masa depan digital mereka terjamin.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Inspirasi Guru: Etika Dunia Siber',
                'image_path' => 'POSTER CYBER HARI GURU_aisyah_V4.jpg',
                'description' => "Institusi pendidikan merupakan medan utama dalam melahirkan individu yang celik teknologi dan berintegriti. Poster edisi khas Hari Guru LZNK ini didedikasikan kepada para pendidik yang sabar mencurahkan ilmu, menegaskan bahawa guru yang berinspirasi mampu membentuk sebuah dunia maya yang lebih selamat. Mereka membimbing murid untuk membawa nilai-nilai moral dari bilik darjah ke dalam ruang siber.\n\nArahan visual dalam poster ini menterjemahkan nasihat guru kepada empat amalan keselamatan digital yang praktikal. Antaranya ialah menyemak kesahihan sesuatu maklumat sebelum klik dan kongsi, disiplin menjaga kerahsiaan kata laluan, kebertanggungjawaban dalam mengendalikan data peribadi milik diri dan orang lain, serta kejujuran dalam membanteras penyebaran berita palsu mahupun 'scam' penipuan dalam talian.\n\nMelalui hashtag #GuruCelikSiber dan #MuridTerpelihara, LZNK menyeru agar nilai amanah dan tanggungjawab diterapkan seawal bangku sekolah. Ilmu keselamatan siber yang dikongsikan oleh para guru hari ini merupakan pelaburan jangka panjang yang akan menjejaki murid hingga ke alam dewasa, membina sebuah ekosistem digital Malaysia yang kebal dan beretika.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Waspada Penipuan Derma & Ibadah Korban',
                'image_path' => 'POSTER CYBER HARI RAYA HAJI_aisyah_V3.jpg',
                'description' => "Kedatangan bulan Zulhijjah dan perayaan Hari Raya Aidiladha sering kali menyuburkan semangat umat Islam untuk bersedekah dan melaksanakan ibadah korban. Malang sekali, keluhuran niat menderma ini kerap dimanipulasi oleh sindiket penipuan siber yang mengambil kesempatan terhadap kemurahan hati masyarakat. Poster ini merupakan peringatan penting agar niat murni tidak disalahgunakan oleh pihak 'scammer'.\n\nTiga modus operandi utama yang sering berlaku semasa musim perayaan ini termasuklah penyebaran pautan derma palsu (Fake Donation Link), penggunaan kod QR penipuan yang mengalihkan dana terus ke akaun penjenayah (QR Scam), serta pembinaan laman web ibadah korban tiruan yang meniru rekabentuk portal rasmi agensi Islam. Taktik ini direka agar mangsa percaya mereka sedang berurusan dengan saluran yang sah.\n\nBagi mengelakkan kerugian, masyarakat dituntut untuk mengamalkan prinsip 'Semak Dahulu, Baru Derma'. Pastikan laman web bermula dengan 'https://', kenal pasti nama organisasi yang berdaftar, dan lakukan sumbangan hanya melalui platform rasmi seperti portal Sadaqah4U LZNK. Simpan segala bukti transaksi dengan kemas supaya ibadah yang dilakukan mendapat keberkatan dan hati kekal tenang.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('info_galleries')->insert($posters);
    }
}