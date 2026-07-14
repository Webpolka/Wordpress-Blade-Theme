@extends('layouts.app')


@section('content')

    @include('partials.page-header')

      <x-section variant="dark" size="xl">
        <h2 class="text-white">Готовы начать?</h2>

        <x-empty-state 
        title="Страница не найдена" 
        description="К сожалению, такой страницы больше не существует, или она была перемещена."
    >        
        <x-slot:icon>
            <svg class="w-16 h-16 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </x-slot:icon>
        
        <x-button variant="primary">
            Вернуться на главную
        </x-button>
    </x-empty-state>
    </x-section>
 
 
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
