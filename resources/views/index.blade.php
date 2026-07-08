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



    <div class="p-6 rounded-md bg-white space-y-6 max-w-md">

        <div class="flex gap-4">

            <x-tooltip text="Синий" color="bg-blue-500 text-white">
                <x-button>Blue</x-button>
            </x-tooltip>

            <x-tooltip text="Зелёный" color="bg-green-500 text-white">
                <x-button>Green</x-button>
            </x-tooltip>

            <x-tooltip text="Градиент" color="bg-gradient-to-r from-blue-500 to-purple-500 text-white">
                <x-button>Gradient</x-button>
            </x-tooltip>

            <x-tooltip text="Без стрелки" :arrow="false">
                <x-button>Hover me</x-button>
            </x-tooltip>

        </div>
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
