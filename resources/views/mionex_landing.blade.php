<!DOCTYPE html>
<html lang="tr" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MIONEX | Yeni Nesil Fintech Platformu</title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;700;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Animation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            nebula: {
                                start: '#6B1B3D',
                                end: '#3D1B3D',
                                dark: '#150A1A',
                            },
                            light: {
                                bg: '#F8FAFC',
                                surface: '#FFFFFF',
                                text: '#1E293B',
                            },
                            electric: '#C72D52',
                            gold: '#C72D52',
                            glass: 'rgba(255, 255, 255, 0.1)',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'gradient-fast': 'gradient 8s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --bg-light: #F8FAFC;
            --bg-dark: #150A1A;
            --text-light: #1E293B;
            --text-dark: #FFFFFF;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-light);
            transition: background-color 0.5s ease, color 0.5s ease;
            overflow-x: hidden;
        }

        .dark body {
            background: #0f1115;
            color: var(--text-dark);
        }

            /* Code Typing Cursor */
            .typing-cursor::after {
                content: '|';
                animation: blink 1s step-end infinite;
                margin-left: 2px;
                color: #C72D52;
                font-weight: bold;
            }

            @keyframes blink {
                from, to { opacity: 1; }
                50% { opacity: 0; }
            }

        .glass-mionex {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .dark .glass-mionex {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-gradient-red {
            background: linear-gradient(to right, #C72D52, #A82244);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Animated Mesh BG */
        .mesh-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, rgba(0, 0, 0, 0.02) 0%, transparent 60%),
                radial-gradient(circle at 10% 10%, rgba(0, 0, 0, 0.03) 0%, transparent 30%);
            filter: blur(40px);
            transition: opacity 0.5s ease;
        }

        .dark .mesh-bg {
            background: none;
            opacity: 0;
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .stylistic-num {
            font-size: 8rem;
            font-weight: 900;
            color: transparent;
            -webkit-text-stroke: 1px rgba(0, 0, 0, 0.05);
            line-height: 1;
        }

        .dark .stylistic-num {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        .dark ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: #E63946;
            border-radius: 10px;
        }
    </style>
</head>

<body x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
}" x-init="document.documentElement.classList.toggle('dark', darkMode)"
    class="font-sans antialiased bg-brand-light-bg dark:bg-brand-nebula-dark text-brand-light-text dark:text-white">

    <!-- Navigation -->
    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)"
        :class="scrolled ? 'bg-white/90 dark:bg-brand-nebula-dark/90 backdrop-blur-lg border-b border-black/5 dark:border-white/5 py-4' : 'bg-transparent py-6'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between gap-8">
            <a href="#" class="flex items-center gap-3 group">
                <img :src="darkMode ? '{{ asset('assets/img/nexwhite.png') }}' : '{{ asset('assets/img/nexblack.png') }}'"
                    alt="MIONEX Logo" class="h-8 w-auto">
                <div class="relative pr-10">
                    <span
                        class="text-xl font-black tracking-tighter text-brand-light-text dark:text-white leading-none uppercase">MIONEX</span>
                    <span
                        class="absolute -top-2 -right-0 bg-[#C72D52] text-[8px] font-black text-white px-1.5 py-0.5 rounded-md leading-none">BETA</span>
                </div>
            </a>

            <div class="hidden md:flex flex-1 justify-center items-center gap-10">
                <a href="#features"
                    class="text-xs font-bold uppercase tracking-widest text-brand-light-text/70 dark:text-[#E8E8E8] hover:text-brand-electric transition-colors">Özellikler</a>
                <a href="#process"
                    class="text-xs font-bold uppercase tracking-widest text-brand-light-text/70 dark:text-[#E8E8E8] hover:text-brand-electric transition-colors">İncele</a>
                <a href="#pricing"
                    class="text-xs font-bold uppercase tracking-widest text-brand-light-text/70 dark:text-[#E8E8E8] hover:text-brand-electric transition-colors">Fiyatlandırma</a>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Toggle -->
                <button @click="toggleTheme()"
                    class="p-2 rounded-xl bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10 transition-colors text-brand-light-text dark:text-white">
                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.243 17.657l-.707-.707M7.636 7.636l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                <a href="{{ route('login') }}"
                    class="bg-[#C72D52] hover:bg-[#A82244] px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest text-white shadow-lg shadow-[#C72D52]/20 transition-all active:scale-95 whitespace-nowrap">
                    Ücretsiz Dene
                </a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Section 1: Hero -->
        <section id="hero" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
            <div class="mesh-bg"></div>

            <div
                class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10 w-full">
                <!-- Content -->
                <div class="text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 mb-6 reveal active">
                        <span class="h-2 w-2 rounded-full bg-[#C72D52] animate-pulse"></span>
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-brand-light-text/60 dark:text-white/60">MIONEX
                            v1.0 PREMIUM BETA</span>
                    </div>

                    <h1
                        class="text-5xl md:text-7xl lg:text-8xl font-black leading-[0.9] tracking-tighter mb-4 reveal active text-brand-light-text dark:text-white min-h-[2.1em]">
                        FİNANSAL <br>
                        <span id="typed-text" class="text-[#C72D52] inline-block">MİMARİ.</span>
                    </h1>

                    <p
                        class="text-base md:text-lg text-brand-light-text/60 dark:text-[#E8E8E8]/80 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0 reveal active [transition-delay:200ms]">
                        Kurumsal finans süreçlerinizi optimize eden, yüksek performanslı ve güvenli yönetim altyapısı.
                        Modern mühendislik ile finansal hassasiyeti birleştirin.
                    </p>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 reveal active [transition-delay:400ms]">
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto bg-[#C72D52] hover:bg-[#A82244] px-10 py-4 rounded-xl text-sm font-black uppercase tracking-widest text-white transition-all hover:scale-105 active:scale-95 shadow-xl shadow-[#C72D52]/20">
                            Ücretsiz Demoyu İncele
                        </a>
                        <a href="#features"
                            class="w-full sm:w-auto px-10 py-4 rounded-xl text-sm font-black uppercase tracking-widest text-brand-light-text dark:text-white border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 transition-all text-center">
                            Özellikleri Keşfet
                        </a>
                    </div>
                </div>

                <!-- Mockup -->
                <div class="relative reveal active [transition-delay:600ms]" id="hero-mockup">
                    <div class="absolute -inset-10 bg-brand-electric/10 blur-[100px] rounded-full"></div>
                    <div class="relative glass-mionex rounded-[2rem] p-4 shadow-2xl overflow-hidden group">
                        <img src="{{ asset('assets/img/dashboard.png') }}" alt="MIONEX Dashboard Panel"
                            class="rounded-2xl opacity-95 group-hover:opacity-100 transition-all duration-700 hover:scale-[1.02]">
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50">
                <span
                    class="text-[8px] font-black uppercase tracking-[1em] text-brand-light-text dark:text-white">Kaydır</span>
                <div class="h-12 w-[1px] bg-gradient-to-b from-brand-electric to-transparent"></div>
            </div>
        </section>

        <!-- Section 2: Technical Infrastructure -->
        <section id="technical" class="py-32 relative overflow-hidden bg-white/50 dark:bg-black/20">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="reveal">
                        <h2
                            class="text-4xl md:text-5xl font-black tracking-tighter mb-8 text-brand-light-text dark:text-white">
                            İHTİYAÇTAN DOĞAN <br> <span class="text-[#C72D52]">SADELİK.</span>
                        </h2>
                        <p class="text-base text-brand-light-text/60 dark:text-white/60 leading-relaxed mb-10">
                            MIONEX, karmaşık ve gereksiz özelliklerden arındırılmış, tamamen işletme sahiplerinin günlük 
                            ihtiyaçları gözlemlenerek geliştirilmiş sade bir yönetim yapısı sunar.
                        </p>

                        <div class="space-y-8">
                            <div class="flex gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-2xl bg-[#C72D52]/10 flex items-center justify-center border border-[#C72D52]/20">
                                    <svg class="h-6 w-6 text-[#C72D52]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-brand-light-text dark:text-white">Double-Entry Ledger</h4>
                                    <p class="text-sm text-brand-light-text/50 dark:text-white/40">Global finans standartlarında çift kayıtlı defter mimarisi ile %100 mutabakat garantisi.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-2xl bg-[#C72D52]/10 flex items-center justify-center border border-[#C72D52]/20">
                                    <svg class="h-6 w-6 text-[#C72D52]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-brand-light-text dark:text-white">Audit Log & İzlenebilirlik</h4>
                                    <p class="text-sm text-brand-light-text/50 dark:text-white/40">Sistemdeki her bir veri değişimi `ActivityLog` katmanında zaman damgası ve IP kaydı ile arşivlenir.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-2xl bg-[#C72D52]/10 flex items-center justify-center border border-[#C72D52]/20">
                                    <svg class="h-6 w-6 text-[#C72D52]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-brand-light-text dark:text-white">Asenkron Görev Yönetimi</h4>
                                    <p class="text-sm text-brand-light-text/50 dark:text-white/40">Yüksek hacimli veri işlemleri `Jobs` kuyruk yapısı ile arka planda, sistem performansını etkilemeden işlenir.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="reveal relative [transition-delay:200ms]">
                        <div class="absolute -inset-4 bg-[#C72D52]/5 blur-3xl rounded-[3rem]"></div>
                        <div
                            class="relative glass-mionex p-8 rounded-[2.5rem] border border-black/5 dark:border-white/10 shadow-3xl bg-white/70 dark:bg-black/40">
                            <pre
                                class="text-[10px] md:text-xs font-mono text-[#C72D52]/80 dark:text-white leading-relaxed overflow-x-auto">
<code id="code-snippet" class="typing-cursor">// MIONEX Financial Architecture Content Loading...</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Section 3: Platform Modülleri (Detailed Grid) -->
        <section id="features" class="py-32 relative border-b border-black/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-24">
                    <h2
                        class="text-4xl md:text-6xl font-black tracking-tighter reveal text-brand-light-text dark:text-white">
                        SİSTEM <span class="text-[#C72D52]">MODÜLLERİ.</span>
                    </h2>
                    <p class="mt-4 text-brand-light-text/50 dark:text-white/50 reveal [transition-delay:200ms]">
                        Finansal operasyonlarınızı uçtan uca yöneten bağımsız ve entegre katmanlar.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Cari Yönetim -->
                    <div class="reveal group">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-[#C72D52]/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-brand-light-text dark:text-white">Cari Yönetim</h3>
                            <p class="text-sm text-brand-light-text/60 dark:text-white/40 leading-relaxed mb-6">
                                Müşteri yaşam döngüsü ve risk analizi. Borç/alacak limit kontrolü ve otomatik mutabakat
                                algoritmaları ile ticari ilişkilerinizi dijitalleştirin.
                            </p>
                            <ul class="space-y-3 text-xs text-brand-light-text/50 dark:text-white/30">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Bakiye Yaşlandırma Analizi
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Dinamik Limit Tanımlama
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tedarikçiler -->
                    <div class="reveal group [transition-delay:100ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-[#C72D52]/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-brand-light-text dark:text-white">Tedarikçiler</h3>
                            <p class="text-sm text-brand-light-text/60 dark:text-white/40 leading-relaxed mb-6">
                                Tedarik zinciri ve alım yönetimi. Tedarikçi performans skalası ve alacak takibi ile
                                operasyonel verimliliği en üst düzeye çıkarın.
                            </p>
                            <ul class="space-y-3 text-xs text-brand-light-text/50 dark:text-white/30">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Vade ve Ödeme Optimizasyonu
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Tedarikçi Risk Skorlaması
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Hizmet Yönetimi -->
                    <div class="reveal group [transition-delay:200ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-[#C72D52]/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-brand-light-text dark:text-white">Hizmet Yönetimi
                            </h3>
                            <p class="text-sm text-brand-light-text/60 dark:text-white/40 leading-relaxed mb-6">
                                Hizmet kataloglama ve maliyet analizi. Belirlenen hizmetlerin karlılık oranlarını anlık
                                olarak izleyin ve iş süreçlerinizi kurgulayın.
                            </p>
                            <ul class="space-y-3 text-xs text-brand-light-text/50 dark:text-white/30">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Marj ve Karlılık Takibi
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Operasyonel İş Emirleri
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Finans Merkezi -->
                    <div class="reveal group [transition-delay:300ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-[#C72D52]/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-brand-light-text dark:text-white">Finans Merkezi
                            </h3>
                            <p class="text-sm text-brand-light-text/60 dark:text-white/40 leading-relaxed mb-6">
                                Merkezi nakit akışı ve hazine yönetimi. Kasa, banka ve pos hareketlerini entegre bir
                                şekilde izleyerek likidite kontrolünü elinizde tutun.
                            </p>
                            <ul class="space-y-3 text-xs text-brand-light-text/50 dark:text-white/30">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Anlık Nakit Akış İzleme
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Banka & POS Entegrasyonu
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Teklif Havuzu -->
                    <div class="reveal group [transition-delay:400ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-[#C72D52]/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-brand-light-text dark:text-white">Teklif Havuzu</h3>
                            <p class="text-sm text-brand-light-text/60 dark:text-white/40 leading-relaxed mb-6">
                                Satış pipeline ve teklif yaşam döngüsü. Hazırlanan tekliflerin durumunu takip edin ve
                                onay süreçlerini otomatikleştirin.
                            </p>
                            <ul class="space-y-3 text-xs text-brand-light-text/50 dark:text-white/30">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Revizyon ve Versiyon Takibi
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Tekliften Faturaya Dönüşüm
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Analiz Merkezi -->
                    <div class="reveal group [transition-delay:500ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-[#C72D52]/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-brand-light-text dark:text-white">Analiz Merkezi
                            </h3>
                            <p class="text-sm text-brand-light-text/60 dark:text-white/40 leading-relaxed mb-6">
                                Veri odaklı karar destek sistemi. Özelleştirilebilir dashboardlar ve gelişmiş raporlama
                                motoru ile finansal geleceğinizi öngörün.
                            </p>
                            <ul class="space-y-3 text-xs text-brand-light-text/50 dark:text-white/30">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Görsel KPI Takip Ekranları
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Gerçek Zamanlı BI Raporları
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Nasıl Çalışır (Süreç) -->
        <section id="process" class="py-32 relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(230,57,70,0.05),transparent_40%)]">
            </div>

            <div class="max-w-7xl mx-auto px-6 text-center mb-24">
                <h2
                    class="text-5xl md:text-6xl font-black tracking-tighter reveal text-brand-light-text dark:text-white">
                    ELİT <span class="text-brand-electric">SÜREÇ.</span>
                </h2>
                <div class="mt-6 h-1 w-20 bg-brand-gold mx-auto reveal [transition-delay:200ms]"></div>
            </div>

            <div class="max-w-7xl mx-auto px-6 relative">
                <!-- Connective Line (Desktop) -->
                <div
                    class="hidden lg:block absolute top-1/2 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-black/5 dark:via-white/10 to-transparent -translate-y-1/2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-12 relative z-10">
                    <!-- Step 01 -->
                    <div class="reveal text-center group">
                        <div class="relative inline-block mb-10">
                            <span
                                class="stylistic-num absolute -top-8 left-1/2 -translate-x-1/2 group-hover:scale-110 transition-transform dark:text-white/20">01</span>
                            <div
                                class="relative z-10 bg-white dark:bg-white/5 backdrop-blur-xl p-6 rounded-3xl border border-black/5 dark:border-white/10 group-hover:border-brand-electric transition-colors shadow-2xl">
                                <svg class="h-10 w-10 text-brand-electric" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-xl font-bold mb-4 group-hover:text-brand-electric transition-colors text-brand-light-text dark:text-white">
                            Entegrasyon
                        </h3>
                        <p
                            class="text-xs text-brand-light-text/50 dark:text-white/40 leading-relaxed font-medium uppercase tracking-widest">
                            Hızlı
                            kayıt ve sistem entegrasyonu.</p>
                    </div>

                    <!-- Step 02 -->
                    <div class="reveal text-center group [transition-delay:100ms]">
                        <div class="relative inline-block mb-10">
                            <span
                                class="stylistic-num absolute -top-8 left-1/2 -translate-x-1/2 group-hover:scale-110 transition-transform dark:text-white/20">02</span>
                            <div
                                class="relative z-10 bg-white dark:bg-white/5 backdrop-blur-xl p-6 rounded-3xl border border-black/5 dark:border-white/10 group-hover:border-brand-gold transition-colors shadow-2xl">
                                <svg class="h-10 w-10 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-xl font-bold mb-4 group-hover:text-brand-gold transition-colors text-brand-light-text dark:text-white">
                            Veri Girişi</h3>
                        <p
                            class="text-xs text-brand-light-text/50 dark:text-white/40 leading-relaxed font-medium uppercase tracking-widest">
                            Varlık ve
                            borç kalemlerinin aktarımı.</p>
                    </div>

                    <!-- Step 03 -->
                    <div class="reveal text-center group [transition-delay:200ms]">
                        <div class="relative inline-block mb-10">
                            <span
                                class="stylistic-num absolute -top-8 left-1/2 -translate-x-1/2 group-hover:scale-110 transition-transform dark:text-white/20">03</span>
                            <div
                                class="relative z-10 bg-white dark:bg-white/5 backdrop-blur-xl p-6 rounded-3xl border border-black/5 dark:border-white/10 group-hover:border-brand-electric transition-colors shadow-2xl">
                                <svg class="h-10 w-10 text-brand-electric" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-xl font-bold mb-4 group-hover:text-brand-electric transition-colors text-brand-light-text dark:text-white">
                            Otomasyon
                        </h3>
                        <p
                            class="text-xs text-brand-light-text/50 dark:text-white/40 leading-relaxed font-medium uppercase tracking-widest">
                            Finansal
                            motorun devreye girmesi.</p>
                    </div>

                    <!-- Step 04 -->
                    <div class="reveal text-center group [transition-delay:300ms]">
                        <div class="relative inline-block mb-10">
                            <span
                                class="stylistic-num absolute -top-8 left-1/2 -translate-x-1/2 group-hover:scale-110 transition-transform dark:text-white/20">04</span>
                            <div
                                class="relative z-10 bg-white dark:bg-white/5 backdrop-blur-xl p-6 rounded-3xl border border-black/5 dark:border-white/10 group-hover:border-brand-gold transition-colors shadow-2xl">
                                <svg class="h-10 w-10 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-xl font-bold mb-4 group-hover:text-brand-gold transition-colors text-brand-light-text dark:text-white">
                            Analiz</h3>
                        <p
                            class="text-xs text-brand-light-text/50 dark:text-white/40 leading-relaxed font-medium uppercase tracking-widest">
                            Raporların ve içgörülerin oluşumu.</p>
                    </div>

                    <!-- Step 05 -->
                    <div class="reveal text-center group [transition-delay:400ms]">
                        <div class="relative inline-block mb-10">
                            <span
                                class="stylistic-num absolute -top-8 left-1/2 -translate-x-1/2 group-hover:scale-110 transition-transform dark:text-white/20">05</span>
                            <div
                                class="relative z-10 bg-white dark:bg-white/5 backdrop-blur-xl p-6 rounded-3xl border border-black/5 dark:border-white/10 group-hover:border-brand-electric transition-colors shadow-2xl">
                                <svg class="h-10 w-10 text-brand-electric" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-xl font-bold mb-4 group-hover:text-brand-electric transition-colors text-brand-light-text dark:text-white">
                            Büyüme</h3>
                        <p
                            class="text-xs text-brand-light-text/50 dark:text-white/40 leading-relaxed font-medium uppercase tracking-widest">
                            Sürdürülebilir finansal büyüme.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section 4: Platform Özellikleri (Platform Core) -->
        <section id="platform-features" class="py-32 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-24">
                    <h2
                        class="text-4xl md:text-6xl font-black tracking-tighter reveal text-brand-light-text dark:text-white">
                        PLATFORM <span class="text-brand-gold">ÇEKİRDEĞİ.</span>
                    </h2>
                    <p class="mt-4 text-brand-light-text/50 dark:text-white/50 reveal [transition-delay:200ms]">Gerçek işletme ihtiyaçları için geliştirilmiş, karmaşadan arındırılmış yönetim katmanları.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="reveal group h-full">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-brand-electric/10">
                            <div
                                class="bg-[#C72D52]/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-[#C72D52] transition-colors">
                                <svg class="h-8 w-8 text-[#C72D52] group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-6 text-brand-light-text dark:text-white">Finans Yönetimi
                            </h3>
                            <ul class="space-y-4 text-sm text-brand-light-text/60 dark:text-white/40">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Gerçek zamanlı nakit akışı
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Gider ve gelir konsolidasyonu
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Çoklu döviz desteği
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="reveal group h-full [transition-delay:200ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-brand-gold/10">
                            <div
                                class="bg-brand-gold/20 p-4 rounded-2xl w-fit mb-8 group-hover:bg-brand-gold transition-colors">
                                <svg class="h-8 w-8 text-brand-gold group-hover:text-brand-nebula-dark" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-6 text-brand-light-text dark:text-white">Müşteri İlişkileri
                            </h3>
                            <ul class="space-y-4 text-sm text-brand-light-text/60 dark:text-white/40">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-brand-electric"></span>
                                    360 derece müşteri kartı
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-brand-electric"></span>
                                    Ödeme hatırlatıcılar
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-brand-electric"></span>
                                    İletişim geçmişi ve loglar
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="reveal group h-full [transition-delay:400ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] hover:-translate-y-4 transition-all duration-500 shadow-2xl group-hover:shadow-black/10 dark:group-hover:shadow-white/10">
                            <div
                                class="bg-black/10 dark:bg-white/10 p-4 rounded-2xl w-fit mb-8 group-hover:bg-brand-light-text dark:group-hover:bg-white group-hover:text-white dark:group-hover:text-black transition-all">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-6 text-brand-light-text dark:text-white">İş Süreçleri</h3>
                            <ul class="space-y-4 text-sm text-brand-light-text/60 dark:text-white/40">
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Birim bazlı iş takibi
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Onay mekanizmaları
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-[#C72D52]"></span>
                                    Ekip içi görev dağılımı
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Section 6: Fiyatlandırma (Pricing) -->
        <section id="pricing" class="py-32 relative overflow-hidden bg-brand-light-surface/30 dark:bg-transparent">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-24">
                    <h2
                        class="text-4xl md:text-6xl font-black tracking-tighter reveal text-brand-light-text dark:text-white">
                        ELİT <span class="text-brand-gold">ERİŞİM.</span>
                    </h2>
                    <p class="mt-4 text-brand-light-text/50 dark:text-white/50 reveal [transition-delay:200ms]">Her
                        ölçekteki girişim için ölçeklenebilir fiyatlandırma.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <!-- Starter -->
                    <div class="reveal h-full group">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] flex flex-col border border-black/5 dark:border-white/5 group-hover:border-black/20 dark:group-hover:border-white/20 transition-all">
                            <span
                                class="text-xs font-bold uppercase tracking-[0.3em] text-brand-light-text/30 dark:text-white/30 mb-4">Bireysel</span>
                            <h3 class="text-3xl font-black mb-2 text-brand-light-text dark:text-white">Starter</h3>
                            <div
                                class="text-3xl font-black mb-10 tracking-tighter text-brand-light-text dark:text-white uppercase">
                                Hazırlanıyor
                            </div>
                            <ul class="space-y-4 mb-12 flex-grow">
                                <li class="flex items-center gap-3 text-sm text-brand-light-text/60 dark:text-white/60">
                                    <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    1 Domain Lisansı
                                </li>
                                <li class="flex items-center gap-3 text-sm text-brand-light-text/60 dark:text-white/60">
                                    <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Ömür Boyu Kullanım
                                </li>
                                <li
                                    class="flex items-center gap-3 text-sm text-brand-light-text/20 dark:text-white/20 italic">
                                    <svg class="h-4 w-4 text-brand-light-text/10 dark:text-white/10" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Gelişmiş Analitik
                                </li>
                            </ul>
                            <a href="{{ route('login') }}"
                                class="block text-center py-4 rounded-2xl border border-black/10 dark:border-white/10 hover:bg-[#C72D52] hover:text-white text-xs font-bold uppercase tracking-widest transition-all text-brand-light-text dark:text-white">Düzenli Kullanım İçin İncele</a>
                        </div>
                    </div>

                    <!-- Professional (Featured) -->
                    <div class="reveal h-full relative z-10 [transition-delay:200ms]">
                        <div
                            class="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#C72D52] px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest text-white shadow-xl shadow-[#C72D52]/30">
                            En Popüler
                        </div>
                        <div
                            class="h-full glass-mionex p-12 rounded-[3rem] flex flex-col border-2 border-[#C72D52] shadow-2xl shadow-[#C72D52]/10 relative overflow-hidden bg-white/80 dark:bg-zinc-900/40">
                            <div class="absolute top-0 right-0 p-8 opacity-10">
                                <svg class="h-24 w-24 text-[#C72D52]" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-[0.3em] text-brand-electric mb-4">Gelişmiş
                                Büyüme</span>
                            <h3 class="text-4xl font-black mb-2 text-brand-light-text dark:text-white">Professional</h3>
                            <div
                                class="text-4xl font-black mb-12 tracking-tighter text-brand-light-text dark:text-white uppercase">
                                Hazırlanıyor
                            </div>
                            <ul class="space-y-6 mb-12 flex-grow">
                                <li
                                    class="flex items-center gap-4 text-base font-bold text-brand-light-text dark:text-white">
                                    <svg class="h-5 w-5 text-brand-electric" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    5 Domain Lisansı
                                </li>
                                <li
                                    class="flex items-center gap-4 text-base font-bold text-brand-light-text dark:text-white">
                                    <svg class="h-5 w-5 text-brand-electric" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    White-label Kurulum
                                </li>
                                <li
                                    class="flex items-center gap-4 text-base font-bold text-brand-light-text dark:text-white">
                                    <svg class="h-5 w-5 text-brand-electric" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    1 Yıl VIP Destek
                                </li>
                            </ul>
                            <a href="{{ route('login') }}"
                                class="block text-center py-5 rounded-2xl bg-[#C72D52] hover:bg-[#A82244] text-sm font-black uppercase tracking-widest text-white transition-all scale-105 shadow-xl shadow-[#C72D52]/20 active:scale-95">
                                Tek Seferlik, Kalıcı Çözüm
                            </a>
                        </div>
                    </div>

                    <!-- Enterprise -->
                    <div class="reveal h-full group [transition-delay:400ms]">
                        <div
                            class="h-full glass-mionex p-10 rounded-[2.5rem] flex flex-col border border-black/5 dark:border-white/5 group-hover:border-black/20 dark:group-hover:border-white/20 transition-all">
                            <span
                                class="text-xs font-bold uppercase tracking-[0.3em] text-brand-light-text/30 dark:text-white/30 mb-4">Sınırsız
                                Güç</span>
                            <h3 class="text-3xl font-black mb-2 text-brand-light-text dark:text-white">Enterprise</h3>
                            <div
                                class="text-3xl font-black mb-10 tracking-tighter text-brand-light-text dark:text-white uppercase">
                                Hazırlanıyor
                            </div>
                            <ul class="space-y-4 mb-12 flex-grow text-sm text-brand-light-text/60 dark:text-white/60">
                                <li class="flex items-center gap-3">
                                    <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Sınırsız Lisanslama
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Gelişmiş API ve Entegrasyon
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="h-4 w-4 text-brand-gold" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Ömür Boyu Destek
                                </li>
                            </ul>
                            <a href="#demo"
                                class="block text-center py-4 rounded-2xl border border-black/10 dark:border-white/10 hover:bg-[#C72D52] hover:text-white text-xs font-bold uppercase tracking-widest transition-all text-brand-light-text dark:text-white">İletişime
                                Geç</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Final CTA & Footer -->
        <footer class="pt-16 pb-8 bg-[#000000] relative overflow-hidden transition-colors">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <!-- Brand -->
                    <div class="reveal">
                        <div class="flex items-center gap-3 mb-8">
                            <img src="{{ asset('assets/img/nexwhite.png') }}" alt="MIONEX Logo" class="h-8 w-auto">
                            <div class="relative pr-10">
                                <span
                                    class="text-xl font-black tracking-tighter text-white leading-none uppercase">MIONEX</span>
                                <span
                                    class="absolute -top-2 -right-0 bg-[#C72D52] text-[8px] font-black text-white px-1.5 py-0.5 rounded-md leading-none">BETA</span>
                            </div>
                        </div>
                        <p class="text-sm text-white/80 leading-relaxed mb-8">Küçük işletmeler için geliştirilmiş sade, hızlı ve güvenli finansal yönetim platformu. Karışık Excel tablolarından ve karmaşık sistemlerden bugün kurtulun.</p>
                        <div class="flex items-center gap-4">
                            <a href="#"
                                class="h-10 w-10 rounded-full border border-white/20 bg-white/5 flex items-center justify-center text-white/80 hover:text-[#C72D52] hover:border-[#C72D52] hover:bg-transparent transition-all">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="#"
                                class="h-10 w-10 rounded-full border border-white/10 flex items-center justify-center text-white/40 hover:text-[#C72D52] hover:border-[#C72D52] transition-all">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c.796 0 1.441.645 1.441 1.44s-.645 1.44-1.441 1.44c-.795 0-1.439-.645-1.439-1.44s.644-1.44 1.439-1.44z" />
                                </svg>
                            </a>
                            <a href="#"
                                class="h-10 w-10 rounded-full border border-white/10 flex items-center justify-center text-white/40 hover:text-[#C72D52] hover:border-[#C72D52] transition-all">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Links 1 -->
                    <div class="reveal [transition-delay:100ms]">
                        <h4 class="text-xs font-black uppercase tracking-[0.3em] text-white mb-8">Ürün</h4>
                        <ul class="space-y-4">
                            <li><a href="#hero" class="text-sm text-white hover:text-[#C72D52] transition-colors">Ana
                                    Sayfa</a></li>
                            <li><a href="#features"
                                    class="text-sm text-white hover:text-[#C72D52] transition-colors">Modüller</a></li>
                            <li><a href="#pricing"
                                    class="text-sm text-white hover:text-[#C72D52] transition-colors">Fiyatlandırma</a></li>
                            <li><a href="#" class="text-sm text-white hover:text-[#C72D52] transition-colors">Sıkça
                                    Sorulanlar</a></li>
                        </ul>
                    </div>

                    <!-- Links 2 -->
                    <div class="reveal [transition-delay:200ms]">
                        <h4 class="text-xs font-black uppercase tracking-[0.3em] text-white mb-8">Şirket</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-sm text-white hover:text-[#C72D52] transition-colors">Hakkımızda</a>
                            </li>
                            <li><a href="#" class="text-sm text-white hover:text-[#C72D52] transition-colors">Kariyer</a>
                            </li>
                            <li><a href="#" class="text-sm text-white hover:text-[#C72D52] transition-colors">Blog</a></li>
                            <li><a href="#" class="text-sm text-white hover:text-[#C72D52] transition-colors">İletişim</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Links 3 -->
                    <div class="reveal [transition-delay:300ms]">
                        <h4 class="text-xs font-black uppercase tracking-[0.3em] text-white mb-8">Yasal</h4>
                        <ul class="space-y-4">
                            <li><a href="#"
                                    class="text-sm text-white hover:text-[#C72D52] transition-colors">Gizlilik
                                    Politikası</a></li>
                            <li><a href="#"
                                    class="text-sm text-white hover:text-[#C72D52] transition-colors">Kullanım
                                    Şartları</a></li>
                            <li><a href="#" class="text-sm text-white hover:text-[#C72D52] transition-colors">Çerez
                                    Politikası</a></li>
                            <li><a href="#"
                                    class="text-sm text-white hover:text-[#C72D52] transition-colors">KVKK</a></li>
                        </ul>
                    </div>
                </div>

                <div
                    class="pt-12 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-white">© 2024 MIONEX . TÜM HAKLARI SAKLIDIR.</p>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white">SİSTEM DURUMU: AKTİF</span>
                    </div>
                </div>
            </div>
        </footer>



        <!-- Scroll to Top Button -->
        <div x-data="{ show: false }" x-on:scroll.window="show = window.pageYOffset > 500">
            <button x-show="show" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
                class="fixed bottom-8 right-8 z-50 p-4 rounded-2xl glass-mionex border border-[#C72D52]/20 bg-[#C72D52] text-white shadow-2xl dark:shadow-[#C72D52]/20 hover:bg-white hover:text-[#C72D52] transition-all group scale-100 active:scale-95">
                <svg class="h-6 w-6 transform group-hover:-translate-y-1 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>

        <script>
            // Reveal on Scroll
            const reveal = () => {
                const reveals = document.querySelectorAll(".reveal");
                reveals.forEach(el => {
                    const windowHeight = window.innerHeight;
                    const elementTop = el.getBoundingClientRect().top;
                    const elementVisible = 150;
                    if (elementTop < windowHeight - elementVisible) {
                        el.classList.add("active");
                    }
                });
            }
            window.addEventListener("scroll", reveal);

            // Typing Animation
            const textElement = document.getElementById("typed-text");
            const words = ["MİMARİ.", "VERİMLİLİK.", "GÜVENLİK.", "HASSASİYET."];
            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;

            function type() {
                const currentWord = words[wordIndex];
                if (isDeleting) {
                    textElement.textContent = currentWord.substring(0, charIndex - 1);
                    charIndex--;
                } else {
                    textElement.textContent = currentWord.substring(0, charIndex + 1);
                    charIndex++;
                }

                if (!isDeleting && charIndex === currentWord.length) {
                    setTimeout(() => isDeleting = true, 2000);
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                }

                const typeSpeed = isDeleting ? 50 : 150;
                setTimeout(type, typeSpeed);
            }
            window.onload = type;

            // Mockup Tilt Effect (Simplified GSAP)
            const mockup = document.querySelector('#hero-mockup');
            if (mockup) {
                window.addEventListener('mousemove', (e) => {
                    const xPos = (e.clientX / window.innerWidth - 0.5) * 20;
                    const yPos = (e.clientY / window.innerHeight - 0.5) * 20;
                    gsap.to(mockup, {
                        rotateY: xPos,
                        rotateX: -yPos,
                        duration: 1,
                        ease: "power2.out"
                    });
                });
            }
            // Live Code Typing Animation
            const codeSnippet = document.getElementById("code-snippet");
            const codeToType = `// MIONEX Core Financial Module
public function processLedger(Amount $value) 
{
    return DB::transaction(function() use ($value) {
        $this->traceActivity('ledger_process');
        $this->ledger->push($value);
        return $this->reconcile();
    });
}

# _`;

            let i = 0;
            const typingSpeed = 15; // Fast typing

            function typeCode() {
                if (i < codeToType.length) {
                    if (codeToType.charAt(i) === '#') {
                        codeSnippet.innerHTML = codeToType.substring(0, i) + '<span class="animate-pulse">█</span>';
                        codeSnippet.classList.remove('typing-cursor');
                    } else if (codeToType.charAt(i) === '_') {
                        // Keep cursor active
                    } else {
                        codeSnippet.textContent = codeToType.substring(0, i + 1);
                        i++;
                        setTimeout(typeCode, typingSpeed);
                    }
                }
            }

            const codeObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    codeSnippet.textContent = "";
                    typeCode();
                    codeObserver.unobserve(codeSnippet);
                }
            }, { threshold: 0.5 });

            if (codeSnippet) codeObserver.observe(codeSnippet);
        </script>
</body>

</html>