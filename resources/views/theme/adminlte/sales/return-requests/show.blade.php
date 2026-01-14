@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="mb-0">Return Request #{{ $returnRequest->reference_number }}</h1>
      <p class="text-muted">Raised on {{ $returnRequest->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="col-sm-6 text-right">
      <a href="{{ route('admin.sales.orders.show', $returnRequest->order_id) }}" class="btn btn-outline-primary">
        <i class="fas fa-shopping-cart"></i> View Original Order
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <!-- Left Column: Details & Items -->
    <div class="col-md-8">
      <!-- Return Details -->
      <div class="card mb-4">
        <div class="card-header bg-light"><strong>Return Information</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <p><strong>Reason:</strong> {{ $returnRequest->reason->reason }}</p>
              <p><strong>Description:</strong> {{ $returnRequest->description ?? 'No description provided.' }}</p>
            </div>
            <div class="col-sm-6">
              <p><strong>Refund Method:</strong>
                {{ $returnRequest->refund_method === 'account_credits' ? 'Account Credits' : 'Original Payment Method' }}
              </p>
              <p><strong>Refund Status:</strong>
                <span class="badge badge-{{ $returnRequest->refund_status === 'completed' ? 'success' : 'warning' }}">
                  {{ ucfirst($returnRequest->refund_status) }}
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Items to Return -->
      <div class="card mb-4">
        <div class="card-header bg-light"><strong>Items Requested for Return</strong></div>
        <div class="card-body p-0">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Product</th>
                <th>Purchased Qty</th>
                <th>Return Qty</th>
                <th>Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($returnRequest->items as $item)
                <tr>
                  <td>
                    {{ $item->orderLineItem->productVariant->product->translation->name ?? 'Product' }}
                    <br>
                    @foreach ($item->orderLineItem->productVariant->attributeValues as $val)
                      <span class="badge badge-light">{{ $val->attribute->name }}: {{ $val->value }}</span>
                    @endforeach
                  </td>
                  <td>{{ $item->orderLineItem->quantity }}</td>
                  <td><span class="text-danger font-weight-bold">{{ $item->quantity }}</span></td>
                  <td>{{ number_format($item->orderLineItem->price, 2) }}</td>
                  <td>{{ number_format($item->orderLineItem->price * $item->quantity, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Supporting Photos -->
      @if ($returnRequest->attachments->count() > 0)
        <div class="card mb-4">
          <div class="card-header bg-light"><strong>Supporting Photos</strong></div>
          <div class="card-body">
            <div class="row">
              @foreach ($returnRequest->attachments as $attachment)
                <div class="col-sm-3 mb-3">
                  <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                    <img src="{{ asset('storage/' . $attachment->file_path) }}" class="img-fluid img-thumbnail"
                      style="height: 150px; object-fit: cover;">
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Right Column: Actions -->
    <div class="col-md-4">
      <!-- Update Status Form -->
      <div class="card mb-4">
        <div class="card-header bg-primary"><strong>Manage Request</strong></div>
        <div class="card-body">
          <form action="{{ route('admin.sales.return-requests.update', $returnRequest->id) }}" method="POST"
            class="ajax-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label>Update Status</label>
              <select name="status" class="form-control">
                <option value="pending" {{ $returnRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $returnRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $returnRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="shipped" {{ $returnRequest->status === 'shipped' ? 'selected' : '' }}>Shipped (by
                  customer)</option>
                <option value="received" {{ $returnRequest->status === 'received' ? 'selected' : '' }}>Received</option>
                <option value="refunded" {{ $returnRequest->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
              </select>
            </div>

            <div class="form-group">
              <label>Shipping Cost Borne By</label>
              <select name="shipping_cost_borne_by" class="form-control">
                <option value="">-- Select --</option>
                <option value="company" {{ $returnRequest->shipping_cost_borne_by === 'company' ? 'selected' : '' }}>
                  Company</option>
                <option value="customer" {{ $returnRequest->shipping_cost_borne_by === 'customer' ? 'selected' : '' }}>
                  Customer</option>
              </select>
            </div>

            <div class="form-group">
              <label>Refund Status</label>
              <select name="refund_status" class="form-control">
                <option value="pending" {{ $returnRequest->refund_status === 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="completed" {{ $returnRequest->refund_status === 'completed' ? 'selected' : '' }}>Completed
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Internal Notes (Shared with customer)</label>
              <textarea name="admin_notes" class="form-control" rows="4">{{ $returnRequest->admin_notes }}</textarea>
            </div>

            <div class="form-group">
              <label>Shipping Label (PDF/Image)</label>
              <input type="file" name="shipping_label" class="form-control-file">
              @if ($returnRequest->shipping_label_path)
                <div class="mt-2">
                  <a href="{{ asset('storage/' . $returnRequest->shipping_label_path) }}" target="_blank"
                    class="btn btn-xs btn-outline-info">
                    <i class="fas fa-file-download"></i> View Current Label
                  </a>
                </div>
              @endif
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update Request</button>
          </form>
        </div>
      </div>

      <!-- Customer Info -->
      <div class="card mb-4">
        <div class="card-header bg-light"><strong>Customer Details</strong></div>
        <div class="card-body">
          <p><strong>Name:</strong> {{ $returnRequest->user->name }}</p>
          <p><strong>Email:</strong> {{ $returnRequest->user->email }}</p>
          <p><strong>Recent History:</strong> {{ $returnRequest->user->returnRequests()->count() }} returns raised</p>
        </div>
      </div>
    </div>
  </div>
@endsection
