import './bootstrap.js';
import './styles/app.css';

import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import './styles/admin.css';

console.log("This log comes from assets/app.js - welcome to Webpack Encore! 🎉");

function initCarousels() {
    document.querySelectorAll('.swiper-movies, .swiper').forEach(swiperEl => {
        if (swiperEl.swiper) {
            swiperEl.swiper.destroy(true, true);
        }
        const nextBtn = swiperEl.querySelector('.swiper-button-next');
        const prevBtn = swiperEl.querySelector('.swiper-button-prev');
        const slides = swiperEl.querySelectorAll('.swiper-slide');

        const slidesPerView = window.innerWidth >= 1024 ? 5 :
            window.innerWidth >= 768 ? 3 : 2;
        const enableLoop = slides.length > slidesPerView;

        new Swiper(swiperEl, {
            modules: [Navigation, Autoplay],
            spaceBetween: 24,
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
                0:   { slidesPerView: 2, spaceBetween: 24 },
                640: { slidesPerView: 3, spaceBetween: 24 },
                768: { slidesPerView: 4, spaceBetween: 28 },
                1024: { slidesPerView: 5, spaceBetween: 32 },
                1280: { slidesPerView: 6, spaceBetween: 32 },
            },
        });
    });
}

function initPage() {
    initCarousels();

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

    // Carrousel Hero & Footer (un seul interval même si initPage est appelé deux fois)
    if (window.heroIntervalId) {
        clearInterval(window.heroIntervalId);
    }
    const heroCarousel = document.getElementById('hero-carousel');
    const footerCarousel = document.getElementById('footer-carousel');
    const totalSlides = window.heroImages?.length || 0;
    let currentIndex = 0;

    if (heroCarousel && footerCarousel && totalSlides > 1) {
        window.heroIntervalId = setInterval(() => {
            currentIndex = (currentIndex + 1) % totalSlides;
            heroCarousel.style.transform = `translateX(-${currentIndex * 100}%)`;
            footerCarousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        }, 5000);
    }
}

function runWhenReady() {
    requestAnimationFrame(() => {
        initPage();
    });
}

document.addEventListener('turbo:load', runWhenReady);
document.addEventListener('DOMContentLoaded', runWhenReady);

// First load: if script runs after DOM is already ready (e.g. at end of body), init immediately
if (document.readyState !== 'loading') {
    runWhenReady();
}

document.addEventListener('turbo:before-cache', () => {
    document.querySelectorAll('.swiper-movies, .swiper').forEach(el => {
        if (el.swiper) {
            el.swiper.destroy(true, true);
        }
    });
});