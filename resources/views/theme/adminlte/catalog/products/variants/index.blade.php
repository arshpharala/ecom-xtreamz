@foreach ($variants as $variant)
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Product Variants #{{ $loop->iteration }}</h3>
      <div class="card-tools">
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
            data-bs-toggle="dropdown" aria-expanded="false">
            Action
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#" onclick="getAside()"
                data-url="{{ route('admin.catalog.product.variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}">Edit</a>
            </li>
            <li><a class="dropdown-item btn-delete" href="#"
                data-url="{{ route('admin.catalog.product.variants.destroy', ['product' => $product->id, 'variant' => $variant->id]) }}">Delete</a>
            </li>
          </ul>
        </div>

      </div>
    </div>
    <div class="card-body p-0">
      <div class="row">
        <div class="col-md-12">

          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <th>SKU</th>
                  <td>{{ $variant->sku }}</td>
                </tr>
                <tr>
                  <th>Price</th>
                  <td>{{ $variant->price }}</td>
                </tr>
                <tr>
                  <th>Stock</th>
                  <td>{{ $variant->stock }}</td>
                </tr>
                @foreach ($variant->attributeValues as $attr)
                  <tr>
                    <th>{{ $attr->attribute->name }}</th>
                    <td>{{ $attr->value }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="2" class="uploaded-image-box" >
                    @foreach ($variant->attachments as $attachment)
                      <div class="uploaded-image">
                        <img src="{{ asset('storage/' . $attachment->file_path) }}" class="img-thumbnail">
                        <button type="button" class="delete-image-btn" data-id="{{ $attachment->id }}">
                          &times;
                        </button>
                      </div>
                    @endforeach
                  </td>

                </tr>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
