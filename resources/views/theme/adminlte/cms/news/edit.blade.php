@extends('theme.adminlte.layouts.app')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>@lang('crud.edit_title', ['name' => 'News'])</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.cms.news.index') }}" class="btn btn-secondary float-sm-right">
                @lang('crud.back_to_list', ['name' => 'News'])
            </a>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.cms.news.update', $news->id) }}" method="POST" class="ajax-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            {{-- Main Content --}}
            <div class="col-md-8">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">News Details</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($news->category_id == $category->id)>
                                        {{ $category->translation->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" class="form-control"
                                   value="{{ $news->slug }}" required>
                        </div>

                        {{-- Translations --}}
                        @foreach (active_locals() as $locale)
                             @php $trans = $news->translations->where('locale', $locale)->first(); @endphp
                            <div class="form-group">
                                <label>Name ({{ strtoupper($locale) }})</label>
                                <input type="text" name="title[{{ $locale }}]" class="form-control"
                                       value="{{ $trans->title }}" required>
                            </div>

                            <div class="form-group">
                                <label>Intro ({{ strtoupper($locale) }})</label>
                                <textarea name="intro[{{ $locale }}]" class="form-control tinymce-editor">{{ $trans->intro }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Description ({{ strtoupper($locale) }})</label>
                                <textarea name="description[{{ $locale }}]" class="form-control tinymce-editor">{{ $trans->description }}</textarea>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-md-4">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">News Options</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control"
                                   value="{{ old('author', $news->author) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Position</label>
                            <input type="number" name="position" class="form-control"
                                   value="{{ old('position', $news->position) }}">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" value="1"
                                       class="custom-control-input" id="is_active"
                                       {{ old('is_active', $news->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Is Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Published At</label>
                            <input type="datetime-local" name="published_at" class="form-control"
                                   value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if ($news->image)
                                <img src="{{ asset('storage/' . $news->image) }}" class="img-lg img-thumbnail mt-2">
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Thumbnail <small>(optional)</small></label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            @if ($news->thumbnail)
                                <img src="{{ asset('storage/' . $news->thumbnail) }}" class="img-lg img-thumbnail mt-2">
                            @endif
                        </div>
                    </div>
                </div>

                @include('theme.adminlte.components._metas', ['model' => $news, 'grid' => 'col-md-12'])
            </div>

            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-secondary">@lang('crud.update')</button>
            </div>
        </div>
    </form>
@endsection
