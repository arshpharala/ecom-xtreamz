@extends('theme.xtremez.layouts.app')

@push('styles')
  <style>
    .contact-info-card {
      transition: all 0.3s ease;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .contact-info-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
      background: #fff !important;
      border-color: #000;
    }

    .contact-icon-box {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #000 0%, #333 100%) !important;
      color: #fff !important;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 16px;
      flex-shrink: 0;
      transition: transform 0.3s ease;
    }

    .contact-info-card:hover .contact-icon-box {
      transform: scale(1.1) rotate(5deg);
    }

    .form-control:focus {
      box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05) !important;
      background-color: #fff !important;
      border: 1px solid #000 !important;
    }

    .tracking-wider {
      letter-spacing: 2px;
    }

    .section-subtitle {
      color: #6c757d;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 600;
      display: block;
      margin-bottom: 0.5rem;
    }
  </style>
@endpush

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-white" title="Home">Home</a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page">Contact Us</li>
        </ol>
      </nav>
    </div>
  </section>
@endsection

@section('content')
  <section class="heading-section py-5">
    <div class="container text-center">
      <div class="heading-row">
        <span class="section-subtitle">Connect With Us</span>
        <h2 class="section-title fs-1 m-0 font-weight-bold">Contact Us</h2>
        <div class="mx-auto mt-3" style="width: 60px; height: 3px; background: #000;"></div>
      </div>
    </div>
  </section>
  <section class="py-5">
    <div class="container">
      <div class="row gy-5">
        <!-- Contact Info -->
        <div class="col-lg-5">
          <div class="contact-info-card h-100 p-4 p-md-5 bg-light" style="border-radius: 20px;">
            <h2 class="section-title mb-4">Get in Touch</h2>
            <p class="text-muted mb-5">Have a question or need a custom corporate gift solution? Our team is here to help
              you.</p>

            <div class="contact-item d-flex mb-4 align-items-center">
              <div class="contact-icon-box me-3 shadow-sm">
                <i class="bi bi-geo-alt fs-4"></i>
              </div>
              <div>
                <h5 class="mb-1 font-weight-bold">Office Address</h5>
                <p class="text-muted mb-0 small">
                  {{ setting('address', 'OFFICE NUMBER 10, 5TH FLOOR, AL MASSOUD BUILDING, FATHIMA BINT MUBARAK STREET - AL DANAH - ZONE 1 - ABU DHABI') }}
                </p>
              </div>
            </div>

            <div class="contact-item d-flex mb-4 align-items-center">
              <div class="contact-icon-box me-3 shadow-sm">
                <i class="bi bi-envelope fs-4"></i>
              </div>
              <div>
                <h5 class="mb-1 font-weight-bold">Email Us</h5>
                <a href="mailto:{{ setting('contact_email', 'xtremez.ads@gmail.com') }}"
                  class="text-muted text-decoration-none small hover-dark">
                  {{ setting('contact_email', 'xtremez.ads@gmail.com') }}
                </a>
              </div>
            </div>

            <div class="contact-item d-flex mb-4 align-items-center">
              <div class="contact-icon-box me-3 shadow-sm">
                <i class="bi bi-phone fs-4"></i>
              </div>
              <div>
                <h5 class="mb-1 font-weight-bold">Call Us</h5>
                <a href="tel:{{ setting('contact_phone', '0522621345') }}"
                  class="text-muted text-decoration-none small hover-dark">
                  {{ setting('contact_phone', '+971 52 262 1345') }}
                </a>
              </div>
            </div>

            <div class="mt-5">
              <h5 class="mb-3">Follow Us</h5>
              <div class="social-icons d-flex gap-3">
                @if (setting('facebook'))
                  <a href="{{ setting('facebook') }}" class="btn btn-outline-dark rounded-circle px-2 py-1"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i
                      class="bi bi-facebook fs-6"></i></a>
                @endif
                @if (setting('instagram'))
                  <a href="{{ setting('instagram') }}" class="btn btn-outline-dark rounded-circle px-2 py-1"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i
                      class="bi bi-instagram fs-6"></i></a>
                @endif
                @if (setting('twitter'))
                  <a href="{{ setting('twitter') }}" class="btn btn-outline-dark rounded-circle px-2 py-1"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; padding: 0 !important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-twitter-x" viewBox="0 0 16 16">
                      <path
                        d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                    </svg>
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
          <div class="contact-form-container p-4 p-md-5 bg-white border shadow-sm" style="border-radius: 20px;">
            <h3 class="mb-4">Send us a Message</h3>

            @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show mb-4 border-0" role="alert"
                style="border-radius: 12px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form action="{{ route('contact-us.submit') }}" method="POST" id="contactForm">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control border-0 bg-light" id="name"
                      placeholder="John Doe" required style="border-radius: 12px;">
                    <label for="name">Your Name</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control border-0 bg-light" id="email"
                      placeholder="name@example.com" required style="border-radius: 12px;">
                    <label for="email">Email Address</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input type="text" name="phone" class="form-control border-0 bg-light" id="phone"
                      placeholder="+971 00 000 0000" style="border-radius: 12px;">
                    <label for="phone">Phone Number</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select name="subject_select" class="form-select border-0 bg-light" id="subject_select" required
                      style="border-radius: 12px; height: 58px;">
                      <option value="" selected disabled>Select Subject</option>
                      @foreach ($subjects as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                      @endforeach
                      <option value="Other">Other</option>
                    </select>
                    <label for="subject_select">Subject</label>
                  </div>
                </div>

                <div class="col-md-12 d-none" id="other_subject_container">
                  <div class="form-floating mb-3">
                    <input type="text" name="other_subject" class="form-control border-0 bg-light"
                      id="other_subject" placeholder="Enter Subject" style="border-radius: 12px;">
                    <label for="other_subject">Enter Subject</label>
                  </div>
                </div>

                <input type="hidden" name="subject" id="final_subject">

                <div class="col-12">
                  <div class="form-floating mb-4">
                    <textarea name="message" class="form-control border-0 bg-light" placeholder="Leave a message here" id="message"
                      style="height: 150px; border-radius: 12px;" required></textarea>
                    <label for="message">Your Message</label>
                  </div>
                </div>
                <div class="col-12">
                  <button type="submit"
                    class="btn btn-dark btn-lg w-100 py-3 rounded-pill text-uppercase fs-6 tracking-wider">
                    Send Message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Google Map Section -->
  @if (setting('show_office_map', false) && !empty(setting('office_map_embed')))
    <section class="map-section mt-5 mb-5">
      <div class="container overflow-hidden" style="border-radius: 20px;">
        <div class="ratio ratio-21x9 shadow-sm">
          {!! setting('office_map_embed') !!}
        </div>
      </div>
    </section>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const subjectSelect = document.getElementById('subject_select');
      const otherContainer = document.getElementById('other_subject_container');
      const otherInput = document.getElementById('other_subject');
      const finalSubject = document.getElementById('final_subject');
      const contactForm = document.getElementById('contactForm');

      subjectSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
          otherContainer.classList.remove('d-none');
          otherInput.setAttribute('required', 'required');
        } else {
          otherContainer.classList.add('d-none');
          otherInput.removeAttribute('required');
          finalSubject.value = this.value;
        }
      });

      contactForm.addEventListener('submit', function(e) {
        if (subjectSelect.value === 'Other') {
          finalSubject.value = otherInput.value;
        } else {
          finalSubject.value = subjectSelect.value;
        }
      });
    });
  </script>
@endsection
