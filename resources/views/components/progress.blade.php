{{--
  ============================================================
  Компонент: Progress (Прогресс-бар)
  Описание: Линейный индикатор прогресса (загрузка, профиль, лимиты).
  ============================================================

  ------------------------------------------------------------
  ОСОБЕННОСТИ
  ------------------------------------------------------------
    • 4 цветовых варианта на базе Design System.
    • Анимированные диагональные полоски (striped) для активных процессов.
    • Вывод текста (процентов) прямо внутри полоски.
    • Идеальная анимация ширины (transition-all).

  ------------------------------------------------------------
  ПРОПСЫ (Параметры)
  ------------------------------------------------------------
    - value     (int)    : Значение от 0 до 100. По умолчанию: 0.
    - variant   (string) : Цвет ('primary', 'success', 'warning', 'destructive'). По умолчанию: 'primary'.
    - size      (string) : Высота ('sm', 'md', 'lg'). По умолчанию: 'md'.
    - striped   (bool)   : Включить анимированные полоски. По умолчанию: false.
    - showLabel (bool)   : Показывать проценты внутри. По умолчанию: false.
    - class     (string) : Доп. классы для внешней обертки.

  ============================================================
  ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
  ============================================================

  ОСНОВНЫЙ ПРАВИЛА ALPINE 
  
  если передаешь булево или число ставишь :
  если передаешь метод или свойство из x-data AlpineJs то : не ставишь
  если нужно передать из x-data свойство или метод другому компоненту используем x-bind

  1. Базовый прогресс:
    <x-progress :value="45" />

  2. Опасный лимит (например, память сервера на 90%):
    <x-progress :value="90" variant="destructive" />

  3. Идет загрузка (с анимированными полосками):
    <x-progress :value="65" variant="success" :striped="true" size="lg" :show-label="true" />

  4. С текстом внутри (заполнение профиля):
    <x-progress :value="80" variant="primary" size="lg" :show-label="true" />


  ПРИМЕРЫ в динамике:
  
  1.Имитация загрузки файла (File Upload)
  Юзер жмет кнопку, полоска начинает ползти, а когда доходит до 100% — перекрашивается в зеленый и останавливается.

  <div x-data="{ 
        progress: 0, 
        loading: false,
        startUpload() {
            this.progress = 0;
            this.loading = true;
            
            let interval = setInterval(() => {
                if (this.progress < 100) {
                    this.progress += 10; // Увеличиваем на 10%
                } else {
                    clearInterval(interval);
                    this.loading = false;
                }
            }, 300); // Каждые 300мс
        }
    }" class="space-y-4 max-w-sm">

        <x-progress 
            value="progress" 
            striped="loading" 
            variant="progress >= 100 ? 'success' : 'primary'" 
            size="lg"
        />

        <x-button @click="startUpload()" x-bind:disabled="loading">
            <span x-text="loading ? 'Идет загрузка...' : 'Загрузить файл'"></span>
        </x-button>
    </div>  


    2.Заполнение профиля (Profile Completion)
    Юзер ставит галочки в чекбоксах, а прогресс-бар с текстом внутри реагирует на каждое нажатие, высчитывая процент. 
    Здесь мы используем геттер Alpine get completion().

   <div x-data="{ 
        name: true, 
        email: false, 
        phone: false,
        get completion() {
            let filled = 0;
            if (this.name) filled++;
            if (this.email) filled++;
            if (this.phone) filled++;
            return Math.round((filled / 3) * 100);
        }
    }" class="space-y-4 max-w-sm">

        <div class="flex justify-between text-sm text-muted-foreground">
            <span>Заполнение профиля</span>
            <span x-text="completion + '%'"></span>
        </div>

        <x-progress value="completion" variant="primary" size="lg" :show-label="true" />

        <div class="space-y-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="name" class="rounded"> Указать имя
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="email" class="rounded"> Указать email
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="phone" class="rounded"> Указать телефон
            </label>
        </div>
    </div>


    3.Результаты опроса (Voting Poll)
    Выводим несколько вариантов ответа. Прогресс-бары показывают проценты голосов. 
    При клике на вариант количество голосов увеличивается, и все полоски плавно переползают на новые значения.

    <div x-data="{ 
        votes: [12, 8, 5], 
        totalVotes() { return this.votes.reduce((sum, val) => sum + val, 0); },
        getPercent(index) { 
            return this.totalVotes() > 0 ? Math.round((this.votes[index] / this.totalVotes()) * 100) : 0; 
        },
        vote(index) { this.votes[index]++; }
    }" class="space-y-4 max-w-sm">

        <div class="space-y-3">
            <div class="space-y-1">
                <div class="flex justify-between text-sm">
                    <span>Вариант 1</span>
                    <span x-text="getPercent(0) + '%'"></span>
                </div>
                <x-progress value="getPercent(0)" variant="primary" />
            </div>

            <div class="space-y-1">
                <div class="flex justify-between text-sm">
                    <span>Вариант 2</span>
                    <span x-text="getPercent(1) + '%'"></span>
                </div>
                <x-progress value="getPercent(1)" variant="warning" />
            </div>

            <div class="space-y-1">
                <div class="flex justify-between text-sm">
                    <span>Вариант 3</span>
                    <span x-text="getPercent(2) + '%'"></span>
                </div>
                <x-progress value="getPercent(2)" variant="destructive" />
            </div>
        </div>

        <div class="flex gap-2 pt-2 border-t border-border">
            <x-button size="sm" @click="vote(0)">Голосовать за 1</x-button>
            <x-button size="sm" variant="outline" @click="vote(1)">Голосовать за 2</x-button>
            <x-button size="sm" variant="destructive" @click="vote(2)">Голосовать за 3</x-button>
        </div>
    </div>
