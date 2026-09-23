<nav class="fixed inset-x-0 bottom-0 z-40 w-full px-1.5 pb-2 pt-1.5">
    <div class="flex justify-center">
        <div class="inline-grid w-fit max-w-full grid-cols-4 gap-2 rounded-[24px] bg-white px-2 py-1.5 shadow-[0_12px_30px_rgba(15,23,42,0.12)]">
            <a href="{{ route('preview.mobile') }}" class="flex min-w-[52px] flex-col items-center justify-center rounded-[14px] px-1 py-1.5 text-[9px] {{ request()->routeIs('preview.mobile') ? 'font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                <span class="mb-1 flex h-8 w-8 items-center justify-center rounded-full {{ request()->routeIs('preview.mobile') ? 'bg-emerald-100 text-emerald-700' : 'bg-transparent text-slate-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/></svg>
                </span>
                <span>Home</span>
            </a>

            <a href="{{ route('preview.mobile.umkm') }}" class="flex min-w-[52px] flex-col items-center justify-center rounded-[14px] px-1 py-1.5 text-[9px] {{ request()->routeIs('preview.mobile.umkm') || request()->routeIs('preview.mobile.umkm.detail') ? 'font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                <span class="mb-1 flex h-8 w-8 items-center justify-center rounded-full {{ request()->routeIs('preview.mobile.umkm') || request()->routeIs('preview.mobile.umkm.detail') ? 'bg-emerald-100 text-emerald-700' : 'bg-transparent text-slate-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5"><path d="M7 7h10l2 11H5l2-11Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/></svg>
                </span>
                <span>UMKM</span>
            </a>

            <a href="{{ route('preview.mobile.peta') }}" class="flex min-w-[52px] flex-col items-center justify-center rounded-[14px] px-1 py-1.5 text-[9px] {{ request()->routeIs('preview.mobile.peta') ? 'font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                <span class="mb-1 flex h-8 w-8 items-center justify-center rounded-full {{ request()->routeIs('preview.mobile.peta') ? 'bg-emerald-100 text-emerald-700' : 'bg-transparent text-slate-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6.5L9 3l6 3 6-3v13l-6 3-6-3-6 3V6.5z"/><path d="M9 3v13M15 6v11"/></svg>
                </span>
                <span>Peta</span>
            </a>

            <a href="{{ route('preview.mobile.promo') }}" class="flex min-w-[52px] flex-col items-center justify-center rounded-[14px] px-1 py-1.5 text-[9px] {{ request()->routeIs('preview.mobile.promo') ? 'font-bold text-slate-800' : 'font-medium text-slate-500' }}">
                <span class="mb-1 flex h-8 w-8 items-center justify-center rounded-full {{ request()->routeIs('preview.mobile.promo') ? 'bg-emerald-100 text-emerald-700' : 'bg-transparent text-slate-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5"><path d="M18 8h-1l-2 2"/><path d="M7 8h10v8H7z"/><path d="M8 12h8"/></svg>
                </span>
                <span>Promo</span>
            </a>
        </div>
    </div>
</nav>
