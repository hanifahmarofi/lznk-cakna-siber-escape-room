<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class OperasiFizikalSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        try {
            $room = Room::create([
                'title' => 'Operasi Perisai: Pencerobohan Fizikal',
                'title_en' => 'Operation Shield: Physical Breach',
                'description' => 'Bukan semua godaman bermula di alam maya. Terdapat anasir asing yang berjaya melepasi pintu masuk utama. Selamatkan infrastruktur fizikal.',
                'description_en' => 'Not all hacks start in cyberspace. Unauthorized entities have bypassed the front door. Secure the physical infrastructure.',
                'pass_mark' => 100,
                'is_active' => true,
            ]);

            $scenarios = [
                ['level' => 1, 'text' => 'FASA 1: Anda tiba di pintu masuk zon selamat (Secure Zone) pejabat. Seorang lelaki memakai uniform penghantar makanan membawa kotak pizza meminta anda menahan pintu untuknya.',
                    'options' => [
                        ['text' => 'Mohon maaf dan minta beliau melalui pondok pengawal atau kaunter tetamu untuk pendaftaran.', 'points' => 10, 'feedback' => '[TEPAT] Anda berjaya menghalang taktik "Tailgating/Piggybacking" yang sering digunakan untuk memintas kunci keselamatan.'],
                        ['text' => 'Pegang pintu, tetapi beritahu dia letak kotak di kaunter depan bilik sahaja.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Dia telah berjaya masuk ke dalam kawasan sekuriti melepasi Access Card anda.'],
                        ['text' => 'Senyum, pegang pintu untuknya dan tunjukkan laluan ke pantry dalam.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Penceroboh masuk dengan bebas. Kotak pizza itu mungkin menyembunyikan alatan godaman peranti (Rogue Device)!']
                    ]],
                ['level' => 2, 'text' => 'FASA 2: Di atas meja di ruang rehat (Pantry), anda terjumpa sebuah pemacu kilat (Thumbdrive/USB) berlencana "BONUS PRESTASI TAHUNAN.pdf".',
                    'options' => [
                        ['text' => 'Ambil menggunakan tisu, serahkan terus kepada Jabatan Keselamatan IT (InfoSec) untuk pengasingan.', 'points' => 10, 'feedback' => '[TEPAT] Ini adalah taktik "Baiting" (Umpan). Pasukan IT akan memeriksanya dalam kotak pasir (Sandbox).'],
                        ['text' => 'Tinggalkan sahaja USB itu di atas meja supaya pemiliknya mudah mencari.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Meninggalkannya membenarkan staf lain yang lebih naif (curious) mengambil dan mencucuknya ke PC.'],
                        ['text' => 'Cucuk USB tersebut pada komputer kerja anda untuk tengok isi kandungannya.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda telah menyuntik perisian tebusan berbentuk "Rubber Ducky" yang memintas sistem sekuriti terus dari perkakasan!']
                    ]],
                ['level' => 3, 'text' => 'FASA 3: Anda terpaksa bergegas ke tandas sebentar. Laporan kewangan yang sensitif terpampang terbuka di skrin komputer desktop anda.',
                    'options' => [
                        ['text' => 'Tekan kekunci [Windows] + [L] untuk mengunci (Lock) skrin serta-merta sebelum bangun.', 'points' => 10, 'feedback' => '[TEPAT] "Clear Desk & Clear Screen Policy" adalah adab keselamatan fizikal yang asas dan wajib.'],
                        ['text' => 'Tutup (Minimize) aplikasi tersebut dan biarkan nampak paparan "Desktop" sahaja.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Sesiapa yang lalu boleh memaksimumkan semula aplikasi tersebut dalam masa sesaat.'],
                        ['text' => 'Biar sahaja terbuka. Anda hanya pergi sekejap dan staf lain patut percaya satu sama lain.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Seseorang sempat mengambil gambar skrin (Shoulder Surfing) menggunakan telefon pintar!']
                    ]],
                ['level' => 4, 'text' => 'FASA 4: Balik dari tandas, anda nampak seorang "staf vendor pembersihan" (Cleaner) sedang menyapu di bawah meja berdekatan pelayan mini (Server Rack) terbuka.',
                    'options' => [
                        ['text' => 'Tegur dengan sopan dan pastikan beliau ada tag kelulusan vendor, dan laporkan rak terbuka itu kepada IT.', 'points' => 10, 'feedback' => '[TEPAT] "Challenge Unbadged Personnel" adalah hak penjagaan keselamatan fasiliti pekerja.'],
                        ['text' => 'Tengok sahaja kerana beliau nampak seperti pekerja kontrak biasa.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Pakaian seragam sangat mudah dibeli atau dipalsukan. Beliau mungkin memacak "Rogue Router".'],
                        ['text' => 'Tinggalkan bilik tersebut supaya beliau selesa membersihkan ruang bawah meja.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Penjenayah menyamar telah memasang "Keylogger Hardware" pada port papan kekunci rakan anda.']
                    ]],
                ['level' => 5, 'text' => 'FASA 5: Pasukan IT mendapati ada satu peranti rangkaian baharu dinamakan "Free_WiFi_Pantry" bersiaran (broadcast) dari bangunan anda.',
                    'options' => [
                        ['text' => 'Gunakan perisian penganalisa Wi-Fi untuk mengesan sumber isyarat (Triangulation) dan musnahkan peranti asing (Rogue Access Point) tersebut.', 'points' => 10, 'feedback' => '[TEPAT] Ancaman "Evil Twin" dipatahkan sebelum ia mencuri kata laluan staf yang bersambung kepadanya.'],
                        ['text' => 'Edar emel kepada staf supaya jangan guna Wi-Fi tersebut.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Emel peringatan tidak menghalang peranti asing tersebut merisik (Sniffing) gelombang syarikat.'],
                        ['text' => 'Biar sahaja, mungkin staf bawahan yang pasang sendiri untuk hiburan.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Segala trafik staf yang sambung ke "Evil Twin" (termasuk kata laluan portal) di-sniff oleh penceroboh!']
                    ]],
                ['level' => 6, 'text' => 'FASA 6: Di bilik mensyuarat, terdapat nota pelekat (Sticky Note) berwarna kuning di bawah papan kekunci PC mesyuarat, tertulis kata laluan admin.',
                    'options' => [
                        ['text' => 'Koyakkan, bakar/racik (shred) kertas tersebut dan lapor kepada IT untuk tukar kata laluan Admin dewan mesyuarat.', 'points' => 10, 'feedback' => '[TEPAT] Pembersihan fizikal (Clean Desk Policy) mengatasi kelemahan manusia yang malas menghafal.'],
                        ['text' => 'Alihkan dan tampal semula nota itu di dalam laci supaya lebih tersorok.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Ia masih terdedah kepada pencuci, tetamu, atau pemeriksa teknikal luar.'],
                        ['text' => 'Ambil gambar nota itu untuk senangkan urusan anda guna bilik mesyuarat kelak.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda telah mendigitalkan (menyebarkan) kelemahan keselamatan ke dalam peribadi anda!']
                    ]],
                ['level' => 7, 'text' => 'FASA 7: Kad Akses (Access Card) anda hilang dari dompet. Apa tindakan utama?',
                    'options' => [
                        ['text' => 'Lapor kepada Jabatan Keselamatan / HR serta-merta untuk dinyahaktifkan (Deactivate) nombor siri kad tersebut.', 'points' => 10, 'feedback' => '[TEPAT] Kad yang digantung tidak lagi boleh diimbas oleh pihak yang menemuinya.'],
                        ['text' => 'Tunggu beberapa hari mencari di merata-rata sebelum melapor.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Walaupun berniat baik, tetingkap 24 jam adalah waktu emas penceroboh menyelinap menggunakan kad anda.'],
                        ['text' => 'Pinjam kad rakan (Tumpang masuk) untuk berbulan-bulan tanpa melapor.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Orang asing mula masuk kawasan sulit menggunakan kad identiti anda! Anda akan dituduh!']
                    ]],
                ['level' => 8, 'text' => 'FASA 8: Semasa membuang dokumen lama di pejabat, terdapat timbunan dokumen cetak "Sulit: Profil Data Asnaf" di tepi tong sampah terbuka.',
                    'options' => [
                        ['text' => 'Kumpul kesemuanya dan masukkan ke dalam Mesin Perincih Dokumen (Paper Shredder) zon selamat.', 'points' => 10, 'feedback' => '[TEPAT] "Dumpster Diving" adalah cara klasik penggodam/penipu mencari bahan mangsa.'],
                        ['text' => 'Bungkus elok-elok dalam beg sampah hitam tebal dan buang di luar bangunan.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Pengutip sampah sindiket sering menyelongkar beg hitam dari bangunan korporat.'],
                        ['text' => 'Biar sahaja, "Kan cleaner nak bersihkan nanti".', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Data terperinci peribadi tular di internet hasil daripada penyeludup sampah kertas!']
                    ]],
                ['level' => 9, 'text' => 'FASA 9: Salah seorang staf meletakkan alat "Pengecas USB Awam" (Public USB Charger Cable) yang ditemui di lapangan terbang pada PC jabatannya.',
                    'options' => [
                        ['text' => 'Rampas kabel tersebut! Terangkan risiko serangan "Juice Jacking". Gunakan kabel original soket dinding.', 'points' => 10, 'feedback' => '[TEPAT] Kabel USB awam boleh diubah suai untuk menyuntik perisian rahsia semasa mengecas.'],
                        ['text' => 'Biarkan dia guna asalkan hanya sekadar caj peranti sahaja, bukan untuk salin data.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Walaupun sekadar caj, kabel yang diubahsuai (malicious cable) bypass mode pengecas ke mode data.'],
                        ['text' => 'Pinjam kabel tersebut untuk caj telefon pintar anda sendiri.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Telefon pintar anda digodam ("Juice Jacked") sepenuhnya, mencuri kod TAC perbankan anda!']
                    ]],
                ['level' => 10, 'text' => 'FASA 10: Anda bertugas malam. Rakan sekerja meminta anda sebutkan kata laluannya melalui tingkap supaya dia boleh log masuk sistem dari luar (Work From Home).',
                    'options' => [
                        ['text' => 'Tolak secara profesional. Tegaskan kata laluan tidak boleh disebut, dikongsi secara vokal, atau dipinjamkan.', 'points' => 10, 'feedback' => '[TEPAT] Keselamatan Siber bermula dengan Integriti Identiti (Non-repudiation).'],
                        ['text' => 'Sebutkan dengan perlahan atau tulis di WhatsApp supaya tak dengar kuat.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Jejak tulisan WhatsApp kekal. Berkongsi kata laluan tetap menyalahi polisi pengauditan sistem.'],
                        ['text' => 'Tolong taipkan masuk kata laluannya dari PC anda supaya kerja jalannya siap.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Jika wujud transaksi haram pada detik itu, akaun tersebut menjadi suspek. Amalan perkongsian log yang parah!']
                    ]],
                ['level' => 11, 'text' => 'FASA 11: Bekas staf IT yang telah berhenti kerja kelmarin didapati merayau di koridor teknikal.',
                    'options' => [
                        ['text' => 'Iringi beliau ke pintu keluar dengan hormat dan semak adakah HR telah laksanakan prosedur penyahaktifan sistem dan kad.', 'points' => 10, 'feedback' => '[TEPAT] Prosedur Keluar (Offboarding) fizikal dan logikal yang ketat mengelakkan sabotaj balas dendam.'],
                        ['text' => 'Sapa dan biarkan beliau berjalan. Mungkin dia ingin berjumpa kawan lama.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Beliau tidak lagi mempunyai pelepasan zon terhad. Kehadiran tanpa pengiring adalah risiko tinggi.'],
                        ['text' => 'Pinjamkan akses kad ke bilik Server kepadanya kerana dia lupa nak "ambil barang peribadi".', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Staf dendam telah memformat keseluruhan partition simpanan utama! Bencana menyeluruh!']
                    ]],
                ['level' => 12, 'text' => 'FASA 12: Sebuah mesin pencetak pintar (Smart Printer / IoT) baharu dibeli dan diletakkan di ruang pejabat terbuka. Pasukan penyelenggara lupa menukar kata laluan admin "1234".',
                    'options' => [
                        ['text' => 'Matikan (Off) pencetak, maklumkan IT untuk tukar "Default Password" dan letakkan ia di dalam rangkaian VLAN yang terasing.', 'points' => 10, 'feedback' => '[TEPAT] Peranti IoT adalah pintu belakang (backdoor) termudah bagi penceroboh melompat ke rangkaian korporat.'],
                        ['text' => 'Biarkan sahaja selagi ia berfungsi untuk cetak dokumen.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Penggodam luar boleh mencari pencetak ber-IP awam ini menggunakan laman carian Shodan.'],
                        ['text' => 'Sebarkan kepada semua staf bahawa kata laluan "1234" boleh digunakan untuk main fungsi Wi-Fi pencetak tersebut.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Dokumen sulit yang dicetak disalin (interception) oleh penjenayah kerana tahap sekuriti IoT adalah paras sifar!']
                    ]],
                ['level' => 13, 'text' => 'FASA 13: Anda nampak port LAN/Ethernet dinding terbuka kosong di ruang lobi tetamu.',
                    'options' => [
                        ['text' => 'Minta IT "Disable" port lobi tersebut di bilik Suis (Switch Room), supaya port itu ibarat "mati" jika dicucuk peranti asing.', 'points' => 10, 'feedback' => '[TEPAT] Port Security menghalang serangan "Plug & Hack" secara senyap di kawasan tetamu.'],
                        ['text' => 'Tampal selotip sahaja pada lubang LAN port tersebut.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Penyelesaian fizikal yang lemah. Sangat mudah dikoyak penceroboh.'],
                        ['text' => 'Gunakan port tersebut untuk pasang Android TV bagi tontonan pelawat luar.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Peranti tidak selamat disambung terus ke intranet teras! Terbuka luas untuk pencerobohan peranti TV (Smart TV Hack).']
                    ]],
                ['level' => 14, 'text' => 'FASA 14: Seseorang menghantar "bungkusan cenderahati misteri" mengandungi pembesar suara Bluetooth (Smart Speaker).',
                    'options' => [
                        ['text' => 'Jangan bawa masuk ke pejabat. Ia mungkin alat intipan audio rahsia (Audio Bug). Serah ke meja kawalan (SecDesk).', 'points' => 10, 'feedback' => '[TEPAT] Alat elektronik asing tanpa profil penghantar terjamin adalah vektor serangan intipan perisikan korporat.'],
                        ['text' => 'Bawa masuk tapi pastikan tak sambung pada Wi-Fi pejabat.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Alat tersebut mungkin dilengkapi pemancar gelombang selular sendiri untuk merakam perbincangan.'],
                        ['text' => 'Pasang di tengah meja bilik mensyuarat utama untuk kegunaan bersama bos-bos.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Alat "Trojan Horse" moden! Semua strategi kewangan syarikat dipantau sindiket saingan.']
                    ]],
                ['level' => 15, 'text' => 'FASA 15: Operasi fizikal ditutup. Pengajaran terbesar daripada insiden pencerobohan realiti berbanding skrin digital adalah:',
                    'options' => [
                        ['text' => 'Tidak kira betapa kuat Firewall dan Anti-Virus kita, keselamatan bangunan dan prosedur manusia (SOP fizikal) adalah perisai paling sejati.', 'points' => 10, 'feedback' => '[TEPAT] Human Firewall Fizikal menyelamatkan keadaan. Anda membuktikan kecemerlangan prosedur keselamatan LZNK.'],
                        ['text' => 'Kunci elektronik dan CCTV sudah mencukupi menggantikan minda manusia.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Alat keselamatan elektronik boleh dicelaru (Bypass/Jammed), sifat kewaspadaan manusa tidak boleh.'],
                        ['text' => 'Keselamatan siber hanyalah tugas Jabatan IT dan pengaturcaraan.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Mentaliti toksik yang menghancurkan budaya integriti. Keselamatan organisasi adalah tanggungjawab BERSAMA!']
                    ]]
            ];

            $createdQs = [];
            foreach ($scenarios as $scene) {
                $createdQs[] = Question::create(['room_id' => $room->id, 'level' => $scene['level'], 'text' => $scene['text']]);
            }
            foreach ($scenarios as $idx => $scene) {
                $nextQId = ($idx < count($createdQs) - 1) ? $createdQs[$idx + 1]->id : null;
                foreach ($scene['options'] as $opt) {
                    Option::create(['question_id' => $createdQs[$idx]->id, 'text' => $opt['text'], 'points' => $opt['points'], 'feedback' => $opt['feedback'], 'next_question_id' => $nextQId]);
                }
            }
            DB::commit();
        } catch (\Exception $e) { DB::rollBack(); }
    }
}