<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบแจ้งซ่อม IT') - Faculty of Medicine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&family=Noto+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        :root {
            --primary: #0891b2;
            --primary-dark: #164e63;
            --accent: #059669;
            --bg-medical: #f0f9ff;
        }
        body {
            font-family: 'Figtree', 'Noto Sans', sans-serif;
            background-color: var(--bg-medical);
            background-image: 
                radial-gradient(at 0% 0%, rgba(8, 145, 178, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(5, 150, 105, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            padding-top: 90px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 50px -12px rgba(22, 78, 99, 0.08);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            box-shadow: 0 30px 60px -12px rgba(22, 78, 99, 0.12);
        }
        .primary-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
        .cta-gradient {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        /* Entrance Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        
        /* Accessibility & Interaction */
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.2);
        }
        .interactive-scale {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .interactive-scale:active {
            transform: scale(0.96);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Tom Select Customization */
        .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            border: 1px solid #e2e8f0 !important;
            font-family: inherit !important;
            transition: all 0.2s;
        }
        .ts-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.1) !important;
        }
        /* Prevent SVG Flash (Exploding icons before Tailwind loads) */
        svg {
            width: 2rem;
            height: 2rem;
        }
        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s;
        }
        .loader-logo {
            font-size: 2.5rem;
            font-weight: 900;
            color: #059669;
            letter-spacing: -0.05em;
            font-style: italic;
            animation: pulse-soft 2s infinite ease-in-out;
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.98); }
        }
        /* Definitive FOUC Fix (No giant black icons) */
        body:not(.loaded) > *:not(#preloader) {
            display: none !important;
        }
    </style>
