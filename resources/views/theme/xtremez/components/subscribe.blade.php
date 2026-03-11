  <section class="newsletter-section mt-5">
    <div class="container text-center">
      <p class="subtitle">Get in touch with us</p>
      <h2 class="newsletter-title">Our Newsletter</h2>

      <form id="newsletter-form" class="newsletter-form ajax-form" action="{{ route('ajax.subscribe') }}" method="POST">
        <div id="subscriber_email-error" class="d-none"></div>
        <div class="newsletter-input d-flex align-items-center">
          <i class="bi bi-envelope icon"></i>
          <input type="email" name="email" style="opacity: 0;" class="d-none" step="-1">
          <input type="email" name="subscriber_email" class="form-control" placeholder="Your Email Address" required>
          <button type="submit" class="btn-submit" data-loading-text="spinner">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
              stroke="#000000" stroke-width="0.24000000000000005">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M12.2929 4.29289C12.6834 3.90237 13.3166 3.90237 13.7071 4.29289L20.7071 11.2929C21.0976 11.6834 21.0976 12.3166 20.7071 12.7071L13.7071 19.7071C13.3166 20.0976 12.6834 20.0976 12.2929 19.7071C11.9024 19.3166 11.9024 18.6834 12.2929 18.2929L17.5858 13H4C3.44772 13 3 12.5523 3 12C3 11.4477 3.44772 11 4 11H17.5858L12.2929 5.70711C11.9024 5.31658 11.9024 4.68342 12.2929 4.29289Z"
                  fill="#000000"></path>
              </g>
            </svg>
            {{-- <i class="bi bi-arrow-right"></i> --}}
            {{-- <i class="bi bi-a"></i> --}}
          </button>
        </div>
      </form>
    </div>
  </section>
