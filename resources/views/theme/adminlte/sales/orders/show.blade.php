@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="mb-0">Order #{{ $order->order_number }}</h1>
      <p class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="col-sm-6 text-right">
      <a href="#" class="btn btn-outline-primary" target="_blank">
        <i class="fas fa-file-invoice"></i> View / Download Invoice
      </a>
      <a href="{{ route('admin.sales.orders.index') }}" class="btn btn-outline-secondary ml-2">
        <i class="fas fa-arrow-left"></i> Back to Orders
      </a>
    </div>
  </div>
@endsection

@section('content')
  {{--
        <div class="row">
          <div class="col-12">

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> {{ setting('site_title') }}
                    <small class="float-right">Date: {{ $order->created_at->format('d/m/Y') }}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>Admin, Inc.</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (804) 123-5432<br>
                    Email: info@almasaeedstudio.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong>John Doe</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (555) 539-1037<br>
                    Email: john.doe@example.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #007612</b><br>
                  <br>
                  <b>Order ID:</b> 4F3S8J<br>
                  <b>Payment Due:</b> 2/22/2014<br>
                  <b>Account:</b> 968-34567
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Qty</th>
                      <th>Product</th>
                      <th>Serial #</th>
                      <th>Description</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>1</td>
                      <td>Call of Duty</td>
                      <td>455-981-221</td>
                      <td>El snort testosterone trophy driving gloves handsome</td>
                      <td>$64.50</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Need for Speed IV</td>
                      <td>247-925-726</td>
                      <td>Wes Anderson umami biodiesel</td>
                      <td>$50.00</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Monsters DVD</td>
                      <td>735-845-642</td>
                      <td>Terry Richardson helvetica tousled street art master</td>
                      <td>$10.70</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Grown Ups Blue Ray</td>
                      <td>422-568-642</td>
                      <td>Tousled lomo letterpress</td>
                      <td>$25.99</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Payment Methods:</p>
                  <img src="../../dist/img/credit/visa.png" alt="Visa">
                  <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../../dist/img/credit/american-express.png" alt="American Express">
                  <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                    plugg
                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Amount Due 2/22/2014</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>$250.30</td>
                      </tr>
                      <tr>
                        <th>Tax (9.3%)</th>
                        <td>$10.34</td>
                      </tr>
                      <tr>
                        <th>Shipping:</th>
                        <td>$5.80</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>$265.24</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                  <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                  </button>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row --> --}}



  <div class="row">
    <!-- Left Column -->
    <div class="col-md-6">
      <!-- Customer Info -->
      <div class="card mb-4">
        <div class="card-header"><strong>Customer Details</strong></div>
        <div class="card-body">
          <p><strong>Name:</strong> {{ $order->billingAddress->name }}</p>
          <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
          <p><strong>Phone:</strong> {{ $order->billingAddress->phone }}</p>
        </div>
      </div>

      <!-- Billing Address -->
      <div class="card mb-4">
        <div class="card-header"><strong>Billing Address</strong></div>
        <div class="card-body">
          <address>
            {!! $order->address->render() !!}
          </address>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-6">
      <!-- Payment Details -->
      <div class="card mb-4">
        <div class="card-header"><strong>Payment Details</strong></div>
        <div class="card-body">
          <p><strong>Status:</strong> <span
              class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span>
          </p>
          <p><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
          <p><strong>Gateway Ref:</strong> {{ $order->external_reference ?? 'N/A' }}</p>
          <p><strong>Paid At:</strong> {{ $order->updated_at->format('d M Y, h:i A') }}</p>
        </div>
      </div>

      <!-- Order Management -->
      <div class="card mb-4">
        <div class="card-header"><strong>Order Management</strong></div>
        <div class="card-body">
          <!-- Status Update -->
          <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST" class="ajax-form mb-3">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>Order Status</label>
              <select name="status" class="form-control">
                @php
                  $statuses = ['draft', 'placed', 'processing', 'confirmed', 'fulfilled', 'cancelled'];
                @endphp
                @foreach ($statuses as $s)
                  <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}
                  </option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
          </form>

          <!-- Confirm/Cancel Actions -->
          @if ($order->status === 'placed')
            <div class="row">
              <div class="col-md-6">
                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#confirmModal">
                  <i class="fas fa-check"></i> Confirm Order
                </button>
              </div>
              <div class="col-md-6">
                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#cancelModal">
                  <i class="fas fa-times"></i> Cancel Order
                </button>
              </div>
            </div>
          @endif

          <!-- Delivery Date -->
          @if ($order->status === 'confirmed' || $order->status === 'fulfilled')
            <form action="{{ route('admin.sales.orders.update', $order->id) }}" method="POST" class="ajax-form mt-3">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label>Delivery Date</label>
                <div class="input-group">
                  <input type="datetime-local" name="delivered_at" class="form-control"
                    value="{{ $order->delivered_at ? $order->delivered_at->format('Y-m-d\TH:i') : '' }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Update Delivery</button>
                  </div>
                </div>
                <small class="text-muted">Setting this date starts the 30-day return window.</small>
              </div>
            </form>
          @endif

          @if ($order->delivered_at)
            <p class="mt-2 text-success">
              <i class="fas fa-check-circle"></i> Delivered on {{ $order->delivered_at->format('d M Y, h:i A') }}
            </p>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Order Items -->
  <div class="card mb-4">
    <div class="card-header"><strong>Order Items</strong></div>
    <div class="card-body p-0">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Category</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->lineItems as $item)
            <tr>
              <td>{{ $item->productVariant->product->category->translation->name ?? 'Product' }}</td>
              <td>{{ $item->productVariant->product->translation->name ?? 'Product' }}</td>
              <td>
                @foreach ($item->productVariant->attributeValues as $val)
                  <span class="badge badge-light">{{ $val->attribute->name }}: {{ $val->value }}</span>
                @endforeach
              </td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->price, 2) }} {{ strtoupper(active_currency()) }}</td>
              <td>{{ number_format($item->subtotal, 2) }} {{ strtoupper(active_currency()) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Order Totals -->
  <div class="row">
    <div class="col-md-6 offset-md-6">
      <div class="card">
        <div class="card-header"><strong>Summary</strong></div>
        <div class="card-body">
          <ul class="list-unstyled">
            <li class="d-flex justify-content-between">
              <span>Subtotal:</span>
              <strong>{{ number_format($order->total, 2) }} {{ strtoupper(active_currency()) }}</strong>
            </li>
            <li class="d-flex justify-content-between">
              <span>Tax:</span>
              <strong>0.00 {{ strtoupper(active_currency()) }}</strong>
            </li>
            <li class="d-flex justify-content-between border-top pt-2 mt-2">
              <span>Total:</span>
              <strong>{{ number_format($order->total, 2) }} {{ strtoupper(active_currency()) }}</strong>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
