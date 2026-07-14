{{--
Ок !

==============================================================
 WP Components: Cookie Banner
==============================================================

Аккуратная плашка для согласия на использование cookies. 
Обязательна для сайтов, попадающих под действие GDPR.

--------------------------------------------------------------
 1. ГЛАВНЫЕ ФИЧИ
--------------------------------------------------------------
 - Smart Memory: Запоминает выбор пользователя в localStorage. 
   При следующем визите плашка не появляется.
 - Smooth Animation: Плавно выезжает снизу при загрузке 
   страницы и плавно исчезает при выборе.
 - Dual Action: Кнопки "Принять" и "Отклонить" (обе сохраняют 
   выбор, чтобы не показываться снова).

--------------------------------------------------------------
 2. ПРОПСЫ
--------------------------------------------------------------
 title       (string) : Заголовок. 
 description (string) : Текст плашки.
 privacyUrl  (string) : URL страницы политики конфиденциальности.
 position    (string) : Позиция на экране ('left' или 'right'). 
                        По умолчанию: 'left'.
 class       (string) : Доп. классы.

--------------------------------------------------------------
 3. ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
--------------------------------------------------------------

 // 1. Базовый вывод (добавляется в footer.blade.php)
 <x-cookie-banner />

 // 2. Кастомный текст и позиция справа
 <x-cookie-banner 
     title="Файлы cookie" 
     description="Мы используем их для работы сайта." 
     privacy-url="/privacy" 
     position="right" 
 />

--------------------------------------------------------------
 4. ТЕХНИЧЕСКИЕ ДЕТАЛИ
--------------------------------------------------------------
 Компонент сохраняет ключ 'cookie-consent' со значением 
 'accepted' или 'declined'. Если вам нужно сбросить выбор 
 (для тестов), откройте консоль браузера и введите:
 localStorage.removeItem('cookie-consent')
==============================================================
Юридическая чистота (Закон GDPR/152-ФЗ)
По закону мы не имеем права ставить юзеру трекеры (Яндекс.Метрика, Google Analytics, пиксели Facebook) без его явного согласия. 
Если мы даем только кнопку "Принять" — это нарушение. Юзер должен иметь право сказать: "Я не хочу, чтобы за мной следили".

Теперь при клике браузер генерирует кастомное событие (CustomEvent):

Если нажал "Принять" -> летит событие cookie-consent-accepted.
Если нажал "Отклонить" -> летит событие cookie-consent-declined.

Как теперь подключить Метрику/GA (Пример для тебя):
В твоем главном JS-файле (например, app.js или main.js), где ты инициализируешь скрипты, ты пишешь примерно такую логику:

// Функция загрузки Яндекс.Метрики
function loadAnalytics() {
    // Тут код вставки Метрики/GA
    console.log('Аналитика загружена!');
}

// Проверяем при загрузке страницы (если юзер уже соглашался ранее)
if (localStorage.getItem('cookie-consent') === 'accepted') {
    loadAnalytics();
}

// Слушаем кнопки из нашего баннера
window.addEventListener('cookie-consent-accepted', loadAnalytics);

window.addEventListener('cookie-consent-declined', () => {
    console.log('Юзер отказался. Аналитика не грузится.');
    // Тут можно добавить логику отключения уже загруженных трекеров, если нужно
});

Если юзер нажмет "Отклонить", плагины аналитики даже не загрузятся. 
Сайт будет работать быстрее, а ты будешь спать спокойно, зная, что законы не нарушены.

--}}

@props([
    // Английский базовый язык с доменом weblegko
    'title'       => __('We use cookies 🍪', 'weblegko'),
    'description' => __('This site uses cookies to ensure you get the best experience. By continuing to use the site, you agree to their use.', 'weblegko'),
    'privacyUrl'  => '/privacy-policy',
    'position'    => 'left', // left | right
    'class'       => null,
])

@php
    // Фикс для мобилок (inset-x-4) и десктопа (sm:w-96 + привязка к краю)
    $posClass = $position === 'right' 
        ? 'sm:right-8 sm:left-auto' 
        : 'sm:left-8 sm:right-auto';
        
    $wrapperClasses = cn(
        'fixed z-50 bottom-4 inset-x-4 sm:bottom-8 sm:w-96 sm:inset-x-auto',
        $posClass,
        $class
    );
@endphp

<div 
    x-data="{ show: !localStorage.getItem('cookie-consent') }"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-8"
    class="{{ $wrapperClasses }}"
>
    <div class="min-w-[16rem] bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-2xl p-5 flex flex-col gap-4">
        
        <div class="flex items-start gap-3">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    {{ $description }}
                    @if($privacyUrl)                        
                        <a href="{{ $privacyUrl }}" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">{{ __('Learn more', 'weblegko') }}</a>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
            <x-button 
                @click="localStorage.setItem('cookie-consent', 'accepted'); window.dispatchEvent(new CustomEvent('cookie-consent-accepted')); show = false"               
                class="w-full sm:flex-1" variant="primary"
            >              
                {{ __('Accept', 'weblegko') }}
            </x-button>
            <x-button 
                @click="localStorage.setItem('cookie-consent', 'declined'); window.dispatchEvent(new CustomEvent('cookie-consent-declined')); show = false"
                class="w-full sm:flex-1" variant="outline"
            >                
                {{ __('Decline', 'weblegko') }}
            </x-button>
        </div>

    </div>
</div>