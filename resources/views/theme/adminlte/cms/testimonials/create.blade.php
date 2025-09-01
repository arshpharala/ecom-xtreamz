@extends('theme.adminlte.layouts.app')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>@lang('crud.create_title', ['name' => 'Testimonial'])</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-secondary float-sm-right">
                @lang('crud.back_to_list', ['name' => 'Testimonial'])
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Main Content --}}
        <div class="col-md-8">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Testimonial Details</h3>
                </div>
                <form action="{{ route('admin.cms.testimonials.store') }}" method="POST" class="ajax-form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        {{-- Name Translations --}}
                        @foreach (active_locals() as $locale)
                            <div class="form-group">
                                <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
                                <input type="text" name="name[{{ $locale }}]" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
                                <textarea name="description[{{ $locale }}]" class="form-control" required></textarea>
                            </div>
                        @endforeach

                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>

                        {{-- Icon --}}
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">

                            @if (isset($testimonial) && $testimonial->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" class="img-lg img-thumbnail">
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-secondary">@lang('crud.create')</button>
                    </div>
            </div>
        </div>

        {{-- Sidebar Card (Meta/Options) --}}
        <div class="col-md-4">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Testimonial Options</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="position">Rating</label>
                        <input type="number" name="rating" min="1" max="5" value="5"
                            class="form-control" required>
                    </div>
                    {{-- Position --}}
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="number" name="position" class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('position', 0) }}">
                        @error('position')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Is Visible --}}
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input"
                                id="is_active">
                            <label class="custom-control-label" for="is_active">Is Active</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Compnay Logo</label>
                        <input type="file" name="company_logo" class="form-control" accept="image/*">

                        @if (isset($testimonial) && $testimonial->company_logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $testimonial->company_logo) }}"
                                    class="img-lg img-thumbnail">
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
