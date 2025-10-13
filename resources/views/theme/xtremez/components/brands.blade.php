<section class="heading-section py-5">
  <div class="container">
    <div class="heading-row  animate-on-scroll" data-animate="fade-down">
      <h2 class="section-title fs-1 text-center m-0">Our
        Brands</h2>
    </div>
  </div>
</section>

<section class="our-brands-section py-5">
  <div class="container">
    <div class="slider pb-5">
      @for ($i = 0; $i < 2; $i++)
        <div class="logos">
          @foreach ($brands as $brand)
            <img src="{{ asset('storage/' . $brand->logo) }}" class="brand-logo" alt="{{ $brand->name }}" />
          @endforeach
        </div>
      @endfor
    </div>
  </div>
</section>
