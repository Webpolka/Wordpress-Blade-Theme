<header class="h-16 sticky top-0 bg-white flex items-center shadow-md" x-data>
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
                <x-dropdown-menu :items="[
                    ['label' => 'Главная', 'url' => '/'],
                    [
                        'label' => 'Каталог',
                        'url' => '/catalog',
                        'children' => [
                            ['label' => 'Товары', 'url' => '/catalog/products'],
                            ['label' => 'Товары', 'url' => '/catalog/products'],
                
                            [
                                'label' => 'Категории',
                                'url' => '/catalog/categories',
                                'children' => [
                                    ['label' => 'Электроника', 'url' => '/catalog/electronics'],
                                    ['label' => 'Одежда', 'url' => '/catalog/clothing'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products',],
                                ],
                            ],
                        ],
                    ],
                    ['label' => 'Контакты', 'url' => '/contacts'],
                ]" />
            </div>

            {{-- Мобильный дравер --}}
            <x-mobile-drawer position="right" title="Меню">
                <x-accordion-menu :hover="false">
                    <x-accordion-menu.submenu label="Каталог">
                        <x-accordion-menu.item href="#">Главная</x-accordion-menu.item>
                        <x-accordion-menu.item href="#">О нас</x-accordion-menu.item>
                        <x-accordion-menu.item href="#">Контакты</x-accordion-menu.item>
                        <x-accordion-menu.item href="#">Социальные сети</x-accordion-menu.item>
                    </x-accordion-menu.submenu>
                </x-accordion-menu>

            </x-mobile-drawer>


        </div>
        {{-- @if (has_nav_menu('primary_navigation'))
            <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
                {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
            </nav>
        @endif --}}
    </x-container>
</header>
