@extends('layouts.app')


@section('content')

    @include('partials.page-header')
<x-accordion-faq>
        <x-accordion-faq.item>
            <x-slot:question>
                <span class="text-blue-600">Важно:</span> Как вернуть товар?
            </x-slot:question>
            Ответ здесь...
        </x-accordion-faq.item>
    </x-accordion-faq>

   @php
        $gallery_media = [
                [
                    'src' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb', 
                    'title' => 'Просто красивая картинка (Можно задвоить клик для зума)',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 
                    'title' => 'YouTube видео (Rick Astley)',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://vimeo.com/76979871', 
                    'title' => 'Vimeo видео (The Mountain)',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://rutube.ru/video/9196c1067e4d925a4e5899a23c00c63b/?r=wd', 
                    'title' => 'Rutube видео',
                    'thumb' => ''
                ],
                [
                    'src' => 'https://vk.com/video-211869299_456240914', 
                    'title' => 'VK Видео',
                    'thumb' => ''
                ],
                 [
                    'src' => 'https://www.dailymotion.com/video/x9lygl2', // <-- DAILYMOTION
                    'title' => 'Dailymotion видео',
                    'thumb' => ''
                ],
                [
                    'src' => Vite::asset('resources/video/dj-lovely.mp4'), 
                    'title' => 'Просто локальный видосик',
                    'thumb' => ''
                ]        
            ];      
    @endphp

  <x-product-gallery :images="$gallery_media" />
    
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
