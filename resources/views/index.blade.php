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

        <x-popover>
            <x-popover.trigger as="a" class="text-blue-600 cursor-pointer">
                <x-avatar 
                    src="{{ Vite::asset('resources/images/webp/avatar.webp') }}" 
                    alt="Иван" 
                    size="md" 
                    shape="rounded" 
                    status="online" 
                />
            </x-popover.trigger>
            
            <x-popover.content placement="bottom-start" width="w-72">
                <form class="p-4 flex flex-col gap-3">
                    <x-input type="email" showError="false" placeholder="Email" class="border p-2 rounded"/>
                    <x-input type="password" showError="false" placeholder="Пароль" class="border p-2 rounded"/>
                    <x-button type="submit" class="bg-blue-600 text-white p-2 rounded">Войти</x-button>
                </form>
            </x-popover.content>
        </x-popover>
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

    {{-- Наша новая красивая пагинация --}}
    <x-pagination class="mt-12" />

     <x-post-navigation mode="fixed" :show-title="false" />

    {{-- {!! get_the_posts_navigation() !!} --}}
@endsection

@section('sidebar')
    @include('sections.sidebar')
@endsection
