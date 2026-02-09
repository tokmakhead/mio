<div class="flex items-center gap-2 group">
    <img src="{{ asset('assets/img/nexblack.png') }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'h-8 w-auto dark:hidden']) }}>
    <img src="{{ asset('assets/img/nexwhite.png') }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'h-8 w-auto hidden dark:block']) }}>
    <span class="text-xl font-black tracking-tighter text-gray-900 dark:text-white leading-none uppercase">MIONEX</span>
</div>