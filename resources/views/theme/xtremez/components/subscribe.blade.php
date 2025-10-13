  <section class="newsletter-section mt-5">
    <div class="container text-center">
      <p class="subtitle">Get in touch with us</p>
      <h2 class="newsletter-title">Our Newsletter</h2>

      <form id="newsletter-form" class="newsletter-form ajax-form" action="{{ route('ajax.subscribe') }}"
        method="POST">
        <div id="subscriber_email-error" class="d-none"></div>
        <div class="newsletter-input d-flex align-items-center">
          <i class="bi bi-envelope icon"></i>
          <input type="email" name="email" style="opacity: 0;" class="d-none" step="-1">
          <input type="email" name="subscriber_email" class="form-control" placeholder="Your Email Address"
            required>
          <button type="submit" class="btn-submit" data-loading-text="spinner">
            <i class="bi bi-arrow-right"></i>
            {{-- <i class="bi bi-a"></i> --}}
          </button>
        </div>
      </form>
    </div>
  </section>
