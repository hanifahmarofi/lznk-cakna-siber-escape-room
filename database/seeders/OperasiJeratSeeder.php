<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class OperasiJeratSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        try {
            $room = Room::create([
                'title' => 'Operasi Jerat: Penipuan Panggilan (Vishing)',
                'title_en' => 'Operation Hook: Voice Phishing (Vishing)',
                'description' => 'Helpdesk IT menerima panggilan cemas daripada "individu penting" yang memerlukan akses sistem segera. Uji kemampuan Human Firewall anda dalam menghadapi manipulasi psikologi.',
                'description_en' => 'IT Helpdesk receives an urgent call from a "VIP" demanding immediate system access. Test your Human Firewall against psychological manipulation.',
                'pass_mark' => 100,
                'is_active' => true,
            ]);

            $scenarios = [
                ['level' => 1, 'text' => 'FASA 1: Telefon Helpdesk IT berdering. Pemanggil dengan suara cemas mendakwa dia adalah "Dato\' Pengarah" yang sedang bercuti di luar negara dan gagal log masuk sistem kewangan.',
                    'options' => [
                        ['text' => 'Kekal bertenang, mohon maaf atas kesulitan, dan minta nombor staf ID untuk proses pengesahan identiti (SOP).', 'points' => 10, 'feedback' => '[TEPAT] Anda berpegang teguh kepada Polisi Keselamatan tanpa mengira pangkat pemanggil.'],
                        ['text' => 'Tanya beliau tarikh lahir dan nombor kad pengenalan sebagai pengesahan.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Penipu (Scammer) biasanya sudah mempunyai maklumat peribadi asas ini dari kebocoran data awam.'],
                        ['text' => 'Panik kerana itu adalah bos besar, dan segera set semula (reset) kata laluannya.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda telah menyerahkan kunci utama sistem kewangan kepada "Voice Actor" scammer!']
                    ]],
                ['level' => 2, 'text' => 'FASA 2: Pemanggil menjadi marah. "Saya tiada masa untuk prosedur merepek ini! Luluskan akses saya sekarang atau awak saya pecat!"',
                    'options' => [
                        ['text' => 'Gunakan teknik kelewatan (Delay tactic): "Baik Dato, sistem memerlukan kelulusan sistem. Saya perlukan 2 minit untuk semak".', 'points' => 10, 'feedback' => '[TEPAT] Penipu tidak suka menunggu. Kelewatan memberi ruang anda menghubungi saluran rasmi untuk semakan.'],
                        ['text' => 'Letak telefon (End Call) serta-merta kerana anda yakin itu adalah scammer.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Bagaimana jika ia BENAR-BENAR Pengarah? Tindakan profesional lebih sesuai.'],
                        ['text' => 'Takut dipecat, anda segera meluluskan akses MFA (Multi-Factor Authentication) yang diminta.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda telah tunduk kepada manipulasi ketakutan (Fear & Authority tactic). Sistem diceroboh!']
                    ]],
                ['level' => 3, 'text' => 'FASA 3: Anda meletakkan panggilan pemanggil dalam mod "Hold". Apakah langkah pengesahan seterusnya?',
                    'options' => [
                        ['text' => 'Hubungi nombor telefon rasmi Dato\' Pengarah yang berdaftar dalam direktori syarikat, bukan nombor yang baru menelefon tadi.', 'points' => 10, 'feedback' => '[TEPAT] Out-of-band verification (pengesahan silang luar) mendedahkan panggilan tadi adalah palsu (Spoofed number).'],
                        ['text' => 'Hantar mesej ke nombor WhatsApp yang baru menelefon tadi.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Anda hanya sedang mesej scammer tersebut. Dia pasti akan membalas "Ya, ini saya!".'],
                        ['text' => 'Semak profil Facebook Dato untuk lihat jika dia betul-betul di luar negara.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Scammer juga melihat FB Dato dan menggunakan lokasi tersebut untuk memanipulasi anda!']
                    ]],
                ['level' => 4, 'text' => 'FASA 4: Panggilan silang anda mengesahkan Dato\' Pengarah sebenar sedang bermesyuarat dan tidak pernah menghubungi Helpdesk. Panggilan tadi disahkan palsu (Vishing).',
                    'options' => [
                        ['text' => 'Kembali ke panggilan asal, putuskan panggilan, dan log nombor palsu tersebut dalam senarai sekat (Blocklist) Firewall PBX.', 'points' => 10, 'feedback' => '[TEPAT] Ancaman dipatahkan secara teknikal dan rekod diwujudkan.'],
                        ['text' => 'Sambung panggilan, perli scammer tersebut dan buang masanya.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Scammer moden boleh merakam suara (Voice Cloning) anda pula jika anda banyak bercakap.'],
                        ['text' => 'Biar panggilan dalam mod "Hold" sampai petang.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Membazir talian (channel) rasmi Helpdesk yang diperlukan oleh staf lain.']
                    ]],
                ['level' => 5, 'text' => 'FASA 5: 10 minit kemudian, seorang kerani dari Jabatan Akaun menelefon Helpdesk. "Eh, Dato call tadi minta fail audit dikongsi via Google Drive peribadinya. Boleh IT izinkan akses?"',
                    'options' => [
                        ['text' => 'Arahkan kerani tersebut berhenti serta merta! Maklumkan itu adalah penipuan (Scam) yang menyasarkan organisasi.', 'points' => 10, 'feedback' => '[TEPAT] Anda berjaya menyelamatkan mangsa sekunder (kerani) daripada pancingan vishing yang sama.'],
                        ['text' => 'Arahkan dia berkongsi fail tersebut melalui emel rasmi syarikat sahaja, bukan Google Drive.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Mengapa mahu hantar ke scammer langsung? Walaupun melalui emel rasmi, data tetap bocor!'],
                        ['text' => 'Izinkan akses firewall supaya dia boleh muat naik fail ke Google Drive.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Fail audit sulit kini berada di tangan penjenayah siber!']
                    ]],
                ['level' => 6, 'text' => 'FASA 6: Serangan bersasar (Spear Vishing) ini sangat terancang. Bagaimana scammer tahu siapa untuk dihubungi di Jabatan Akaun?',
                    'options' => [
                        ['text' => 'Semak LinkedIn dan media sosial organisasi. Scammer mengumpul (reconnaissance) nama dan jawatan staf dari sana.', 'points' => 10, 'feedback' => '[TEPAT] Pengumpulan maklumat sumber terbuka (OSINT) adalah taktik utama penggodam sebelum menyerang.'],
                        ['text' => 'Sistem syarikat mesti telah digodam dari dalam.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Vishing sering berlaku tanpa sebarang godaman teknikal, hanya godaman minda (Social Engineering).'],
                        ['text' => 'Scammer hanya meneka nombor secara rawak (Random dialing).', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Panggilan yang memanggil nama sebenar Pengarah dan Kerani membuktikan ia sasaran khusus (Targeted).']
                    ]],
                ['level' => 7, 'text' => 'FASA 7: Sebagai langkah mitigasi awal, apakah tindakan komunikasi dalaman anda?',
                    'options' => [
                        ['text' => 'Hantar amaran kecemasan (Security Alert) rasmi kepada semua warga kerja tentang taktik panggilan palsu (Vishing) penyamaran pengurusan.', 'points' => 10, 'feedback' => '[TEPAT] Hebahan kilat mengimunisasi staf lain daripada tertipu dengan taktik yang sama.'],
                        ['text' => 'Maklumkan kepada Pengurus Jabatan Akaun sahaja.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Scammer mungkin akan menelefon Jabatan Sumber Manusia (HR) pula selepas ini.'],
                        ['text' => 'Senyapkan insiden ini kerana tiada data yang hilang.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Staf yang tidak bersedia akan menjadi mangsa panggilan seterusnya!']
                    ]],
                ['level' => 8, 'text' => 'FASA 8: Keesokan harinya, panggilan kedua diterima. Kali ini, pemanggil mendakwa dari syarikat "Microsoft Support" dan memberitahu pelayan organisasi sedang rosak.',
                    'options' => [
                        ['text' => 'Tolak bantuan. Maklumkan bahawa Microsoft tidak pernah menelefon pelanggan secara proaktif untuk isu pelayan.', 'points' => 10, 'feedback' => '[TEPAT] "Tech Support Scam" adalah salah satu vishing paling popular di dunia.'],
                        ['text' => 'Minta nama dan nombor rujukan kes (Ticket Number) beliau.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Scammer boleh mereka-reka tiket nombor yang kedengaran sangat asli (contoh: MS-78891-B).'],
                        ['text' => 'Turutkan arahannya memuat turun perisian "TeamViewer" untuk dibantu.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda baru membenarkan Remote Access Trojan (RAT) masuk ke dalam komputer anda!']
                    ]],
                ['level' => 9, 'text' => 'FASA 9: Pemanggil "Microsoft" cuba menakut-nakutkan anda: "Jika anda tutup telefon ini, semua data emel LZNK akan terpadam dalam masa 5 minit!"',
                    'options' => [
                        ['text' => 'Tutup panggilan (Hang Up). Ia adalah gertakan kosong tanpa asas teknikal.', 'points' => 10, 'feedback' => '[TEPAT] Scammer menggunakan taktik masa & ketakutan (Urgency & Fear) untuk merosakkan logik akal anda.'],
                        ['text' => 'Suruh rakan sebelah memeriksa pelayan dengan panik.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Keresahan yang anda tunjukkan memberi kepuasan psikologi kepada penipu tersebut.'],
                        ['text' => 'Percaya kata-katanya dan beri IP pelayan untuk "diselamatkan".', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Penggodam tidak menceroboh anda; anda sendiri yang menyerahkan kunci kepada mereka!']
                    ]],
                ['level' => 10, 'text' => 'FASA 10: Anda berjaya menangkis serangan. Pasukan keselamatan perlu memulakan latihan simulasi "Vishing" untuk semua staf. Apakah modul terbaik?',
                    'options' => [
                        ['text' => 'Modul mengecam taktik manipulasi emosi (kecemasan, ketakutan, pangkat) berbanding teknologi.', 'points' => 10, 'feedback' => '[TEPAT] Kejuruteraan Sosial menggodam emosi manusia, bukan kod komputer.'],
                        ['text' => 'Modul teknikal bagaimana nak trace alamat IP pemanggil.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Ia terlalu teknikal untuk staf biasa (Non-IT) dan kurang praktikal.'],
                        ['text' => 'Modul amaran supaya tidak menjawab langsung nombor tidak dikenali.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Agensi perkhidmatan pelanggan wajib menjawab panggilan luar. Ini akan mematikan operasi!']
                    ]],
                ['level' => 11, 'text' => 'FASA 11: Selain latihan, alat teknologi apakah (Security Baseline) yang membantu mengekang scammer ini jika mereka mendapat kata laluan?',
                    'options' => [
                        ['text' => 'Sistem Pengesahan Tanpa Kata Laluan (Passwordless / FIDO2) atau MFA Biometrik.', 'points' => 10, 'feedback' => '[TEPAT] Walaupun penipu menipu kata laluan melalui panggilan, mereka tidak memiliki cap jari/peranti mangsa.'],
                        ['text' => 'Tukar kata laluan setiap 30 hari.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Polisi penukaran kata laluan kerap menyebabkan staf menggunakan kata laluan lemah (P@ssword1, P@ssword2).'],
                        ['text' => 'Buang sepenuhnya sistem luar dan kembali ke fail kertas.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Mundur ke belakang (Regressive) membunuh revolusi pendigitalan perkhidmatan awam.']
                    ]],
                ['level' => 12, 'text' => 'FASA 12: Pengurusan mencadangkan supaya nombor telefon sambungan (extension) utama tidak lagi didedahkan kepada awam secara terbuka di web.',
                    'options' => [
                        ['text' => 'Sokong. Hanya papar nombor talian khidmat pelanggan (Hotline) berpusat untuk tapisan awal.', 'points' => 10, 'feedback' => '[TEPAT] Pusat khidmat pelanggan bertindak sebagai barisan pertahanan fizikal sebelum panggilan tiba ke pihak pengurusan.'],
                        ['text' => 'Bantah. Ia menyusahkan vendor dan agensi lain menghubungi kakitangan.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Kemudahan perlu diseimbangkan dengan keselamatan (Convenience vs Security).'],
                        ['text' => 'Papar semua nombor terus kakitangan termasuk nombor bimbit peribadi mereka.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Anda telah menghidangkan "Menu Penipuan" emas kepada semua sindiket scam negara!']
                    ]],
                ['level' => 13, 'text' => 'FASA 13: Anda perlu menetapkan "Safe Word" atau Protokol Pengesahan Dalaman sekiranya Pengarah benar-benar perlu kelulusan cemas di luar negara.',
                    'options' => [
                        ['text' => 'Wajibkan panggilan video ringkas (FaceTime/Teams) atau penggunaan Kod Kelulusan Dinamik bulanan.', 'points' => 10, 'feedback' => '[TEPAT] Deepfake suara wujud, tetapi pemalsuan video masa nyata interaktif lebih sukar bagi scammer jalanan.'],
                        ['text' => 'Guna soalan rahsia (Nama haiwan peliharaan pertama).', 'points' => 5, 'feedback' => '[KURANG TEPAT] Jawapan soalan rahsia sering dikongsi secara terbuka oleh VIP di Instagram mereka.'],
                        ['text' => 'Cukup kenal melalui intonasi suara di telefon.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Teknologi AI masa kini boleh meniru suara (Voice Cloning) mana-mana individu dengan ketepatan 99%!']
                    ]],
                ['level' => 14, 'text' => 'FASA 14: Laporan polis (Report) perlu dibuat mengenai penyamaran ini. Apakah maklumat kritikal untuk disertakan?',
                    'options' => [
                        ['text' => 'Log panggilan (CDR), masa panggilan yang tepat, dan rakaman suara jika ada.', 'points' => 10, 'feedback' => '[TEPAT] Ini membantu agensi SKMM berhubung dengan syarikat telekomunikasi menjejak dalang (Spoofing traces).'],
                        ['text' => 'Hanya nombor telefon yang dipaparkan di skrin.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Nombor itu selalunya palsu (Spoofed) dan milik individu tidak bersalah.'],
                        ['text' => 'Nama Dato Pengarah untuk polis menyiasat beliau.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Tindakan dangkal. Pengarah adalah nama mangsa yang dipergunakan, bukan suspek penjenayah!']
                    ]],
                ['level' => 15, 'text' => 'FASA 15: Sebagai kesimpulan, ancaman Vishing mensasarkan satu kerentanan yang tidak boleh ditampal dengan perisian komputer. Apakah ia?',
                    'options' => [
                        ['text' => 'Sifat empati manusia (keinginan untuk membantu) dan kepatuhan melulu kepada hierarki (Authority).', 'points' => 10, 'feedback' => '[TEPAT] "Human Firewall" yang kuat adalah mereka yang patuh pada polisi keselamatan walaupun di bawah tekanan emosi.'],
                        ['text' => 'Kelemahan sistem operasi Windows.', 'points' => 5, 'feedback' => '[KURANG TEPAT] Vishing berjaya tidak kira OS apa yang anda gunakan, hatta di atas kertas sekalipun.'],
                        ['text' => 'Tahap kelajuan internet di pejabat.', 'points' => 0, 'feedback' => '[RALAT KRITIKAL] Kelemahan logik paling teruk. Penilaian sistem anda dicurigai!']
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