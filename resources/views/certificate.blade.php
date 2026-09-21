<!DOCTYPE html>
<html lang="{{ request('lang') === 'en' ? 'en' : 'ms' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ request('lang') === 'en' ? 'Certificate of Achievement' : 'Sijil Pencapaian' }} - {{ Auth::user()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <style>
        @page { size: A4 landscape; margin: 0; }
        
        body { 
            background-color: #0f2818; 
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .certificate-container {
            width: 297mm;
            height: 209mm;
            position: relative;
            padding: 12mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            box-sizing: border-box;
            overflow: hidden;
            background-color: #f0fdf4; 
            background-image: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M50 0 L58 35 L93 35 L65 57 L75 93 L50 72 L25 93 L35 57 L7 35 L42 35 Z" fill="%23d4af37" fill-opacity="0.03" transform="rotate(45 50 50)"/><path d="M0 0 L100 100" stroke="%23d4af37" stroke-width="0.1" stroke-opacity="0.05"/><path d="M100 0 L0 100" stroke="%23d4af37" stroke-width="0.1" stroke-opacity="0.05"/></svg>');
            background-repeat: repeat;
        }

        .certificate-border {
            border: 6px solid #10b981; 
            padding: 4px;
            height: 100%;
            position: relative;
            box-sizing: border-box;
            z-index: 10;
        }
        
        .certificate-inner-border {
            border: 2px solid #d4af37; 
            height: 100%;
            padding: 8mm;
            text-align: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            background: rgba(240, 253, 244, 0.85); 
        }

        .font-serif-classic { font-family: 'Playfair Display', serif; }

        @media print {
            html, body { width: 297mm; height: 210mm; margin: 0; padding: 0; background-color: #fff; overflow: hidden; }
            .certificate-container { width: 297mm; height: 209mm; padding: 12mm; margin: 0; box-shadow: none; border: none; page-break-after: avoid; page-break-inside: avoid; }
            .no-print { display: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>

    @php
        $isEng = request('lang') === 'en';
        
        // 1. Calculate Standard Core Score
        $coreScore = isset($userScore) ? 
            (($userScore->level_1_score ?? 0) + 
             ($userScore->level_2_score ?? 0) + 
             ($userScore->level_3_score ?? 0) + 
             ($userScore->level_4_score ?? 0) + 
             ($userScore->level_5_score ?? 0)) : 0;

        $isCustomRoom = request()->has('custom_room');
        $customRoomScore = 0;

        // 2. 🔥 DYNAMIC CUSTOM ROOM SCORE FETCHER 🔥
        if ($isCustomRoom) {
            $customRoomId = request('custom_room');
            $customScores = is_string($userScore->custom_room_scores ?? '')
                ? json_decode($userScore->custom_room_scores, true)
                : ($userScore->custom_room_scores ?? []);

            $customRoomScore = $customScores[$customRoomId] ?? 0;
        }
    @endphp

    <div class="fixed top-5 right-5 no-print flex gap-3 z-50">
        <a href="{{ route('dashboard') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 transition font-serif-classic text-sm flex items-center">
            {{ $isEng ? 'Back to Dashboard' : 'Kembali ke Papan Pemuka' }}
        </a>
        
        <a href="{{ request()->fullUrlWithQuery(['lang' => $isEng ? 'ms' : 'en']) }}" class="bg-[#d4af37] text-white font-bold px-4 py-2 rounded-lg shadow-lg hover:bg-[#b8962e] transition flex items-center gap-2 font-serif-classic text-sm uppercase">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
            {{ $isEng ? 'Bahasa Melayu' : 'English' }}
        </a>

        <button onclick="window.print()" class="bg-[#064e3b] text-white font-bold px-6 py-2 rounded-lg shadow-lg hover:bg-[#047857] transition flex items-center gap-2 font-serif-classic text-sm uppercase">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            {{ $isEng ? 'Print Certificate' : 'Cetak Sijil' }}
        </button>
    </div>

    <div class="certificate-container text-gray-800">
        
        <svg class="absolute bottom-0 left-0 w-[140mm] h-[70mm] z-0 opacity-80" viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg">
            <polygon points="0,170 80,170 100,190 250,190 250,200 0,200" fill="#d4af37" opacity="0.6"/>
            <polygon points="0,130 120,130 150,160 300,160 300,175 135,175 105,145 0,145" fill="#d4af37" opacity="0.3"/>
            <polyline points="0,30 100,30 120,50 350,50" fill="none" stroke="#d4af37" stroke-width="2.5"/>
            <circle cx="350" cy="50" r="4.5" fill="#d4af37"/>
            <rect x="90" y="27" width="6" height="6" fill="#d4af37"/>
            <polyline points="0,60 180,60 200,80 280,80" fill="none" stroke="#d4af37" stroke-width="1.5" opacity="0.8"/>
            <circle cx="280" cy="80" r="3" fill="#d4af37" opacity="0.8"/>
            <polyline points="0,90 60,90 80,110 220,110" fill="none" stroke="#d4af37" stroke-width="3"/>
            <circle cx="220" cy="110" r="5" fill="#d4af37"/>
            <polyline points="0,110 40,110 50,120 180,120" fill="none" stroke="#d4af37" stroke-width="1"/>
            <circle cx="180" cy="120" r="2.5" fill="#d4af37"/>
            <rect x="150" y="140" width="10" height="2" fill="#d4af37"/>
            <rect x="165" y="140" width="4" height="2" fill="#d4af37"/>
            <rect x="175" y="140" width="20" height="2" fill="#d4af37"/>
            <rect x="200" y="140" width="3" height="2" fill="#d4af37"/>
            <rect x="210" y="140" width="15" height="2" fill="#d4af37"/>
            <rect x="230" y="140" width="2" height="2" fill="#d4af37"/>
            <rect x="40" y="155" width="8" height="2" fill="#d4af37" opacity="0.6"/>
            <rect x="52" y="155" width="15" height="2" fill="#d4af37" opacity="0.6"/>
            <rect x="70" y="155" width="4" height="2" fill="#d4af37" opacity="0.6"/>
        </svg>

        <svg class="absolute top-0 right-0 w-[140mm] h-[70mm] z-0 opacity-80 rotate-180" viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg">
            <polygon points="0,170 80,170 100,190 250,190 250,200 0,200" fill="#d4af37" opacity="0.6"/>
            <polygon points="0,130 120,130 150,160 300,160 300,175 135,175 105,145 0,145" fill="#d4af37" opacity="0.3"/>
            <polyline points="0,30 100,30 120,50 350,50" fill="none" stroke="#d4af37" stroke-width="2.5"/>
            <circle cx="350" cy="50" r="4.5" fill="#d4af37"/>
            <rect x="90" y="27" width="6" height="6" fill="#d4af37"/>
            <polyline points="0,60 180,60 200,80 280,80" fill="none" stroke="#d4af37" stroke-width="1.5" opacity="0.8"/>
            <circle cx="280" cy="80" r="3" fill="#d4af37" opacity="0.8"/>
            <polyline points="0,90 60,90 80,110 220,110" fill="none" stroke="#d4af37" stroke-width="3"/>
            <circle cx="220" cy="110" r="5" fill="#d4af37"/>
            <polyline points="0,110 40,110 50,120 180,120" fill="none" stroke="#d4af37" stroke-width="1"/>
            <circle cx="180" cy="120" r="2.5" fill="#d4af37"/>
            <rect x="150" y="140" width="10" height="2" fill="#d4af37"/>
            <rect x="165" y="140" width="4" height="2" fill="#d4af37"/>
            <rect x="175" y="140" width="20" height="2" fill="#d4af37"/>
            <rect x="200" y="140" width="3" height="2" fill="#d4af37"/>
            <rect x="210" y="140" width="15" height="2" fill="#d4af37"/>
            <rect x="230" y="140" width="2" height="2" fill="#d4af37"/>
            <rect x="40" y="155" width="8" height="2" fill="#d4af37" opacity="0.6"/>
            <rect x="52" y="155" width="15" height="2" fill="#d4af37" opacity="0.6"/>
            <rect x="70" y="155" width="4" height="2" fill="#d4af37" opacity="0.6"/>
        </svg>

        <div class="certificate-border">
            <div class="certificate-inner-border relative">
                
                <div class="absolute inset-0 flex justify-center items-center pointer-events-none z-0" style="opacity: 0.15;">
                    <img src="{{ asset('img/sporton.jpg') }}" alt="Spartan Background" class="max-h-[85%] object-contain">
                </div>

                <div class="relative z-10 flex justify-center mb-2">
                    <img src="{{ asset('img/logo2.png') }}" alt="LZNK Logo" class="h-20 object-contain">
                </div>

                <div class="relative z-10 mb-6">
                    <h1 class="text-4xl md:text-5xl font-bold font-serif-classic text-[#d4af37] tracking-widest uppercase mb-1">
                        {{ $isEng ? 'CERTIFICATE OF ACHIEVEMENT' : 'SIJIL PENCAPAIAN' }}
                    </h1>
                    <p class="text-sm font-bold tracking-widest text-[#064e3b] uppercase mt-2">
                        {{ $isEng ? 'LZNK Cyber Security Center' : 'Pusat Keselamatan Siber LZNK' }}
                    </p>
                </div>

                <div class="relative z-10 max-w-3xl mx-auto space-y-3">
                    <p class="text-sm font-serif-classic">
                        {{ $isEng ? 'This is to certify that' : 'Dengan ini disahkan bahawa' }}
                    </p>
                    
                    <h2 class="text-4xl font-bold font-serif-classic text-[#064e3b] border-b-2 border-gray-400 pb-1 inline-block px-10 uppercase tracking-wider">
                        {{ Auth::user()->name }}
                    </h2>
                    
                    <p class="text-sm font-serif-classic font-bold text-gray-600 mt-1 uppercase tracking-widest">
                        {{ $isEng ? 'Staff ID: ' : 'ID Staf: ' }} {{ Auth::user()->agent_id ?? (Auth::user()->staff_id ?? ($isEng ? 'No ID' : 'Tiada ID')) }}
                    </p>
                    
                    <p class="text-sm font-serif-classic mt-6">
                        {{ $isEng ? 'has successfully completed and passed the assessment with excellence for the module :' : 'telah berjaya melengkapkan dan melepasi tahap penilaian cemerlang bagi modul :' }}
                    </p>
                    
                    <h3 class="text-xl md:text-2xl font-bold font-serif-classic text-[#064e3b] uppercase tracking-wide mt-2 mb-4">
                        "{{ $roomTitle ?? 'LZNK CAKNA SIBER ESCAPE ROOM : S.H.I.E.L.D' }}"
                    </h3>

                    <div class="mt-8 space-y-1">
                        <p class="text-sm font-serif-classic italic text-gray-600">
                            @if($isCustomRoom)
                                {{ $isEng ? 'With a Special Operations Score of: ' : 'Dengan Markah Operasi Khas: ' }}
                                <span class="font-bold text-emerald-600 text-xl">{{ $customRoomScore }} {{ $isEng ? 'Points' : 'Mata' }}</span>
                            @elseif(request()->has('room') || request()->has('arcade'))
                                {{ $isEng ? 'Achievement Status: ' : 'Status Pencapaian: ' }}
                                <span class="font-bold text-emerald-600 text-xl tracking-widest uppercase">{{ $isEng ? '100% COMPLETED' : '100% SELESAI' }}</span>
                            @else
                                {{ $isEng ? 'With an Achievement Score of: ' : 'Dengan Markah Pencapaian: ' }}
                                <span class="font-bold text-emerald-600 text-xl">{{ $coreScore }} {{ $isEng ? 'Points' : 'Mata' }}</span>
                            @endif
                        </p>
                        
                        <p class="text-base font-serif-classic text-gray-900 font-semibold italic mt-4">
                            {{ $isEng ? 'Dated on : ' : 'Pada Tarikh : ' }} {{ isset($userScore->updated_at) ? $userScore->updated_at->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="relative z-10 flex justify-between items-end mt-2 px-10 pb-2">
                    
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg viewBox="0 0 100 100" class="absolute w-full h-full text-emerald-600 drop-shadow-md" fill="currentColor">
                            <path d="M50 0L57.7 8.1L68.5 5.9L73 15.6L83.5 17L84.3 27.8L93.4 32.5L90.5 42.7L97.4 50L90.5 57.3L93.4 67.5L84.3 72.2L83.5 83L73 84.4L68.5 94.1L57.7 91.9L50 100L42.3 91.9L31.5 94.1L27 84.4L16.5 83L15.7 72.2L6.6 67.5L9.5 57.3L2.6 50L9.5 42.7L6.6 32.5L15.7 27.8L16.5 17L27 15.6L31.5 5.9L42.3 8.1L50 0Z"/>
                        </svg>
                        <div class="absolute text-white text-center font-bold text-[10px] uppercase font-serif-classic leading-tight mt-1">
                            @if($isEng)
                                VERIFIED BY<br>SECURITY<br>AND INFORMATION<br>ASSURANCE<br>DIVISION
                            @else
                                DISAHKAN OLEH<br>DIVISYEN<br>KESELAMATAN<br>DAN JAMINAN<br>MAKLUMAT
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="border-t-2 border-[#064e3b] mt-20 pt-2 px-2 inline-block text-center max-w-[320px]">
                            <p class="font-bold text-[13px] text-gray-900 font-serif-classic">Yang Berbahagia Dato' Syeikh Zakaria Bin Othman, DSDK.,AMK.</p>
                            <p class="text-[11px] text-[#064e3b] font-bold uppercase mt-1">
                                {{ $isEng ? 'Chief Executive Officer' : 'Ketua Pegawai Eksekutif' }}
                            </p>
                            <p class="text-[11px] text-gray-700">Lembaga Zakat Negeri Kedah Darul Aman</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@include('partials.cursor')
</body>
</html>