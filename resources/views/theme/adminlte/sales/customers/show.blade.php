@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Customer: {{ $user->name }}</h1>
    </div>
    <div class="col-sm-6 d-flex justify-content-end">
      <a href="{{ route('admin.sales.customers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Customers
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <!-- Stats Boxes -->
    <div class="col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $user->orders->count() }}</h3>
          <p>Total Orders</p>
        </div>
        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ number_format($user->orders->sum('total'), 2) }} {{ active_currency() }}</h3>
          <p>Total Spent</p>
        </div>
        <div class="icon"><i class="fas fa-wallet"></i></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $user->billingAddresses->count() }}</h3>
          <p>Saved Addresses</p>
        </div>
        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ $user->cards->count() }}</h3>
          <p>Saved Cards</p>
        </div>
        <div class="icon"><i class="fas fa-credit-card"></i></div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <!-- User Info -->
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-3">Basic Info</h5>
          <div class="row">
            <div class="col-sm-12"><strong>Name:</strong> {{ $user->name }}</div>
            <div class="col-sm-12"><strong>Email:</strong> {{ $user->email }}</div>
            <div class="col-sm-12"><strong>Status:</strong>
              <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
            <div class="col-sm-12"><strong>Last Login:</strong> {{ $user->last_login_at ?? '-' }}</div>
            <div class="col-sm-12"><strong>Password Changed:</strong> {{ $user->password_changed_at ?? '-' }}</div>
            <div class="col-sm-12"><strong>Joined:</strong> {{ $user->created_at->format('d M Y, h:i A') }}</div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-md-9">
  <!-- Tabs -->
  <ul class="nav nav-tabs" id="customerTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
        Orders
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab">
        Saved Addresses
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cards" type="button" role="tab">
        Saved Cards
      </button>
    </li>
  </ul>
  <div class="tab-content border p-4 bg-white" id="customerTabContent">
    <!-- Orders Tab -->
    <div class="tab-pane fade show active" id="orders" role="tabpanel">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#Order No</th>
            <th>Total</th>
            <th>Status</th>
            <th>Placed At</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($user->orders as $order)
            <tr>
              <td>{{ $order->order_number }}</td>
              <td>{{ number_format($order->total, 2) }} {{ active_currency() }}</td>
              <td>
                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                  {{ ucfirst($order->payment_status) }}
                </span>
              </td>
              <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
              <td>
                <a href="{{ route('admin.sales.orders.show', $order->id) }}"
                  class="btn btn-sm btn-outline-primary">View</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No orders found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Saved Addresses Tab -->
    <div class="tab-pane fade" id="addresses" role="tabpanel">
      @forelse($user->billingAddresses as $address)
        <div class="border p-3 mb-3">
          <strong>{{ $address->name }}</strong><br>
          {{ $address->address }}, {{ $address->area }}, {{ $address->city }}, {{ $address->province }}<br>
          <strong>Phone:</strong> {{ $address->phone }}<br>
          <strong>Landmark:</strong> {{ $address->landmark ?? '-' }}
        </div>
      @empty
        <p>No saved addresses.</p>
      @endforelse
    </div>

    <!-- Saved Cards Tab -->
    <div class="tab-pane fade" id="cards" role="tabpanel">
      @forelse($user->cards as $card)
        <div class="border p-3 mb-3">
          <strong>**** **** **** {{ $card->card_last_four }}</strong><br>
          {{ ucfirst($card->card_brand) }} • Expires {{ $card->expiry_month }}/{{ $card->expiry_year }}<br>
          <strong>Gateway:</strong> {{ ucfirst($card->gateway) }}
        </div>
      @empty
        <p>No saved cards.</p>
      @endforelse
    </div>
  </div>
    </div>
  </div>




@endsection
