<!DOCTYPE html>
<html lang="tr" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mioly Ekosistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #1a1a1b;
            -webkit-font-smoothing: antialiased;
        }

        .dark-card {
            background: #0a0a0b;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .dark-card:hover {
            transform: translateY(-6px) scale(1.01);
            background: #0f0f12;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .mesh-light {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.02) 0%, transparent 50%);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #000000 0%, #4b5563 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        /* SHIMMER: Left-to-Right sweep (Starts at M, ends at Y) */
        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .shimmer-text {
            background: linear-gradient(110deg,
                    rgba(0, 0, 0, 0.05) 0%,
                    rgba(0, 0, 0, 0.05) 45%,
                    rgba(255, 255, 255, 0.8) 50%,
                    rgba(0, 0, 0, 0.05) 55%,
                    rgba(0, 0, 0, 0.05) 100%);
            background-size: 500% auto;
            /* Larger size for a cleaner sweep */
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 6s infinite ease-out;
        }

        /* HARD RESET TYPEWRITER DOTS - EMERALD COLOR */
        @keyframes typewriter-1 {

            0%,
            24% {
                opacity: 0;
            }

            25%,
            85% {
                opacity: 1;
            }

            86%,
            100% {
                opacity: 0;
            }
        }

        @keyframes typewriter-2 {

            0%,
            49% {
                opacity: 0;
            }

            50%,
            85% {
                opacity: 1;
            }

            86%,
            100% {
                opacity: 0;
            }
        }

        @keyframes typewriter-3 {

            0%,
            74% {
                opacity: 0;
            }

            75%,
            85% {
                opacity: 1;
            }

            86%,
            100% {
                opacity: 0;
            }
        }

        .dot {
            display: inline-block;
            color: #10b981;
            /* Emerald-500 */
        }

        .dot-1 {
            animation: typewriter-1 2s infinite;
        }

        .dot-2 {
            animation: typewriter-2 2s infinite;
        }

        .dot-3 {
            animation: typewriter-3 2s infinite;
        }

        /* Heartbeat Animation */
        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }

            14% {
                transform: scale(1.3);
            }

            28% {
                transform: scale(1);
            }

            42% {
                transform: scale(1.2);
            }

            70% {
                transform: scale(1);
            }
        }

        .heart-beat {
            display: inline-block;
            animation: heartbeat 1.4s ease-in-out infinite;
            color: #ef4444;
            transform-origin: center;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-6 selections:bg-violet-100">

    <div class="mesh-light"></div>

    <!-- Header Section -->
    <header class="w-full max-w-4xl mb-20 text-center px-4 relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-12">
            <span
                class="text-[120px] font-black tracking-tighter select-none pointer-events-none uppercase shimmer-text">MIOLY</span>
        </div>

        <div
            class="inline-flex items-center gap-2 px-6 py-1.5 bg-black rounded-full mb-10 shadow-xl shadow-black/5 hover:scale-105 transition-transform cursor-default">
            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white px-1">SOLUTIONS</span>
        </div>

        <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-8 leading-none">
            <span class="hero-gradient">Hub</span><span class="ml-2 font-bold whitespace-nowrap">
                <span class="dot dot-1">.</span><span class="dot dot-2">.</span><span class="dot dot-3">.</span>
            </span>
        </h1>

        <p class="text-gray-400 text-lg font-light leading-relaxed max-w-2xl mx-auto border-l border-gray-100 pl-8">
            Dijital geleceğinizi şekillendiren, yüksek performanslı ve <br class="hidden md:block"> butik bir yazılım
            ekosistemi.
        </p>
    </header>

    <!-- Compact 2+2 Grid -->
    <main class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-6 px-4">

        <!-- MIONEX -->
        <a href="{{ route('dashboard') }}"
            class="dark-card rounded-[2rem] p-8 md:p-10 group flex flex-col justify-between min-h-[300px]">
            <div>
                <div class="flex justify-between items-start mb-8 md:mb-10">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white border border-white/10 group-hover:bg-violet-600 transition-all duration-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div
                        class="flex items-center gap-2 px-3 py-1 bg-emerald-500/10 rounded-full border border-emerald-500/20">
                        <span class="indicator bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Aktif
                            Sistem</span>
                    </div>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-2 md:mb-3 tracking-tight">MIONEX</h2>
                <p class="text-sm md:text-base text-gray-500 font-light leading-relaxed">Merkezi Müşteri ve Finans
                    Yönetimi.</p>
            </div>
            <div class="mt-8 flex items-center justify-between border-t border-white/5 pt-6">
                <span class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em]">Sürüm v1.0</span>
                <span class="text-white text-sm group-hover:translate-x-3 transition-transform">&rarr;</span>
            </div>
        </a>

        <!-- BETAFY -->
        <div
            class="dark-card rounded-[2rem] p-8 md:p-10 flex flex-col justify-between min-h-[300px] opacity-90 cursor-default">
            <div>
                <div class="flex justify-between items-start mb-8 md:mb-10">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white/40 border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-[9px] px-3 py-1 bg-white/5 text-gray-400 rounded-full border border-white/10 uppercase font-black tracking-widest italic">Yakında</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-2 md:mb-3 tracking-tight">BETAFY</h2>
                <p class="text-sm md:text-base text-gray-500 font-light leading-relaxed">Test Uzmanı ve Beta Motoru.</p>
            </div>
            <div class="mt-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[8px] text-gray-700 font-bold uppercase tracking-widest">Gelişme</span>
                    <span class="text-[8px] text-gray-700 font-bold">65%</span>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-[65%]"></div>
                </div>
            </div>
        </div>

        <!-- Rowfy -->
        <div
            class="dark-card rounded-[2rem] p-8 md:p-10 flex flex-col justify-between min-h-[300px] opacity-90 cursor-default">
            <div>
                <div class="flex justify-between items-start mb-8 md:mb-10">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white/40 border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                            </path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-2 md:mb-3 tracking-tight">Rowfy</h2>
                <p class="text-sm md:text-base text-gray-500 font-light leading-relaxed italic">Sizi rahatlatacak.</p>
            </div>
            <div class="mt-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[8px] text-gray-700 font-bold uppercase tracking-widest">Gelişme</span>
                    <span class="text-[8px] text-gray-700 font-bold">15%</span>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-[15%]"></div>
                </div>
            </div>
        </div>

        <!-- Future -->
        <div
            class="dark-card rounded-[2rem] p-8 md:p-10 border-dashed border-white/10 flex items-center justify-center opacity-30">
            <div class="text-center">
                <div
                    class="w-10 h-10 border-2 border-dashed border-white/10 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <span class="w-1.5 h-1.5 bg-white/20 rounded-full animate-ping"></span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/50">Tasarlanıyor</span>
            </div>
        </div>

    </main>

    <!-- Final Footer -->
    <footer class="mt-32 w-full max-w-4xl flex justify-between items-center px-4 py-12 border-t border-gray-100">
        <div class="text-[9px] font-black text-gray-400 uppercase tracking-[0.5em]">
            &copy; {{ date('Y') }} MIOLY HUB
        </div>
        <div
            class="text-[13px] font-bold text-gray-400 tracking-wider flex items-center gap-1.5 opacity-80 hover:opacity-100 transition-opacity cursor-default">
            We <span class="heart-beat text-2xl text-red-500">&hearts;</span> MIOLY
        </div>
    </footer>

</body>

</html>