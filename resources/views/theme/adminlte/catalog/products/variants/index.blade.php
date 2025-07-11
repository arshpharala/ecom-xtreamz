@foreach ($variants as $variant)
  <div class="card shadow-sm mb-3 border-0">
    <div class="card-header bg-white py-2" style="border-bottom:1px solid #e9ecef;">
      <div class="card-title">
        <span class="h6 mb-0 text-secondary">{{ $variant->sku }}</span>
        <span class="badge badge-light ml-2">{{ config('app.currency', 'AED') }} {{ $variant->price }} </span>
      </div>
      <div class="card-tools">
          <div class="dropdown">
            <a href="#" class="text-muted" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="#" onclick="getAside()"
                data-url="{{ route('admin.catalog.product.variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}">Edit</a></li>
              <li><a class="dropdown-item btn-delete" href="#"
                data-url="{{ route('admin.catalog.product.variants.destroy', ['product' => $product->id, 'variant' => $variant->id]) }}">Delete</a></li>
            </ul>
          </div>

      </div>
    </div>
    <div class="card-body py-2 px-3">

      <div class="d-flex flex-wrap align-items-center mb-2">
        <span class="text-muted small mr-3">
          <i class="fas fa-box"></i> Stock: <strong>{{ $variant->stock }}</strong>
        </span>
        @if($variant->shipping)
          <span class="text-muted small mr-2">
            <i class="fas fa-cube"></i>
            L: <b>{{ $variant->shipping->length ?? '-' }}</b> /
            W: <b>{{ $variant->shipping->width ?? '-' }}</b> /
            H: <b>{{ $variant->shipping->height ?? '-' }}</b>
          </span>
          <span class="text-muted small mr-2">
            <i class="fas fa-weight-hanging"></i>
            <b>{{ $variant->shipping->weight ?? '-' }}</b> kg
          </span>
        @endif
      </div>

      <div class="mb-2 border-top pt-2">
        <div class="d-flex flex-wrap">
          @foreach ($variant->attributeValues as $attr)
            <span class="badge badge-pill badge-primary mr-1 mb-1">
              {{ $attr->attribute->name }}: {{ $attr->value }}
            </span>
          @endforeach
        </div>
      </div>

      @if ($variant->attachments->count())
        <div class="mt-2 d-flex flex-wrap align-items-center" style="gap: 10px;">
          @foreach ($variant->attachments as $attachment)
              <img src="{{ asset('storage/' . $attachment->file_path) }}" class="img-thumbnail img-md">
          @endforeach
        </div>
      @endif

    </div>
  </div>
@endforeach