</head>
<body class="text-cyan-950 flex flex-col min-h-screen selection:bg-cyan-200 selection:text-cyan-900">
    <!-- Premium Preloader -->
    <div id="preloader">
        <div class="loader-logo">IT<span class="text-slate-800">SERVICE</span></div>
        <div class="mt-4 w-48 h-1 bg-slate-100 rounded-full overflow-hidden relative">
            <div class="absolute inset-0 bg-emerald-500 w-1/2 animate-[loading_1.5s_infinite_ease-in-out]"></div>
        </div>
        <style>
            @keyframes loading {
                0% { left: -50%; }
                100% { left: 100%; }
            }
        </style>
    </div>
    <nav class="bg-white/90 backdrop-blur-md border-b border-emerald-100 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('tickets.create') }}" class="text-2xl font-black text-emerald-600 tracking-tighter italic">
                IT<span class="text-slate-800">SERVICE</span>
            </a>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('tickets.create') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">หน้าแรก</a>
                <a href="{{ route('tickets.search') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">ติดตามสถานะ</a>
                
                <div class="h-6 w-px bg-slate-200 mx-2"></div>
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">Dashboard</a>
                    <a href="{{ route('admin.tickets.index') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">ใบงาน</a>
                    <a href="{{ route('admin.workloads.index') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">ภาระงาน</a>
                    <a href="{{ route('admin.departments.index') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">หน่วยงาน</a>
                    
                    <div class="relative group" id="report-dropdown-container">
                        <button id="report-dropdown-button" class="flex items-center text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">
                            รายงานสถิติ
                            <svg class="w-4 h-4 ml-1 text-slate-400 transition-transform group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div id="report-dropdown-menu" class="hidden absolute left-0 mt-2 w-48 bg-white/90 backdrop-blur-xl border border-emerald-100 rounded-2xl shadow-xl shadow-emerald-900/5 overflow-hidden z-50 animate-fade-in-up">
                            <div class="p-2 space-y-1">
                                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-3 text-sm font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-colors">
                                    สถิติพนักงาน
                                </a>
                                <a href="{{ route('admin.reports.departments') }}" class="block px-4 py-3 text-sm font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-colors">
                                    สถิติหน่วยงาน
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'superadmin')
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">จัดการผู้ใช้งาน</a>
                    @endif
                    
                    <div class="relative group" id="user-dropdown-container">
                        <button id="user-dropdown-button" class="flex items-center bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl border border-slate-100 transition-all active:scale-95">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold mr-2">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-bold text-slate-700 mr-2">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-xl border border-emerald-100 rounded-2xl shadow-xl shadow-emerald-900/5 overflow-hidden z-50 animate-fade-in-up">
                            <div class="p-2 space-y-1">
                                <a href="{{ route('admin.profile.password') }}" class="flex items-center px-4 py-3 text-sm font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    เปลี่ยนรหัสผ่าน
                                </a>
                                
                                <div class="h-px bg-slate-100 mx-2 my-1"></div>
                                
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-colors text-left">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        ออกจากระบบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-lg hover:bg-emerald-600 hover:text-white transition-all">สำหรับเจ้าหน้าที่</a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu Container -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-lg px-6 py-6 space-y-4 shadow-xl">
            <a href="{{ route('tickets.create') }}" class="block text-base font-semibold text-slate-600 hover:text-emerald-600">หน้าแรก</a>
            <a href="{{ route('tickets.search') }}" class="block text-base font-semibold text-slate-600 hover:text-emerald-600">ติดตามสถานะ</a>
            
            @auth
                <div class="flex items-center p-3 bg-slate-50 rounded-2xl mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mr-3">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">เข้าใช้งานโดย</p>
                        <p class="text-base font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    </div>
                </div>

                <a href="{{ route('admin.dashboard') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600">Dashboard</a>
                <a href="{{ route('admin.tickets.index') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600">ใบงาน</a>
                <a href="{{ route('admin.workloads.index') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600">ภาระงาน</a>
                <a href="{{ route('admin.departments.index') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600">หน่วยงาน</a>
                
                <div class="border-t border-slate-100 my-2 pt-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">รายงานสถิติ</p>
                    <a href="{{ route('admin.reports.index') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600 mb-2 pl-4 border-l-2 border-emerald-100 hover:border-emerald-500">สถิติพนักงาน</a>
                    <a href="{{ route('admin.reports.departments') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600 pl-4 border-l-2 border-emerald-100 hover:border-emerald-500">สถิติหน่วยงาน</a>
                </div>

                @if(auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.users.index') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600">จัดการผู้ใช้งาน</a>
                @endif

                <a href="{{ route('admin.profile.password') }}" class="block text-base font-bold text-slate-700 hover:text-emerald-600">เปลี่ยนรหัสผ่าน</a>
                
                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-emerald-50 text-emerald-600 text-sm font-bold rounded-xl hover:bg-emerald-600 hover:text-white transition-all">ออกจากระบบ</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block w-full py-3 bg-emerald-50 text-emerald-700 text-center text-sm font-bold rounded-xl hover:bg-emerald-600 hover:text-white transition-all">สำหรับเจ้าหน้าที่</a>
            @endauth
        </div>
    </nav>

    <script>
        const menuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const openIcon = document.getElementById('menu-icon-open');
        const closeIcon = document.getElementById('menu-icon-close');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });

        // Desktop Dropdown
        const userBtn = document.getElementById('user-dropdown-button');
        const userMenu = document.getElementById('user-dropdown-menu');

        if(userBtn) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }

        // Report Dropdown
        const reportBtn = document.getElementById('report-dropdown-button');
        const reportMenu = document.getElementById('report-dropdown-menu');

        if(reportBtn) {
            reportBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                reportMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!reportMenu.contains(e.target) && !reportBtn.contains(e.target)) {
                    reportMenu.classList.add('hidden');
                }
            });
        }
    </script>

    <main class="flex-grow py-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="mt-auto py-8 border-t border-slate-200 text-center text-slate-500 text-sm bg-white/50">
        &copy; {{ date('Y') }} คณะแพทยศาสตร์. All rights reserved.
    </footer>

    <script>
        // Hide Preloader and Reveal Content on Load
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            document.body.classList.add('loaded'); // Reveal everything
            
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.visibility = 'hidden';
            }, 500);
        });
    </script>

    @stack('scripts')
</body>
</html>
