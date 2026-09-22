<nav class="fixed inset-x-0 bottom-0 z-40 w-full border-t border-slate-200 bg-white/90 px-2 pb-3 pt-2 backdrop-blur-xl">
    <div class="mx-auto grid max-w-screen-md grid-cols-5 gap-2 rounded-[30px] border border-slate-200/80 bg-white px-2 py-2 shadow-[0_18px_40px_rgba(15,23,42,0.12)]">
        <a href="{{ route('preview.mobile') }}" class="flex flex-col items-center justify-center rounded-[18px] px-1 py-2 text-[10px] {{ request()->routeIs('preview.mobile') ? 'bg-emerald-50 font-bold text-slate-800' : 'font-medium text-slate-500' }}">
            <span class="mb-1 flex h-9 w-9 items-center justify-center rounded-[14px] {{ request()->routeIs('preview.mobile') ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/></svg>
            </span>
            <span>Home</span>
        </a>

        <a href="{{ route('preview.mobile.umkm') }}" class="flex flex-col items-center justify-center rounded-[18px] px-1 py-2 text-[10px] {{ request()->routeIs('preview.mobile.umkm') || request()->routeIs('preview.mobile.umkm.detail') ? 'bg-emerald-50 font-bold text-slate-800' : 'font-medium text-slate-500' }}">
            <span class="mb-1 flex h-9 w-9 items-center justify-center rounded-[14px] {{ request()->routeIs('preview.mobile.umkm') || request()->routeIs('preview.mobile.umkm.detail') ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M7 7h10l2 11H5l2-11Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/></svg>
            </span>
            <span>UMKM</span>
        </a>

        <a href="{{ route('preview.mobile.peta') }}" class="flex flex-col items-center justify-center rounded-[18px] px-1 py-2 text-[10px] {{ request()->routeIs('preview.mobile.peta') ? 'bg-emerald-50 font-bold text-slate-800' : 'font-medium text-slate-500' }}">
            <span class="mb-1 flex h-9 w-9 items-center justify-center rounded-[14px] {{ request()->routeIs('preview.mobile.peta') ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6.5L9 3l6 3 6-3v13l-6 3-6-3-6 3V6.5z"/><path d="M9 3v13M15 6v11"/></svg>
            </span>
            <span>Peta</span>
        </a>

        <a href="{{ route('preview.mobile.promo') }}" class="flex flex-col items-center justify-center rounded-[18px] px-1 py-2 text-[10px] {{ request()->routeIs('preview.mobile.promo') ? 'bg-emerald-50 font-bold text-slate-800' : 'font-medium text-slate-500' }}">
            <span class="mb-1 flex h-9 w-9 items-center justify-center rounded-[14px] {{ request()->routeIs('preview.mobile.promo') ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 8h-1l-2 2"/><path d="M7 8h10v8H7z"/><path d="M8 12h8"/></svg>
            </span>
            <span>Promo</span>
        </a>

        <a href="{{ route('preview.mobile.akun') }}" class="flex flex-col items-center justify-center rounded-[18px] px-1 py-2 text-[10px] {{ request()->routeIs('preview.mobile.akun') ? 'bg-emerald-50 font-bold text-slate-800' : 'font-medium text-slate-500' }}">
            <span class="mb-1 flex h-9 w-9 items-center justify-center rounded-[14px] {{ request()->routeIs('preview.mobile.akun') ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="8" r="4"/><path d="M4 20c2-3 5-4 8-4s6 1 8 4"/></svg>
            </span>
            <span>Akun</span>
        </a>
    </div>
</nav>
