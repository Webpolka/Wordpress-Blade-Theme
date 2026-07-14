{{--
OK !

==============================================================
 WP Components: Skeleton
==============================================================

Скелетон (Skeleton) — это анимированная заглушка, которая 
показывается пользователю, пока реальные данные (текст, картинки)
еще грузятся с сервера. 

--------------------------------------------------------------
 1. ПОЧЕМУ ЭТО ВАЖНО (UX)
--------------------------------------------------------------
 - В отличие от крутящегося спиннера, скелетон показывает 
   СТРУКТУРУ будущего контента.
 - Пользователь сразу понимает, что загрузится (статья, товар 
   или список), и психологически ожидание кажется быстрее.
 - Предотвращает скачки верстки (Cumulative Layout Shift), так 
   как место под контент зарезервировано сразу.

--------------------------------------------------------------
 2. ПРОПСЫ
--------------------------------------------------------------
 as    (string) : HTML-тег. По умолчанию: 'div'.
                  (Можно указать 'span', если болванка 
                  встроена в строку текста).
 class (string) : Tailwind классы. ОЧЕНЬ ВАЖНО задавать 
                  размеры (h-*, w-*) и форму (rounded-*).

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Простой текстовый блок
 <x-skeleton class="h-4 w-32 rounded" />

 // 2. Картинка карточки товара
 <x-skeleton class="h-48 w-full rounded-xl" />

 // 3. Круглый аватар (для комментариев)
 <x-skeleton class="h-10 w-10 rounded-full" />

 // 4. Полноценная болванка карточки блога
 <div class="border p-4 rounded-xl space-y-4">
     <x-skeleton class="h-40 w-full rounded-lg" />
     <x-skeleton class="h-6 w-3/4" />
     <x-skeleton class="h-4 w-full" />
     <x-skeleton class="h-4 w-5/6" />
     <div class="flex items-center gap-2 mt-4">
         <x-skeleton class="h-8 w-8 rounded-full" />
         <x-skeleton class="h-4 w-24" />
     </div>
 </div>

Как это применять в связке с Alpine.js (DX):

Если ты делаешь AJAX-фильтр постов, код выглядит примерно так:

 <div x-data="{ loading: false, posts: [] }">
    <button @click="loading = true; fetchPosts()">Загрузить</button>

    @if($loading) // или x-show="loading"
        <!-- Скелетоны -->
        <div class="grid grid-cols-3 gap-6">
            <div class="space-y-3">
                <x-skeleton class="h-40 w-full rounded-xl" />
                <x-skeleton class="h-6 w-3/4" />
                <x-skeleton class="h-4 w-1/2" />
            </div>
        </div>
    @else
        <!-- Реальные посты -->
        ...
    @endif
</div>
==============================================================
  
--}}

@props([
    'as'    => 'div',
    'class' => null,
])

{{-- Директива @once гарантирует, что CSS загрузится один раз, даже если скелетонов на странице 100 штук --}}
@once
    <style>
        /* Анимация бегущей волны (Shimmer) */
        @keyframes wp-skeleton-shimmer {
            100% { transform: translateX(100%); }
        }

        .wp-skeleton {
            position: relative;
            overflow: hidden;
            background-color: #e2e8f0; /* slate-200 */
        }

        .wp-skeleton.force-dark,
        .dark .wp-skeleton {
            background-color: #1e293b; /* slate-800 */
        }

        /* Сама волна: прозрачный градиент, который едет поверх фона */
        .wp-skeleton::after {
            position: absolute;
            inset: 0;
            z-index:0;
            transform: translateX(-100%);
            background-image: linear-gradient(90deg, rgba(255,255,255,0) 0, rgba(255,255,255,0.6) 20%, rgba(255,255,255,0.8) 60%, rgba(255,255,255,0) 100%);
            animation: wp-skeleton-shimmer 1.5s infinite;
            content: '';
        }

        /* Для темной темы делаем волну чуть светлее, но не слепящей */
        .wp-skeleton.force-dark::after,
        .dark .wp-skeleton::after {
            background-image: linear-gradient(90deg, rgba(255,255,255,0) 0, rgba(255,255,255,0.05) 20%, rgba(255,255,255,0.1) 60%, rgba(255,255,255,0) 100%);
        }
    </style>
@endonce

@php
    // Базовый класс теперь наш кастомный wp-skeleton + стандартный rounded для скругления углов, если разработчик не передал свой
    $classes = cn('wp-skeleton rounded-md', $class);
@endphp

<{{ $as }} class="{{ $classes }}">
    {{ $slot }}
</{{ $as }}>