--}}

@once
    <style>
        @keyframes wp-progress-stripes {
            from { background-position: 1rem 0; }
            to { background-position: 0 0; }
        }
        .wp-stripes {
            background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);
            background-size: 1rem 1rem;
            animation: wp-progress-stripes 1s linear infinite;
        }
    </style>
@endonce

@props([
    'value'     => 0,
    'variant'   => 'primary',
    'size'      => 'md',
    'striped'   => false,
    'showLabel' => false,
    'class'     => null,
])

@php
    $sizes = ['sm' => 'h-1.5', 'md' => 'h-2.5', 'lg' => 'h-4'];
    $currentSize = $sizes[$size] ?? $sizes['md'];

    $wrapperClasses = cn('w-full bg-muted rounded-full overflow-hidden', $currentSize, $class);
    $barClasses = 'h-full rounded-full flex items-center justify-center text-xs font-medium transition-all duration-500 ease-out';

    $variants = [
        'primary'    => 'bg-primary text-primary-foreground',
        'success'    => 'bg-green-600 text-white',
        'warning'    => 'bg-amber-500 text-white',
        'destructive'=> 'bg-destructive text-destructive-foreground',
    ];

    // МАГИЯ: Проверяем, передано ли число (PHP) или выражение (Alpine)
    $isAlpineValue = !is_numeric($value);
    $isAlpineVariant = !in_array($variant, array_keys($variants));
    $isAlpineStriped = !in_array(strtolower((string)$striped), ['true', 'false', '1', '0', '']);

    // Если это число (статика) - кастуем к int и ограничиваем 0-100. Если Alpine - оставляем строку!
    $staticValue = $isAlpineValue ? null : min(100, max(0, (int)$value));

    // Статичные классы
    $staticVariantClass = $isAlpineVariant ? '' : ($variants[$variant] ?? $variants['primary']);
    $staticStripedClass = $isAlpineStriped ? '' : (filter_var($striped, FILTER_VALIDATE_BOOLEAN) ? 'wp-stripes' : '');
    $staticWidthStyle = $isAlpineValue ? '' : 'width: ' . $staticValue . '%;';
@endphp

<div 
    class="{{ $wrapperClasses }}" 
    role="progressbar" 
    aria-valuemin="0" 
    aria-valuemax="100"
    @if($isAlpineValue) :aria-valuenow="{{ $value }}" @else aria-valuenow="{{ $staticValue }}" @endif
>
    <div 
        class="{{ $barClasses }} {{ $staticVariantClass }} {{ $staticStripedClass }}"
        
        {{-- Если Alpine, вешаем :style, иначе статичный style --}}
        @if($isAlpineValue)
            :style="`width: ${ {{ $value }} }%;`"
        @else
            style="{{ $staticWidthStyle }}"
        @endif

        {{-- Если Alpine управляет цветом или полосками, вешаем :class --}}
        @if($isAlpineVariant || $isAlpineStriped)
            :class="[
                @if($isAlpineVariant)
                    ({{ $variant }}) === 'primary' ? 'bg-primary text-primary-foreground' : '',
                    ({{ $variant }}) === 'success' ? 'bg-green-600 text-white' : '',
                    ({{ $variant }}) === 'warning' ? 'bg-amber-500 text-white' : '',
                    ({{ $variant }}) === 'destructive' ? 'bg-destructive text-destructive-foreground' : '',
                @endif
                @if($isAlpineStriped)
                    {{ $striped }} ? 'wp-stripes' : '',
                @endif
            ]"
        @endif
    >
        @if ($showLabel && $size === 'lg')
            <span class="px-2" @if($isAlpineValue) x-show="{{ $value }} >= 10" @endif>
                @if($isAlpineValue)
                    <span x-text="{{ $value }}"></span>%
                @else
                    {{ $staticValue }}%
                @endif
            </span>
        @endif
    </div>
</div>