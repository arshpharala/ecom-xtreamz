@extends('theme.xtremez.layouts.app')

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-white" title="Home">
              <!-- <i class="bi bi-house"></i> -->
              Home
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="{{ $page->title ?? 'Featured' }}">
            {{ $page->title ?? 'Page' }}
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection
@section('content')
  <section class="heading-section py-5">
    <div class="container">
      <div class="heading-row">
        <h2 class="section-title fs-1 text-center m-0">Featured</h2>
      </div>
    </div>
  </section>


    @include('theme.xtremez.components.products.gift-set', ['products' => $giftSetProducts])
    
@endsection
