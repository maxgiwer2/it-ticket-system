<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบแจ้งซ่อม IT') - Faculty of Medicine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Kanit:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        body {
            font-family: 'Outfit', 'Kanit', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.07);
        }
        .primary-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        /* Tom Select Customization */
        .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            border: 1px solid #e2e8f0 !important;
            font-family: inherit !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            margin-top: 5px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>
<body class="text-slate-800">
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 mb-8">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('tickets.create') }}" class="text-xl font-bold primary-gradient bg-clip-text text-transparent">IT SERVICE</a>
            <div class="space-x-6 flex items-center">
                <a href="{{ route('tickets.create') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">หน้าแรก</a>
                <a href="{{ route('tickets.search') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">ติดตามสถานะ</a>
                <a href="{{ route('admin.departments.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-bold rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-all">จัดการหน่วยงาน</a>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

    <footer class="mt-auto py-8 border-t border-slate-200 text-center text-slate-500 text-sm">
        &copy; {{ date('Y') }} คณะแพทยศาสตร์. All rights reserved.
    </footer>
</body>
</html>
