<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kutim | Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {'50': '#f0fdf4', '100': '#dcfce7', '500': '#16a34a', '600': '#15803d', '700': '#166534', '900': '#14532d'}, ink: '#111827', mist: '#f5f7f5', muted: '#6b7280', line: '#e5e7eb' }, boxShadow: { soft: '0 12px 30px rgba(15, 23, 42, 0.06)', lift: '0 18px 45px rgba(20, 83, 45, 0.15)' } } } } }
    </script>
    <style>
        * { box-sizing: border-box; }
        html, body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        body {
           background: linear-gradient(180deg, #edf6ee 0%, #e7f0ea 100%);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
           color: #0f172a;
       }
       .bottom-nav { background: rgba(255,255,255,0.78); backdrop-filter: blur(14px); border-top: 1px solid rgba(148,163,184,0.18); box-shadow: 0 -10px 30px rgba(15,23,42,0.08); }
       .nav-pill { border-radius: 18px; padding: 8px 6px 10px; transition: all .2s ease; min-height: 72px; }
       .nav-pill.active { color: #0f172a; }
       .nav-icon { width: 42px; height: 42px; border-radius: 15px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #64748b; transition: all .2s ease; box-shadow: inset 0 0 0 1px rgba(15,23,42,0.03); }
       .nav-pill.active .nav-icon { background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color: white; box-shadow: 0 12px 24px rgba(22, 163, 74, 0.24); }
       .nav-label { display: block; margin-top: 6px; font-size: 9px; line-height: 1; letter-spacing: 0.03em; text-transform: uppercase; }
       .card-soft { background: rgba(255,255,255,0.88); border: 1px solid rgba(148,163,184,0.12); box-shadow: 0 10px 24px rgba(15,23,42,0.04); }
       .section-label { letter-spacing: 0.18em; }
       .profile-card { background: linear-gradient(135deg, #ffffff 0%, #f4fbf5 100%); border: 1px solid rgba(34,197,94,0.12); box-shadow: 0 16px 38px rgba(16, 185, 129, 0.12); }
       .profile-avatar { background: linear-gradient(135deg, #dff7e5 0%, #bbf7d0 100%); color: #166534; }
       .metric-card { background: rgba(255,255,255,0.74); border: 1px solid rgba(148,163,184,0.12); }
       .menu-item { transition: transform .2s ease, box-shadow .2s ease; }
       .menu-item:active { transform: translateY(1px); }
       @media (max-width: 420px) {
           body { padding: 0; }
           nav.bottom-nav { width: 100%; max-width: 100%; left: 0; transform: none; border-radius: 0; }
       }
   </style>
</head>
<body class="min-h-screen w-full overflow-x-hidden bg-[#edf4ef]">
   <div class="min-h-screen w-full bg-[#edf4ef]">
       <main class="mx-auto max-w-md px-4 pb-24 pt-3">
           <header class="mb-4 flex items-center justify-between">
               <div>
                   <p class="section-label text-[9px] font-semibold text-emerald-700">AKUN</p>
                   <h1 class="text-[19px] font-black tracking-[-0.03em] text-slate-900">Profil saya</h1>
               </div>
               <button class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50">
                   <i class="fa-solid fa-gear text-sm"></i>
               </button>
           </header>

           <section class="profile-card mb-4 rounded-[28px] p-4 text-slate-900">
               <div class="flex items-center gap-3">
                   <div class="profile-avatar flex h-16 w-16 items-center justify-center rounded-[22px] text-xl font-black shadow-inner shadow-emerald-100">
                       {{ strtoupper(substr(($userProfile['name'] ?? 'Ayu Lestari'), 0, 1)) }}
                   </div>
                   <div class="min-w-0 flex-1">
                       <p class="mb-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700">Pelaku usaha</p>
                       <h2 class="truncate text-xl font-black tracking-[-0.04em] text-slate-900">{{ $userProfile['name'] ?? 'Ayu Lestari' }}</h2>
                       <p class="truncate text-sm text-slate-500">{{ $userProfile['email'] ?? 'ayulestari@email.com' }}</p>
                   </div>
               </div>

               @if($isLoggedIn ?? false)
                   <div class="mt-4 flex gap-2">
                       <a href="{{ route('dashboard.pelaku') }}" class="flex-1 rounded-full bg-emerald-600 px-4 py-2.5 text-center text-sm font-bold text-white shadow-sm shadow-emerald-200">Dashboard</a>
                       <form action="{{ route('logout') }}" method="POST" class="inline-block flex-1">
                           @csrf
                           <button type="submit" class="w-full rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">Keluar</button>
                       </form>
                   </div>
               @else
                   <div class="mt-4 flex gap-2">
                       <a href="{{ route('login') }}" class="flex-1 rounded-full bg-emerald-600 px-4 py-2.5 text-center text-sm font-bold text-white shadow-sm shadow-emerald-200">Masuk</a>
                       <a href="{{ route('register') }}" class="flex-1 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Daftar</a>
                   </div>
               @endif
           </section>

           <section class="mb-4 grid grid-cols-2 gap-3">
               @foreach($stats ?? [] as $stat)
                   <div class="metric-card rounded-[22px] p-3">
                       <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">{{ $stat['label'] ?? 'Stat' }}</p>
                       <p class="mt-2 text-[30px] font-black leading-none tracking-[-0.05em] text-slate-900">{{ $stat['value'] ?? '0' }}</p>
                   </div>
               @endforeach
           </section>

           <section class="mb-4">
               <div class="mb-3 flex items-center justify-between px-1">
                   <h3 class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Menu</h3>
               </div>
               <div class="space-y-1.5">
                   @foreach($menuItems ?? [] as $menu)
                       <button class="menu-item card-soft w-full rounded-[18px] px-3 py-2.5 text-left">
                           <div class="flex items-center justify-between gap-2">
                               <div class="flex items-center gap-2.5 min-w-0">
                                   <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-sm text-emerald-700 ring-1 ring-emerald-100 shrink-0">
                                       <i class="{{ $menu['icon'] ?? 'fa-solid fa-circle' }}"></i>
                                   </div>
                                   <div class="min-w-0">
                                       <p class="truncate text-[13px] font-bold text-slate-900">{{ $menu['title'] ?? 'Menu' }}</p>
                                       <p class="truncate text-[10px] text-slate-500">{{ $menu['subtitle'] ?? '' }}</p>
                                   </div>
                               </div>
                               <span class="text-base text-slate-400 shrink-0">›</span>
                           </div>
                       </button>
                   @endforeach
               </div>
           </section>
       </main>
       @include('mobile.partials.bottom-nav')
   </div>
</body>
</html>
