@extends('theme.medibazaar.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb-area pt-125 pb-125" style="background-image:url({{ asset('storage/' . $page->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-text">
                            <h2>{{ __('News') }}</h2>
                        </div>
                        <ul class="breadcrumb-menu">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><span>News</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->

    <!-- blog-area-start -->
    <div class="blog-area pt-105 pb-100">
        <div class="container">
            <div class="row">
                @forelse($news as $item)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="blog-wrapper mb-55">
                            <div class="blog-img pos-rel">
                                <a href="{{ route('news.show', $item->id) }}">
                                    <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/img/blog/default.jpg') }}"
                                        alt="{{ $item->translation->title }}">
                                </a>
                                <span class="blog-tag color-{{ $item->category_id ?? 1 }}">
                                    {{ optional($item->category)->name ?? 'General' }}
                                </span>
                            </div>
                            <div class="blog-text">
                                <div class="blog-meta">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <a href="{{ route('news.show', $item->id) }}">
                                            {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                                        </a>
                                    </span>
                                </div>
                                <h4>
                                    <a href="{{ route('news.show', $item->id) }}">
                                        {{ $item->translation->title }}
                                    </a>
                                </h4>
                                <p>{{ Str::limit($item->translation->intro, 100) }}</p>
                                <div class="b-button gray-b-button">
                                    <a href="{{ route('news.show', $item->id) }}">
                                        Read more <i class="far fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No news found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="basic-pagination mt-20 basic-pagination-2 text-center">
                        {{ $news->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- blog-area-end -->
@endsection
