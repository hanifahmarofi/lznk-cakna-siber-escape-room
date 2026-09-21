<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class OperasiHelangSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        try {
            $room = Room::create([
                'title' => 'Operasi Helang: Ancaman Dalaman',
                'title_en' => 'Operation Eagle: Insider Threat',
                'description' => 'Sistem mengesan aktiviti muat turun data yang luar biasa dari akaun seorang staf. Anda perlu menyiasat dan menghalang kebocoran data (Data Leakage) tanpa mencetuskan panik.',
                'description_en' => 'The system detects unusual data download activity from a staff account. Investigate and prevent data leakage without causing panic.',
                'pass_mark' => 100,
                'is_active' => true,
            ]);

            $scenarios = [
                ['level' => 1, 'text' => 'FASA 1: Amaran DLP (Data Loss Prevention) tercetus. Akaun "Staf A" sedang memuat turun 50GB data asnaf ke dalam komputer ribanya pada jam 2:00 pagi.',
                    'options' => [
                        ['text' => 'Sekat (Suspend) akaun Staf A serta-merta dan asingkan komputer ribanya dari rangkaian.', 'points' => 10, 'feedback' => '[TEPAT] Anda berjaya menghentikan muat turun tersebut secara drastik sebelum lebih banyak data terdedah.'],
                        ['text' => 'Hubungi Staf A untuk bertanya mengapa beliau memuat turun data pada waktu ini.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Ini memberi amaran kepada pelaku untuk memadamkan bukti atau mempercepatkan pemindahan data.'],
                        ['text' => 'Abaikan amaran ini kerana Staf A mempunyai akses sah ke pangkalan data.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Akses sah tidak bermaksud niat yang sah. Data berharga sedang disalin keluar dari organisasi!']
                    ]],
                ['level' => 2, 'text' => 'FASA 2: Anda dapati Staf A menggunakan VPN dari lokasi luar negara (Rusia) yang sangat mencurigakan.',
                    'options' => [
                        ['text' => 'Tamatkan sesi (Kill Session) VPN tersebut dan sekat IP antarabangsa.', 'points' => 10, 'feedback' => '[TEPAT] Langkah pantas memotong akses penggodam luar yang mungkin telah mencuri identiti Staf A.'],
                        ['text' => 'Hantar emel amaran kepada Staf A tentang penggunaan VPN luar.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Penggodam (atau staf tersebut) tidak akan mempedulikan amaran emel semasa sedang mencuri data.'],
                        ['text' => 'Tunggu sehingga waktu pejabat untuk berbincang dengan Staf A.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Menjelang pagi, kesemua pangkalan data telah disalin dan dijual di Dark Web.']
                    ]],
                ['level' => 3, 'text' => 'FASA 3: Anda menyekat akaun tersebut. Pada jam 8 pagi, Staf A datang ke IT Helpdesk dan marah kerana tidak dapat log masuk. Apa tindakan anda?',
                    'options' => [
                        ['text' => 'Maklumkan akaun dikunci sementara atas faktor keselamatan dan minta perantinya untuk disemak.', 'points' => 10, 'feedback' => '[TEPAT] Pendekatan tenang membantu anda merampas peranti (bahan bukti) tanpa provokasi.'],
                        ['text' => 'Tuduh Staf A mencuri data dan arahkan pengawal keselamatan menangkapnya.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Tuduhan tanpa siasatan forensik lengkap boleh mengundang tindakan saman malu.'],
                        ['text' => 'Mohon maaf dan tetapkan semula (reset) kata laluannya supaya dia boleh bekerja.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda baru sahaja membuka semula pintu kepada pencuri data!']
                    ]],
                ['level' => 4, 'text' => 'FASA 4: Komputer riba Staf A berada di tangan anda. Pasukan Blue Team mula menganalisis peranti tersebut.',
                    'options' => [
                        ['text' => 'Hasilkan imej forensik (Forensic Image) bit-by-bit sebelum melakukan analisis.', 'points' => 10, 'feedback' => '[TEPAT] Imej forensik mengekalkan integriti bahan bukti sekiranya kes ini dibawa ke mahkamah.'],
                        ['text' => 'Buka fail log secara manual untuk mencari fail yang dimuat turun.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Tindakan ini merosakkan cap masa (timestamp) dan integriti bahan bukti digital.'],
                        ['text' => 'Format semula (Format) komputer riba tersebut untuk membuang sebarang virus.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda telah menghapuskan kesemua bukti pemindahan data haram secara kekal!']
                    ]],
                ['level' => 5, 'text' => 'FASA 5: Analisis mendapati sebuah perisian "Keylogger" telah dipasang pada komputer Staf A minggu lepas.',
                    'options' => [
                        ['text' => 'Jalankan imbasan menyeluruh di seluruh rangkaian untuk mencari Keylogger yang sama.', 'points' => 10, 'feedback' => '[TEPAT] Anda proaktif mencari jangkitan lain (Indicator of Compromise) di dalam rangkaian organisasi.'],
                        ['text' => 'Buang perisian Keylogger itu dari komputer Staf A sahaja.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Jangkitan mungkin telah merebak ke peranti rakan sekerjanya yang lain.'],
                        ['text' => 'Tuduh Staf A sengaja memasang Keylogger tersebut.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Keylogger biasanya dipasang oleh penggodam luar tanpa disedari mangsa (Staf A sebenarnya tidak bersalah!).']
                    ]],
                ['level' => 6, 'text' => 'FASA 6: Oleh kerana Staf A bukan pelaku utama (mangsa godaman), apakah yang sepatutnya menghentikan penggodam luar ini dari awal?',
                    'options' => [
                        ['text' => 'Pengesahan Pelbagai Faktor (MFA/2FA) wajib untuk VPN dan sistem.', 'points' => 10, 'feedback' => '[TEPAT] Walaupun penggodam mencuri kata laluan, MFA akan menyekat mereka daripada log masuk.'],
                        ['text' => 'Kata laluan yang lebih panjang (12 aksara).', 'points' => 5, 'feedback' => '[KURANG TEPAT] Keylogger merekodkan setiap aksara yang ditaip. Kata laluan sepanjang 50 aksara pun tetap akan dicuri.'],
                        ['text' => 'Perisian Antivirus percuma.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Antivirus percuma tidak mempunyai EDR yang cukup kuat untuk mengesan serangan sasaran (APT).']
                    ]],
                ['level' => 7, 'text' => 'FASA 7: Penggodam sempat memindahkan sebahagian kecil fail sebelum disekat. Mereka menghantar emel mengugut untuk membocorkannya kepada awam.',
                    'options' => [
                        ['text' => 'Lapor kepada pihak polis (PDRM) dan SKMM/NACSA serta libatkan Penasihat Undang-Undang.', 'points' => 10, 'feedback' => '[TEPAT] Melibatkan pihak berkuasa pada peringkat awal adalah SOP keselamatan rasmi negara.'],
                        ['text' => 'Bincang dengan penggodam untuk mengetahui harga yang mereka mahukan.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Berkomunikasi dengan pemeras ugut mendedahkan organisasi kepada lebih banyak manipulasi.'],
                        ['text' => 'Bayar tebusan menggunakan dana operasi.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Membayar penggodam menggunakan dana awam adalah jenayah pecah amanah (CBT)!']
                    ]],
                ['level' => 8, 'text' => 'FASA 8: Semasa siasatan, anda mendapati Staf A ditipu memuat turun Keylogger tersebut melalui emel palsu yang menyamar sebagai "Kemas Kini HR".',
                    'options' => [
                        ['text' => 'Tarik balik (Recall) emel palsu tersebut dari inbox semua kakitangan melalui pelayan Exchange/Mail.', 'points' => 10, 'feedback' => '[TEPAT] Menghapuskan vektor serangan mengelakkan lebih ramai staf menjadi mangsa.'],
                        ['text' => 'Hantar amaran WhatsApp kepada staf supaya jangan klik emel tersebut.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Tidak semua orang membaca WhatsApp serta-merta. Risiko masih tinggi.'],
                        ['text' => 'Biarkan sahaja, kerana staf sepatutnya sudah cukup bijak untuk tahu ia palsu.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda gagal melindungi pengguna. Lebih ramai staf mula memuat turun Keylogger tersebut!']
                    ]],
                ['level' => 9, 'text' => 'FASA 9: Terdapat 5 staf lain yang dikesan telah mengklik pautan dalam emel "Kemas Kini HR" tersebut.',
                    'options' => [
                        ['text' => 'Laksanakan kuarantin rangkaian serta-merta ke atas peranti ke-5 staf tersebut.', 'points' => 10, 'feedback' => '[TEPAT] Anda berjaya menghentikan jangkitan lateral (lateral movement) di peringkat awal.'],
                        ['text' => 'Paksa ke-5 staf ini menukar kata laluan mereka.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Walaupun kata laluan ditukar, peranti mereka mungkin sudah dijangkiti malware.'],
                        ['text' => 'Tanya mereka di kafeteria adakah mereka perasan komputer mereka perlahan.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Pemantauan keselamatan siber bukan melalui sembang santai. Data terus dicuri!']
                    ]],
                ['level' => 10, 'text' => 'FASA 10: Pengurusan tertinggi mendesak untuk mengetahui jenis data yang sempat dicuri oleh penggodam.',
                    'options' => [
                        ['text' => 'Analisis fail log DLP untuk memberikan laporan tepat (senarai nama fail dan saiz).', 'points' => 10, 'feedback' => '[TEPAT] Anda memberi fakta berasaskan bukti (Evidence-based reporting).'],
                        ['text' => 'Buat andaian bahawa semua data staf telah dicuri.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Andaian yang berlebihan akan mencetuskan langkah panik yang tidak perlu.'],
                        ['text' => 'Maklumkan bahawa tiada data dicuri untuk meredakan keadaan.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Menipu pengurusan adalah pelanggaran integriti serius. Kebocoran data akhirnya terbukti di media!']
                    ]],
                ['level' => 11, 'text' => 'FASA 11: Data yang disalin hanyalah templat borang kosong (tiada data peribadi). Risiko kebocoran adalah rendah.',
                    'options' => [
                        ['text' => 'Beri taklimat rasmi kepada pengurusan bahawa impak adalah rendah, tetapi insiden tetap dilaporkan secara formal.', 'points' => 10, 'feedback' => '[TEPAT] Pengurusan risiko yang cemerlang. Anda telus tetapi tidak menimbulkan panik.'],
                        ['text' => 'Rahsiakan keseluruhan insiden kerana tiada data sebenar hilang.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Insiden hampir-rugi (Near Miss) wajib direkodkan untuk tujuan audit.'],
                        ['text' => 'Buat kenyataan akhbar memaklumkan sistem telah diceroboh.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Kenyataan melulu menjatuhkan imej organisasi tanpa sebab yang kukuh!']
                    ]],
                ['level' => 12, 'text' => 'FASA 12: Organisasi perlu menambah baik tahap keselamatan (Post-Incident).',
                    'options' => [
                        ['text' => 'Laksanakan senarai putih aplikasi (Application Whitelisting) supaya staf tidak boleh "install" perisian tanpa kebenaran.', 'points' => 10, 'feedback' => '[TEPAT] Pendekatan Zero-Trust ini adalah benteng terbaik terhadap perisian hasad baru.'],
                        ['text' => 'Tarik balik akses internet dari semua PC staf.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Tindakan melampau ini melumpuhkan operasi harian dan merendahkan produktiviti.'],
                        ['text' => 'Pasang sistem pemantau pekerja (Spyware) untuk melihat skrin mereka 24/7.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Melanggar hak privasi staf dan etika organisasi, memusnahkan kepercayaan pekerja!']
                    ]],
                ['level' => 13, 'text' => 'FASA 13: Staf A didapati trauma akibat kejadian phishing yang menyebabkannya dituduh bersalah pada awalnya.',
                    'options' => [
                        ['text' => 'Anjurkan sesi kaunseling sokongan IT dan jadikan beliau "Duta Kesedaran Siber" agar belajar dari kesilapan.', 'points' => 10, 'feedback' => '[TEPAT] Budaya "No-Blame" menggalakkan pekerja berani melaporkan insiden keselamatan.'],
                        ['text' => 'Beri amaran tegas supaya jangan lurus bendul klik emel lagi.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Amaran menakutkan staf, menyebabkan mereka takut lapor jika terklik link lagi pada masa depan.'],
                        ['text' => 'Pecat Staf A sebagai pengajaran kepada staf lain.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Tindakan undang-undang tidak adil ini menyebabkan syarikat disaman di Mahkamah Perusahaan.']
                    ]],
                ['level' => 14, 'text' => 'FASA 14: Laporan bedah siasat (Post-Mortem) perlu dibentangkan. Siapa audiens utama laporan ini?',
                    'options' => [
                        ['text' => 'Ketua Pegawai Eksekutif (CEO) dan Ahli Lembaga Pengarah.', 'points' => 10, 'feedback' => '[TEPAT] Pengurusan tertinggi wajib faham insiden untuk meluluskan peruntukan keselamatan siber.'],
                        ['text' => 'Semua kakitangan organisasi.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Laporan ini mengandungi butiran teknikal terperinci dan maklumat sensitif yang sulit.'],
                        ['text' => 'Muat naik laporan ke laman web korporat untuk tatapan awam.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Pendedahan kerentanan sistem memberi peta laluan percuma kepada penggodam lain!']
                    ]],
                ['level' => 15, 'text' => 'FASA 15: Operasi hampir selesai. Apakah pelaburan paling penting untuk tahun hadapan bagi mengelak ancaman dalaman dan luaran ini berulang?',
                    'options' => [
                        ['text' => 'Pelaburan program Kesedaran Keselamatan Siber (Security Awareness) yang interaktif untuk warga kerja.', 'points' => 10, 'feedback' => '[TEPAT] Staf adalah barisan pertahanan terakhir (Human Firewall). Kesedaran adalah pelaburan terbaik.'],
                        ['text' => 'Membeli perisian Firewall yang paling mahal di pasaran.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Teknologi terbaik di dunia akan tetap gagal jika staf sendiri yang membuka pintu dari dalam (klik link).'],
                        ['text' => 'Tidak perlu pelaburan tambahan, sistem sedia ada sudah memadai.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Ancaman siber berevolusi setiap hari. Sikap terlalu selesa akan menyebabkan kejatuhan organisasi!']
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