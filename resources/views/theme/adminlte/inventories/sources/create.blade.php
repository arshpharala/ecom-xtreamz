@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.create_title', ['name' => 'Inventory Source'])</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.inventory.sources.index') }}" class="btn btn-secondary float-sm-right">
        @lang('crud.back_to_list', ['name' => 'Inventory Source'])
      </a>
    </div>
  </div>
@endsection

@section('content')
  <form method="post" action="{{ route('admin.inventory.sources.store') }}">
    @csrf

    <div class="row">
      @include('theme.adminlte.inventories.sources._form', [
          'source' => $source,
          'countries' => $countries ?? [],
          'provinces' => $provinces ?? [],
          'cities' => $cities ?? [],
      ])
    </div>

    <div class="py-2">
      <button class="btn btn-primary">@lang('crud.save')</button>
    </div>
  </form>
@endsection
