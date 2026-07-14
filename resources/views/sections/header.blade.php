<header x-data
    class="{{ cn(
        'bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 antialiased',
        'h-16 sticky top-0 z-50 flex items-center',
        'shadow-md dark:shadow-none dark:border-b dark:border-gray-800'
    ) }}">
    <x-container>
        <div class="flex justify-between items-center">

            <x-theme-toggle variant="rounded" size="lg" showLabel="true" showTooltip="true" initialTheme="dark"
                onThemeChange="console.log('Theme changed to', theme)" onThemeToggle="console.log('Theme toggled')"
                className="fixed top-4 right-4 z-50 shadow-lg" />

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
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    ['label' => 'Товары', 'url' => '/catalog/products'],
                                    [
                                        'label' => 'Товары',
                                        'url' => '/catalog/products',
                                        'children' => [
                                            ['label' => 'Электроника', 'url' => '/catalog/electronics'],
                                            ['label' => 'Одежда', 'url' => '/catalog/clothing'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                            ['label' => 'Товары', 'url' => '/catalog/products'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    ['label' => 'Главная', 'url' => '/'],
                    ['label' => 'Контакты', 'url' => '/contacts'],
                ]" />
            </div>

            {{-- Мобильный дравер --}}
             <button x-data @click="$dispatch('drawer-toggle', 'cart')" class="relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
    </button>
    
    <x-mobile-drawer name="cart" position="right" title="Корзина">
        <div class="space-y-4">
            <div class="flex gap-3 p-3 border rounded-lg">
                <img src="product.jpg" class="w-16 h-16 rounded object-cover">
                <div class="flex-1">
                    <h4 class="font-semibold">Товар 1</h4>
                    <p class="text-sm text-gray-500">Размер: M</p>
                    <p class="text-sm font-medium mt-1">1 000 ₽</p>
                </div>
            </div>
        </div>
        
        <x-slot:footer>
            <div class="flex justify-between items-center mb-3">
                <span class="font-semibold">Итого:</span>
                <span class="text-xl font-bold">3 000 ₽</span>
            </div>
            <x-button variant="primary" class="w-full">Оформить заказ</x-button>
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
