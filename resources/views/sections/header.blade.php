<header class="banner" x-data>
    <x-container>
        <div class="flex justify-between items-center">

            <a class="brand" href="{{ home_url('/') }}">
                {!! $siteName !!}
            </a>

            <a href="/" class="brand">Лого</a>

            {{-- Десктопное меню (скрыто на мобильных) --}}
            <div class="hidden lg:block">
                @php
                    $primaryMenu = build_tree_menu('primary_navigation');

                @endphp
                <x-dropdown-menu :items="$primaryMenu" />
            </div>



            {{-- Мобильный дравер --}}
            <x-mobile-drawer position="right" title="Меню">
        <nav class="flex flex-col gap-1">
            <a href="/">Главная</a>
            <a href="/about">О нас</a>
        </nav>
        
        <x-slot:footer>
            <div class="flex gap-3">
                <a href="#" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5"><use href="#icon-facebook"></use></svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5"><use href="#icon-instagram"></use></svg>
                </a>
            </div>
        </x-slot:footer>
    </x-mobile-drawer>
  

        </div>
        {{-- @if (has_nav_menu('primary_navigation'))
            <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
                {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
            </nav>
        @endif --}}
    </x-container>
</header>
