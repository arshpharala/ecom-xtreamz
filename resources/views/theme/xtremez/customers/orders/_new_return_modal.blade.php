<div class="modal fade" id="newReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Request a Return for Order #{{ $order->order_number }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('customers.returns.store') }}" method="POST" class="ajax-form"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Reason for Return</label>
              <select name="return_reason_id" class="form-select" required>
                @foreach ($reasons as $reason)
                  <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                @endforeach
              </select>
            </div>

            <div id="orderItemsContainer" class="col-12 mt-4" style="display: none;">
              <h6>Select Items to Return</h6>
              <div id="itemsList"></div>
            </div>

            <div class="col-12 mt-3">
              <label class="form-label">Description / Issue Highlights</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Tell us more about the issue..."></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Refund Method</label>
              <select name="refund_method" class="form-select">
                <option value="original_method">Original Payment Method</option>
                <option value="account_credits">Account Credits</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Attach Photos (Optional)</label>
              <input type="file" name="attachments[]" class="form-control" multiple accept="image/*">
              <div class="form-text">Max 5 images. Helps us process faster.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Return Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('newReturnModal');
    modal.addEventListener('show.bs.modal', function() {
      const orderId = '{{ $order->id }}';
      $.get('/customers/returns/order-items/' + orderId, function(data) {
        $('#itemsList').html(data);
        $('#orderItemsContainer').show();
      }).fail(function() {
        $('#itemsList').html(
          '<div class="alert alert-warning">This order is not eligible for return or items are not available.</div>'
          );
        $('#orderItemsContainer').show();
      });
    });
  });
</script>
