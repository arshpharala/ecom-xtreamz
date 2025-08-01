@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.create_title', ['name' => 'Coupon'])</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.coupons.index') }}" class="btn btn-secondary float-sm-right">
        @lang('crud.back_to_list', ['name' => 'Coupon'])
      </a>
    </div>
  </div>
@endsection

@section('content')
  {!! Form::open(['route' => 'admin.catalog.coupons.store', 'class' => 'ajax-form']) !!}
  <div class="row">
    {{-- Main Content --}}
    <div class="col-md-8">
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">Coupon Details</h3>
        </div>
        <div class="card-body">

          {{-- Code --}}
          <div class="form-group">
            {!! Form::label('code', 'Coupon Code') !!}
            {!! Form::text('code', null, ['class' => 'form-control', 'required']) !!}
          </div>

          {{-- Type --}}
          <div class="form-group">
            {!! Form::label('type', 'Discount Type') !!}
            {!! Form::select('type', ['fixed' => 'Fixed', 'percentage' => 'Percentage'], null, [
                'class' => 'form-control',
                'required',
            ]) !!}
          </div>

          {{-- Value --}}
          <div class="form-group">
            {!! Form::label('value', 'Discount Value') !!}
            {!! Form::number('value', null, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
          </div>

          {{-- Minimum Cart Amount --}}
          <div class="form-group">
            {!! Form::label('min_cart_amount', 'Minimum Cart Amount') !!}
            {!! Form::number('min_cart_amount', null, ['class' => 'form-control', 'step' => '0.01']) !!}
          </div>

          {{-- Date Range --}}
          <div class="form-row">
            <div class="form-group col-md-6">
              {!! Form::label('start_at', 'Start At') !!}
              {!! Form::input('datetime-local', 'start_at', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
              {!! Form::label('end_at', 'End At') !!}
              {!! Form::input('datetime-local', 'end_at', null, ['class' => 'form-control']) !!}
            </div>
          </div>

        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-primary">@lang('crud.create')</button>
        </div>
      </div>
    </div>

    {{-- Sidebar Options --}}
    <div class="col-md-4">
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">Coupon Options</h3>
        </div>
        <div class="card-body">

          {{-- Usage Limit --}}
          <div class="form-group">
            {!! Form::label('max_usage', 'Usage Limit') !!}
            {!! Form::number('max_usage', null, ['class' => 'form-control']) !!}
          </div>

          <div class="form-group">
            {!! Form::label('max_usage_per_user', 'Usage Limit Per Usage') !!}
            {!! Form::number('max_usage_per_user', null, ['class' => 'form-control']) !!}
          </div>

          {{-- First Time Customer --}}
          <div class="form-group">
            <div class="custom-control custom-switch">
              {!! Form::checkbox('first_time_only', 1, null, [
                  'class' => 'custom-control-input',
                  'id' => 'first_time_only',
              ]) !!}
              {!! Form::label('first_time_only', 'First Time Customer Only', ['class' => 'custom-control-label']) !!}
            </div>
          </div>

          {{-- Status --}}
          <div class="form-group">
            <div class="custom-control custom-switch">
              {!! Form::checkbox('is_active', 1, true, [
                  'class' => 'custom-control-input',
                  'id' => 'is_active',
              ]) !!}
              {!! Form::label('is_active', 'Active', ['class' => 'custom-control-label']) !!}
            </div>
          </div>

          {{-- Product Variants --}}
          <div class="form-group">
            {!! Form::label('product_variant_ids[]', 'Applicable Product Variants') !!}
            {!! Form::select(
                'product_variant_ids[]',
                $variants->pluck('product.translation.name', 'id')->map(function ($name, $id) use ($variants) {
                    $sku = $variants->find($id)?->sku;
                    return "$sku - $name";
                }),
                null,
                ['class' => 'form-control select2', 'multiple'],
            ) !!}
            <small class="form-text text-muted">Leave empty to apply to all products.</small>
          </div>

        </div>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
@endsection
