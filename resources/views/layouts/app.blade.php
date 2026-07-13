<!doctype html>
<html @php(language_attributes()) xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        } else {
        document.documentElement.classList.remove('dark');
        }
    </script>
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- нужно чтобы использовать свг спрайты --}}
<div style="display: none;">
    @include('partials.svg-sprite')
</div>

{{-- нужно для подключения только оустановленных компонентов --}}
<script>
    window.componentsManifestUrl = "{{ get_template_directory_uri() . '/public/components-manifest.json' }}";
</script>

<body @php(body_class('bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 antialiased relative'))>
    @php(wp_body_open())  

    <x-toast />
    <x-modal-dynamic />  

    <div id="app">
        <a class="sr-only focus:not-sr-only" href="#main">
            {{ __('Skip to content', 'sage') }}
        </a>

        <div class="min-h-screen flex flex-col">
            @include('sections.header')

            <x-container>
                    <x-breadcrumbs class="py-3" />
            </x-container>

            {{-- Проверяем, есть ли секция sidebar --}}
            @hasSection('sidebar')
                {{-- С сайдбаром (Сетка 12 колонок) --}}
                <x-container class="flex-1 py-8 md:py-12">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-8">

                        {{-- контент --}}
                        <main id="main" class="lg:col-span-8">                           

                            @yield('content')
                        </main>

                        {{-- сайдбар --}}
                        <aside class="mt-8 lg:mt-0 lg:col-span-4">
                            @yield('sidebar')
                        </aside>
                    </div>
                </x-container>
            @else
                {{-- Без сайдбара (Контент на всю ширину) --}}
                <x-container class="flex-1 py-8 md:py-12">
                    <main id="main">                     

                        @yield('content')
                    </main>
                </x-container>
            @endif

            @include('sections.footer')
        </div>
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
</body>

</html>
