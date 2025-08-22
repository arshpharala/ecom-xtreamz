@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.edit.title', ['name' => 'Inventory Source'])</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.inventory.sources.index') }}" class="btn btn-secondary float-sm-right">
        @lang('crud.back_to_list', ['name' => 'Inventory Source'])
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Inventory Source</h4>
    <a href="{{ route('admin.inventory.sources.index') }}" class="btn btn-light">Back</a>
  </div>

  <form method="post" action="{{ route('admin.inventory.sources.update', $source->id) }}">
    @method('PUT')
    @include('theme.adminlte.inventories.sources._form', [
        'source' => $source,
        'countries' => $countries,
        'provinces' => $provinces,
        'cities' => $cities,
    ])

    <div class="my-3">
      <button class="btn btn-primary">@lang('crud.edit.submit')</button>
    </div>
  </form>
@endsection
