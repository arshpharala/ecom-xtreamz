<div class="modal fade" id="newReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content card card-primary card-outline shadow-lg border-0">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold fw-bold">
          <i class="bi bi-arrow-return-left mr-2 me-2 text-primary"></i> Request Return — #{{ $order->reference_number }}
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('customers.returns.store') }}" method="POST" class="ajax-form"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <div class="modal-body">
          <!-- Step 1: Select Items -->
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="text-uppercase text-muted small font-weight-bold mb-0">1. Select Items & Quantities</h6>
              <div class="custom-control custom-checkbox small" id="selectAllContainer" style="display: none;">
                <input type="checkbox" class="custom-control-input" id="selectAllItems">
                <label class="custom-control-label font-weight-normal" for="selectAllItems">Select All</label>
              </div>
            </div>

            <div class="input-group input-group-sm mb-3" id="itemSearchContainer" style="display: none;">
              <div class="input-group-prepend">
                <span class="input-group-text bg-white border-right-0"><i class="bi bi-search text-muted"></i></span>
              </div>
              <input type="text" id="returnItemSearch" class="form-control border-left-0"
                placeholder="Search by product name...">
            </div>

            <div id="orderItemsContainer" style="display: none;">
              <div id="itemsList" class="bg-light p-3 rounded shadow-sm" style="max-height: 380px; overflow-y: auto;">
                <!-- Items will be loaded here via AJAX -->
              </div>
            </div>
            <div id="itemsLoading" class="text-center py-4">
              <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
              <p class="mt-2 text-muted small">Loading order items...</p>
            </div>
          </div>

          <!-- Step 2: Reason & Details -->
          <div class="mb-4">
            <h6 class="text-uppercase text-muted small font-weight-bold mb-3">2. Describe the Issue</h6>
            <div class="row">
              <div class="col-md-6 form-group">
                <label class="font-weight-bold small">Reason for Return <span class="text-danger">*</span></label>
                <select name="return_reason_id" class="form-control" required>
                  <option value="" disabled selected>Select a reason...</option>
                  @foreach ($reasons as $reason)
                    <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 form-group">
                <label class="font-weight-bold small">Preferred Refund Method</label>
                <select name="refund_method" class="form-control">
                  <option value="original_method">Original Payment Method</option>
                  <option value="account_credits">Account Credits (Instant)</option>
                </select>
              </div>
              <div class="col-12 form-group mt-2">
                <label class="font-weight-bold small">Additional Details</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Please provide more details..."></textarea>
              </div>
            </div>
          </div>

          <!-- Step 3: Attach Photos -->
          <div class="mb-2">
            <h6 class="text-uppercase text-muted small font-weight-bold mb-3">3. Add Photos (Max 5)</h6>
            <div class="custom-file mb-3">
              <input type="file" name="attachments[]" id="returnPhotos" class="custom-file-input" multiple
                accept="image/*">
              <label class="custom-file-label" for="returnPhotos">Choose files</label>
            </div>
            <div id="imagePreviewContainer" class="row row-cols-5 g-2 mt-2">
              <!-- Previews will appear here -->
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary font-weight-bold fw-bold px-4">Submit Return Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('newReturnModal');
    if (!modal) return;

    // Handle AJAX item loading
    modal.addEventListener('show.bs.modal', function() {
      const orderId = '{{ $order->id }}';
      const itemsList = document.getElementById('itemsList');
      const container = document.getElementById('orderItemsContainer');
      const loader = document.getElementById('itemsLoading');
      const searchContainer = document.getElementById('itemSearchContainer');
      const selectAllContainer = document.getElementById('selectAllContainer');

      if (itemsList.children.length === 0) {
        $.get('/customers/returns/order-items/' + orderId, function(data) {
          loader.classList.add('d-none');
          $(itemsList).html(data);
          container.style.display = 'block';
          if (searchContainer) searchContainer.style.display = 'flex';
          if (selectAllContainer) selectAllContainer.style.display = 'block';
        }).fail(function() {
          loader.classList.add('d-none');
          itemsList.innerHTML =
            '<div class="alert alert-warning mb-0">This order is not eligible for return.</div>';
          container.style.display = 'block';
        });
      }
    });

    // Handle Item Search
    const searchInput = document.getElementById('returnItemSearch');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.return-item-row');

        rows.forEach(row => {
          const name = row.querySelector('.return-item-name').textContent.toLowerCase();
          if (name.includes(query)) {
            row.style.setProperty('display', 'block', 'important');
          } else {
            row.style.setProperty('display', 'none', 'important');
          }
        });
      });
    }

    // Handle Select All
    const selectAllCheckbox = document.getElementById('selectAllItems');
    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.return-item-checkbox');

        checkboxes.forEach(cb => {
          const row = cb.closest('.return-item-row');
          // Only select visible ones if searching
          if (row.style.display !== 'none') {
            cb.checked = this.checked;
            cb.dispatchEvent(new Event('change'));
          }
        });
      });
    }

    // Handle Image Preview
    const fileInput = document.getElementById('returnPhotos');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if (fileInput) {
      fileInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        const files = Array.from(this.files).slice(0, 5); // Limit to 5

        files.forEach(file => {
          const reader = new FileReader();
          reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
              <div class="card h-100 mb-0 shadow-sm">
                <img src="${e.target.result}" class="card-img-top img-thumbnail" style="height: 60px; object-fit: cover;">
              </div>
            `;
            previewContainer.appendChild(col);
          }
          reader.readAsDataURL(file);
        });

        // Update label
        const label = document.querySelector('.custom-file-label');
        if (label) {
          label.innerHTML = files.length > 0 ? `${files.length} file(s) selected` : 'Choose files';
        }
      });
    }
  });
</script>
