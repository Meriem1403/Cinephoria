import './bootstrap.js';
import './styles/app.css';

import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import './styles/admin.css';

console.log("This log comes from assets/app.js - welcome to Webpack Encore! 🎉");

document.addEventListener('turbo:load', () => {
    // ✅ Initialisation de TOUS les carrousels visibles
    document.querySelectorAll('.swiper').forEach(swiperEl => {
        const nextBtn = swiperEl.querySelector('.swiper-button-next');
        const prevBtn = swiperEl.querySelector('.swiper-button-prev');
        const slides = swiperEl.querySelectorAll('.swiper-slide');

        // Détection du nombre de slides visibles
        const slidesPerView = window.innerWidth >= 1024 ? 5 :
            window.innerWidth >= 768 ? 3 : 2;

        // ✅ Empêche le blanc si pas assez de slides
        const enableLoop = slides.length > slidesPerView;

        new Swiper(swiperEl, {
            modules: [Navigation, Autoplay],
            spaceBetween: 5,
            loop: enableLoop,
            navigation: {
                nextEl: nextBtn,
                prevEl: prevBtn,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 3 },
                768: { slidesPerView: 4 },
                1024: { slidesPerView: 6 },
            },
        });
    });

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Carrousel Hero & Footer
    const heroCarousel = document.getElementById('hero-carousel');
    const footerCarousel = document.getElementById('footer-carousel');
    const totalSlides = window.heroImages?.length || 0;
    let currentIndex = 0;

    if (heroCarousel && footerCarousel && totalSlides > 1) {
        setInterval(() => {
            currentIndex = (currentIndex + 1) % totalSlides;
            heroCarousel.style.transform = `translateX(-${currentIndex * 100}%)`;
            footerCarousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        }, 15000);
    }
});
