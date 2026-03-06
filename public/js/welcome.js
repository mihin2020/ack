(function() {
  'use strict';
  var currentSlideIndex = 0;
  var slides = document.querySelectorAll('.slide');
  var dots = document.querySelectorAll('.slider-dot');
  var totalSlides = slides.length;

  function showSlide(index) {
    if (!slides.length) return;
    slides.forEach(function(s) { s.classList.remove('active'); });
    dots.forEach(function(d) { d.classList.remove('active'); });
    currentSlideIndex = (index + totalSlides) % totalSlides;
    slides[currentSlideIndex].classList.add('active');
    if (dots[currentSlideIndex]) dots[currentSlideIndex].classList.add('active');
  }

  window.currentSlide = function(index) { showSlide(index); };

  function nextSlide() { showSlide(currentSlideIndex + 1); }
  if (totalSlides > 0) setInterval(nextSlide, 5000);

  var navbar = document.getElementById('navbar');
  if (navbar) {
    function onScroll() {
      navbar.classList.toggle('nav-sticky', window.scrollY > 100);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
      var href = this.getAttribute('href');
      if (href === '#') return;
      e.preventDefault();
      var target = document.querySelector(href);
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  var revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-stagger');
  if (revealEls.length && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) entry.target.classList.add('visible');
      });
    }, { rootMargin: '0px 0px -50px 0px', threshold: 0 });
    revealEls.forEach(function(el) { observer.observe(el); });
  }
})();
