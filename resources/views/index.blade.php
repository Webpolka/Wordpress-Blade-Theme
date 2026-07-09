@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    <x-button
        onclick="window.modalManager.openDynamic({
        title: 'Успех',
        content: window.ModalContentFactory.message({
                text: 'Операция выполнена!',
                variant: 'success'
            })
        })">
        Ок!
    </x-button>



    <div class="p-6 rounded-md bg-white space-y-6 max-w-3xl mx-auto">

        <x-accordion-faq :multiple="true">
            <x-accordion-faq.item question="Сколько стоит доставка?">
                Доставка бесплатна при заказе от 5000 руб.
            </x-accordion-faq.item>
            <x-accordion-faq.item question="Как вернуть товар?">
                Вернуть товар можно в течение 14 дней.
                <x-accordion-faq>
                    <x-accordion-faq.item>
                        <x-slot:question>
                            <span class="text-blue-600">Важно:</span> Как вернуть товар?
                        </x-slot:question>
                        Ответ здесь...
                    </x-accordion-faq.item>
                </x-accordion-faq>
            </x-accordion-faq.item>
        </x-accordion-faq>


        <x-tabs default="account">
            <x-tabs.list>
                <x-tabs.trigger value="account">Аккаунт</x-tabs.trigger>
                <x-tabs.trigger value="password">Пароль</x-tabs.trigger>
            </x-tabs.list>

            <x-tabs.content value="account">
                Настройки аккаунта...
            </x-tabs.content>
            <x-tabs.content value="password">
                Смена пароля...<br>
                Смена пароля...<br>
                <br>
                Смена пароля...
                Смена пароля...
            </x-tabs.content>
        </x-tabs>



     <div class="mb-4 flex items-center justify-between">
      <h2 class="text-2xl font-bold">Наши проекты</h2>
      <div data-swiper-pagination="projects-slider" class="inline-flex justify-center gap-2 !w-[initial]"></div>
  </div>
  
  <x-slider 
      id="projects-slider"
      pagination-position="external"
      :slides-per-view="2"
  >
      <x-slider.slide>Проект 1</x-slider.slide>
      <x-slider.slide>Проект 2</x-slider.slide>
      <x-slider.slide>Проект 3</x-slider.slide>
  </x-slider>
    </div>


    @if (!have_posts())
        <x-alert type="warning">
            {!! __('Sorry, no results were found.', 'sage') !!}
        </x-alert>

        {!! get_search_form(false) !!}
    @endif

    @while (have_posts())
        @php(the_post())
        @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
    @endwhile

    {!! get_the_posts_navigation() !!}
@endsection

@section('sidebar')
    @include('sections.sidebar')
@endsection
