import './bootstrap.js';
import './styles/app.css';

import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import './styles/admin.css';


console.log("This log comes from assets/app.js - welcome to Webpack Encore! 🎉");

document.addEventListener('DOMContentLoaded', () => {
    // carrousel
    new Swiper('.swiper', {
        modules: [Navigation, Autoplay],
        spaceBetween: 20,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: {slidesPerView: 2},
            768: {slidesPerView: 3},
            1024: {slidesPerView: 6},
        },
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({behavior: 'smooth'});
            }
        });
    });

    // Carrousel hero & footer
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