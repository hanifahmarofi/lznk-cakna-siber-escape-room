<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class OperasiBayangSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // 1. Create the Custom Branching Room
            $room = Room::create([
                'title' => 'Operasi Bayang: Ancaman Perisian Tebusan',
                'title_en' => 'Operation Shadow: Ransomware Threat',
                'description' => 'Simulasi 15-langkah menangani serangan perisian tebusan (Ransomware) ke atas pangkalan data utama operasi. Setiap keputusan anda menentukan kelangsungan sistem organisasi.',
                'description_en' => 'A 15-step simulation on handling a Ransomware attack on the main operational database. Every decision determines the survival of the organization systems.',
                'pass_mark' => 100, // Max score is 150 (15 questions * 10 pts)
                'is_active' => true,
            ]);

            // 2. Define the 15-Step Scenario Journey
            $scenarios = [
                [
                    'level' => 1,
                    'text' => 'FASA 1: Seorang staf melaporkan menerima emel amaran invois tertunggak daripada penghantar yang tidak dikenali, tetapi logo syarikat kelihatan sah. Apa arahan anda?',
                    'options' => [
                        ['text' => 'Arahkan staf padam emel tersebut dan sekat (block) penghantar serta-merta.', 'points' => 10, 'feedback' => '[TEPAT] Anda berjaya menghentikan rantaian serangan di peringkat awal (Phishing phase).'],
                        ['text' => 'Minta staf forward emel tersebut kepada anda untuk disemak.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Memanjangkan emel phishing mendedahkan lebih ramai orang kepada risiko. Sebaiknya gunakan fungsi "Report Phishing".'],
                        ['text' => 'Arahkan staf buka lampiran (attachment) untuk pastikan sama ada ia invois sebenar.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Tindakan ini mengaktifkan perisian hasad (malware) yang tersembunyi di dalam lampiran tersebut!']
                    ]
                ],
                [
                    'level' => 2,
                    'text' => 'FASA 2: Walaupun amaran diberikan, sistem mengesan satu peranti di Jabatan Kewangan telah memuat turun fail mencurigakan. Peranti mula menjadi perlahan. Apakah tindakan mitigasi pertama?',
                    'options' => [
                        ['text' => 'Cabut kabel LAN atau putuskan sambungan Wi-Fi peranti tersebut serta-merta.', 'points' => 10, 'feedback' => '[TEPAT] Pengasingan fizikal menghalang perisian hasad daripada merebak ke pelayan utama (Lateral Movement).'],
                        ['text' => 'Arahkan staf matikan (Shut Down) komputer tersebut.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Mematikan PC mungkin memadamkan memori RAM yang penting untuk siasatan forensik kelak.'],
                        ['text' => 'Biar peranti kekal bersambung sambil anda menjalankan imbasan Antivirus jarak jauh.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Imbasan jarak jauh mengambil masa. Perisian hasad kini sedang mencari jalan masuk ke pelayan data asnaf!']
                    ]
                ],
                [
                    'level' => 3,
                    'text' => 'FASA 3: Skrin peranti di Jabatan Kewangan bertukar merah dengan mesej tebusan berbunyi "FILES ENCRYPTED". Apakah langkah komunikasi seterusnya?',
                    'options' => [
                        ['text' => 'Aktifkan Protokol Tindak Balas Insiden (IR) dan maklumkan Pengurusan Tertinggi (C-Level).', 'points' => 10, 'feedback' => '[TEPAT] Ketelusan mempercepatkan mobilisasi pasukan teknikal dan persediaan perundangan.'],
                        ['text' => 'Maklumkan kepada unit IT sahaja untuk cuba selesaikan secara senyap-senyap.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Insiden ini terlalu besar untuk disembunyikan. Pengurusan perlu tahu untuk urus risiko reputasi.'],
                        ['text' => 'Buat pengumuman awam di media sosial bahawa sistem sedang digodam.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Kenyataan melulu mencetuskan panik awam dan mengganggu siasatan dalaman!']
                    ]
                ],
                [
                    'level' => 4,
                    'text' => 'FASA 4: Semasa proses pengasingan, anda mendapati satu pelayan sandaran (backup server) lama masih bersambung ke rangkaian yang sama dengan PC yang dijangkiti.',
                    'options' => [
                        ['text' => 'Log masuk ke sistem suis (switch) dan lumpuhkan port pelayan sandaran itu.', 'points' => 10, 'feedback' => '[TEPAT] Segregasi rangkaian berjaya menyelamatkan data sandaran daripada disulitkan oleh Ransomware.'],
                        ['text' => 'Arahkan pengawal keselamatan ke bilik server untuk cabut plug kuasa.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Cara ini berkesan tetapi berisiko merosakkan perkakasan cakera keras (hard drive).'],
                        ['text' => 'Abaikan pelayan lama tersebut kerana ia tidak mengandungi data bulan ini.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Ransomware telah meresap masuk dan menghancurkan satu-satunya sistem sandaran fizikal yang anda ada!']
                    ]
                ],
                [
                    'level' => 5,
                    'text' => 'FASA 5: Penggodam meninggalkan fail teks menuntut pembayaran 5 Bitcoin (RM1.5 Juta) dalam masa 24 jam atau data kewangan organisasi akan dibocorkan.',
                    'options' => [
                        ['text' => 'Abaikan tuntutan, lapor kepada agensi berkuasa (NACSA/CSM) dan fokus pada pemulihan.', 'points' => 10, 'feedback' => '[TEPAT] Membayar tebusan tidak menjamin data akan dipulangkan dan ia menggalakkan aktiviti jenayah siber.'],
                        ['text' => 'Hubungi penggodam untuk berunding meminta lanjutan masa.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Ini memberi isyarat kepada penggodam bahawa anda terdesak dan bersedia untuk membayar.'],
                        ['text' => 'Syorkan pengurusan tertinggi untuk segera membayar tebusan.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Wang dibayar, namun penggodam tetap membocorkan data. Organisasi kerugian wang dan reputasi!']
                    ]
                ],
                [
                    'level' => 6,
                    'text' => 'FASA 6: Wartawan tempatan mula menghubungi organisasi bertanyakan tentang desas-desus kebocoran data. Apakah jawapan rasmi operasi?',
                    'options' => [
                        ['text' => 'Salurkan soalan kepada Jabatan Komunikasi Korporat untuk kenyataan media yang diselaraskan.', 'points' => 10, 'feedback' => '[TEPAT] Komunikasi sepusat mengelakkan percanggahan fakta dan mengekalkan keyakinan awam.'],
                        ['text' => 'Beri amaran "Tiada Komen" kepada semua wartawan.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Walaupun selamat, ia menimbulkan spekulasi negatif yang lebih buruk di media.'],
                        ['text' => 'Nafikan sekeras-kerasnya bahawa sistem telah digodam.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Kebocoran data akhirnya terbukti benar. Kepercayaan awam terhadap organisasi hancur sepenuhnya.']
                    ]
                ],
                [
                    'level' => 7,
                    'text' => 'FASA 7: Pasukan Blue Team memerlukan anda mencari punca (Patient Zero). Alat apakah yang anda periksa dahulu?',
                    'options' => [
                        ['text' => 'Semak log Firewall dan SIEM (Security Information and Event Management).', 'points' => 10, 'feedback' => '[TEPAT] Log ini menunjukkan trafik masuk luar biasa dan merekodkan IP asal serangan.'],
                        ['text' => 'Periksa fail temp (temporary files) di komputer staf yang dijangkiti.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Agak lambat. Anda mungkin menemui fail perisian hasad, tetapi bukan titik mula pencerobohan.'],
                        ['text' => 'Semak rakaman CCTV di pejabat.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Serangan ini dilakukan secara maya dari luar negara. Membuang masa kritikal pasukan tindak balas!']
                    ]
                ],
                [
                    'level' => 8,
                    'text' => 'FASA 8: Siasatan mendapati akaun seorang kakitangan atasan telah dikompromi kerana beliau menggunakan kata laluan "Password123" tanpa 2FA.',
                    'options' => [
                        ['text' => 'Nyahaktif (Disable) akaun tersebut dan paksa penetapan semula kata laluan global untuk semua staf.', 'points' => 10, 'feedback' => '[TEPAT] Tindakan drastik ini memotong semua akses aktif penggodam di dalam rangkaian.'],
                        ['text' => 'Hanya tukar kata laluan untuk akaun kakitangan atasan tersebut.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Penggodam mungkin telah mencipta "backdoor" menggunakan akaun staf lain semasa mereka mempunyai akses.'],
                        ['text' => 'Beri amaran lisan kepada kakitangan tersebut agar menukar kata laluan nanti.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Penggodam masih mempunyai akses aktif dan terus memuat turun data peribadi dari pangkalan pangkalan data!']
                    ]
                ],
                [
                    'level' => 9,
                    'text' => 'FASA 9: Tiba masa untuk memulihkan sistem daripada data sandaran (Backup). Versi sandaran manakah yang perlu dipilih?',
                    'options' => [
                        ['text' => 'Sandaran terasing (Offline/Cold Backup) yang bertarikh sehari sebelum serangan dikesan.', 'points' => 10, 'feedback' => '[TEPAT] Ini menjamin data yang bersih dan bebas daripada jangkitan Ransomware.'],
                        ['text' => 'Sandaran awan (Cloud Backup) terbaharu yang dibuat secara automatik pagi tadi.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Sandaran terbaharu mungkin turut menyimpan fail yang telah disulitkan oleh perisian hasad.'],
                        ['text' => 'Gunakan perisian "Free Decryptor" dari internet untuk cuba buka fail.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Perisian tersebut sebenarnya adalah perisian hasad sekunder yang memusnahkan sistem sandaran anda!']
                    ]
                ],
                [
                    'level' => 10,
                    'text' => 'FASA 10: Semasa proses pemulihan (Restoration), perkhidmatan sistem teras terpaksa dihentikan sementara. Pengguna mula marah.',
                    'options' => [
                        ['text' => 'Aktifkan Pelan Kelangsungan Perniagaan (BCP) - gunakan proses manual dan borang fizikal.', 'points' => 10, 'feedback' => '[TEPAT] Operasi kritikal diteruskan secara luar talian sambil mengekalkan integriti pemulihan sistem digital.'],
                        ['text' => 'Papar notis "Sistem Diselenggara" di laman web rasmi.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Tidak membantu operasi fizikal di kaunter yang terus menerima kedatangan pelanggan.'],
                        ['text' => 'Hentikan pemulihan dan buka semula sistem yang separuh selamat.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Sistem kembali lumpuh (crash) kerana fail kritikal belum dipulihkan sepenuhnya.']
                    ]
                ],
                [
                    'level' => 11,
                    'text' => 'FASA 11: Sistem berjaya dipulihkan. Sebelum menyambungkannya semula ke internet awam, apakah langkah penampalan (patching)?',
                    'options' => [
                        ['text' => 'Jalankan imbasan Vulnerability Assessment (VA) dan tutup semua "port" yang terbuka.', 'points' => 10, 'feedback' => '[TEPAT] Memastikan kelemahan asal yang dieksploitasi oleh penggodam telah ditampal.'],
                        ['text' => 'Hanya pasang kemaskini Windows (Windows Update) terbaharu.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Kemaskini OS tidak menampal kelemahan pada aplikasi khusus atau konfigurasi firewall.'],
                        ['text' => 'Sambung terus ke internet kerana Antivirus sudah dikemaskini.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Penggodam masuk semula melalui "backdoor" lama yang masih terdedah di pelayan web!']
                    ]
                ],
                [
                    'level' => 12,
                    'text' => 'FASA 12: Pasukan IT mendapati perisian Antivirus lama gagal mengesan jenis Ransomware baharu ini (Zero-Day Attack).',
                    'options' => [
                        ['text' => 'Naik taraf keselamatan endpoint kepada sistem EDR (Endpoint Detection and Response) berasaskan AI.', 'points' => 10, 'feedback' => '[TEPAT] Sistem EDR memantau tingkah laku ganjil (behavioural analysis), bukan sekadar bergantung pada pangkalan data virus lama.'],
                        ['text' => 'Tukar jenama perisian Antivirus kepada syarikat lain.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Antivirus tradisional tetap tidak berkesan melawan serangan Zero-Day, tidak kira jenama apa pun.'],
                        ['text' => 'Kekalkan sistem lama tetapi tambah kekerapan imbasan kepada setiap jam.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Sistem menjadi terlampau lambat kerana imbasan berterusan, namun gagal mengesan virus baharu.']
                    ]
                ],
                [
                    'level' => 13,
                    'text' => 'FASA 13: Anda perlu melaksanakan polisi baharu untuk mengelakkan kakitangan dari mengklik pautan Phishing lagi pada masa hadapan.',
                    'options' => [
                        ['text' => 'Wajibkan modul kesedaran siber berkala dan jalankan simulasi Phishing mengejut (Phishing Test).', 'points' => 10, 'feedback' => '[TEPAT] Budaya kepekaan siber merupakan benteng pertahanan manusia (Human Firewall) yang paling ampuh.'],
                        ['text' => 'Hantar emel amaran panjang berjela kepada semua kakitangan.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Emel amaran jarang dibaca dengan teliti oleh kakitangan yang sibuk dengan tugas harian.'],
                        ['text' => 'Potong gaji staf yang didapati klik pautan phishing.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Budaya ketakutan membuatkan staf menyembunyikan kesilapan mereka dan tidak melaporkan insiden siber.']
                    ]
                ],
                [
                    'level' => 14,
                    'text' => 'FASA 14: Pihak audit luar menuntut laporan kejadian. Apakah yang harus disertakan di dalam laporan insiden siber?',
                    'options' => [
                        ['text' => 'Kronologi lengkap, vektor serangan, impak data, dan pelan mitigasi yang telah dilaksanakan.', 'points' => 10, 'feedback' => '[TEPAT] Laporan komprehensif membuktikan organisasi bertanggungjawab dan mempunyai tadbir urus siber yang matang.'],
                        ['text' => 'Hanya senarai perkakasan yang terjejas.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Audit memerlukan analisis punca utama (Root Cause Analysis), bukan sekadar kerosakan fizikal.'],
                        ['text' => 'Salakan kesalahan sepenuhnya kepada kakitangan yang klik pautan.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Kegagalan sistem adalah masalah keselamatan menyeluruh, bukan sekadar kesilapan seorang manusia. Anda gagal dalam audit!']
                    ]
                ],
                [
                    'level' => 15,
                    'text' => 'FASA 15: Selepas sebulan, sistem berjalan lancar. Apakah langkah proaktif terakhir anda sebagai komander operasi?',
                    'options' => [
                        ['text' => 'Lantik pihak ketiga bebas untuk menjalankan Ujian Penembusan (Penetration Testing) secara tahunan.', 'points' => 10, 'feedback' => '[TEPAT] Penggodam etikal (Ethical Hackers) akan menemui kelemahan baru sebelum penjenayah siber mengeksploitasinya.'],
                        ['text' => 'Semak log sistem secara rawak sebulan sekali.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Pemantauan keselamatan seharusnya aktif 24/7 melalui Pusat Operasi Keselamatan (SOC).'],
                        ['text' => 'Isytiharkan sistem 100% selamat dan kebal dari sebarang godaman.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Di dalam dunia keselamatan siber, tiada sistem yang 100% selamat. Sikap terlalu yakin mengundang padah!']
                    ]
                ]
            ];

            $createdQuestions = [];

            // 3. Create all Questions First
            foreach ($scenarios as $scene) {
                $createdQuestions[] = Question::create([
                    'room_id' => $room->id,
                    'level' => $scene['level'],
                    'text' => $scene['text'],
                ]);
            }

            // 4. Map Options and link them to the Next Question
            foreach ($scenarios as $index => $scene) {
                $currentQ = $createdQuestions[$index];
                
                // Determine the next question ID (if it's not the last question)
                $nextQuestionId = null;
                if ($index < count($createdQuestions) - 1) {
                    $nextQuestionId = $createdQuestions[$index + 1]->id;
                }

                // Insert the 3 options for the current question
                foreach ($scene['options'] as $optData) {
                    Option::create([
                        'question_id' => $currentQ->id,
                        'text' => $optData['text'],
                        'points' => $optData['points'],
                        'feedback' => $optData['feedback'],
                        'next_question_id' => $nextQuestionId, // Creates the chain!
                    ]);
                }
            }

            DB::commit();
            $this->command->info('Operasi Bayang Merah (15-Step Branching Room) successfully seeded!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to seed Branching Room: ' . $e->getMessage());
        }
    }
}
