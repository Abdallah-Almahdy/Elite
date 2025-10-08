<footer dir="rtl" class="bg-dark text-white pt-5">
  <div class="container">

    <!-- Top Row -->
    <div class="row text-center text-md-end align-items-start gy-4">

      <!-- Brand -->
      <div class="col-12 col-md-3">
        <a href="{{ route('home') }}" class="text-decoration-none text-white d-block mb-3">
          <img src="{{ asset('admin/photo/supermarket-shop-logo-vector.png') }}" alt="logo"
               class="mb-2" width="100">
          <h5 class="fw-bold text-warning">عربي ماركت</h5>
        </a>
        <p class="text-light small mb-0">جودة وثقة منذ 1943</p>
      </div>

      <!-- Explore -->
      <div class="col-6 col-md-2">
        <h6 class="fw-bold text-warning mb-3">استكشف</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">خصومات</a></li>
          <li><a href="#" class="footer-link">عروض الصيف</a></li>
          <li><a href="#" class="footer-link">هدايا</a></li>
          <li><a href="#" class="footer-link">النشرة الأسبوعية</a></li>
        </ul>
      </div>

      <!-- About -->
      <div class="col-6 col-md-2">
        <h6 class="fw-bold text-warning mb-3">حولنا</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">من نحن؟</a></li>
          <li><a href="#" class="footer-link">خدمات التوصيل</a></li>
          <li><a href="#" class="footer-link">سياسة الاسترجاع</a></li>
          <li><a href="#" class="footer-link">الدعم الفني</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-12 col-md-3">
        <h6 class="fw-bold text-warning mb-3">معلومات التواصل</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><i class="fas fa-map-marker-alt ms-2"></i> محافظة السويس، مصر</li>
          <li class="mb-2"><i class="fas fa-envelope ms-2"></i> info@arabymarket.com</li>
          <li class="mb-2"><i class="fas fa-phone ms-2"></i> +20 123 456 7890</li>
        </ul>
      </div>

      <!-- Social -->
      <div class="col-12 col-md-2 text-center text-md-end">
        <h6 class="fw-bold text-warning mb-3 position-relative d-inline-block">
          تواصل معنا
          <span class="underline"></span>
        </h6>

        <p class="small text-light mb-3">
          تابعنا على وسائل التواصل الاجتماعي لتصلك أحدث العروض والتخفيضات.
        </p>

        <div class="d-flex justify-content-center justify-content-md-end gap-3 mt-3 flex-wrap">
          <a href="https://facebook.com" target="_blank" class="social-link">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://instagram.com" target="_blank" class="social-link">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://twitter.com" target="_blank" class="social-link">
            <i class="fab fa-x-twitter"></i>
          </a>
          <a href="https://wa.me/201234567890" target="_blank" class="social-link">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>

    </div>

    <!-- Divider -->
    <hr class="border-secondary mt-4">

    <!-- Bottom -->
    <div class="text-center pb-3 small text-light">
      © {{ date('Y') }} جميع الحقوق محفوظة | <span class="text-warning fw-bold">AGAS</span>
    </div>

  </div>
</footer>
