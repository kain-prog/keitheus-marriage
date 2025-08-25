import './bootstrap.js';
import './styles/app.css';

import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

function initSwiper() {
    if (document.querySelector('.swiper')) {
        new Swiper('.swiper', {

            loop: true,
            effect: "fade",
            speed:1000,
            autoplay: {
                delay: 3000
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                bulletClass: 'custom-bullet',
                bulletActiveClass: 'custom-bullet-active',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            scrollbar: {
                el: '.swiper-scrollbar',
            },
        });
    }
}

document.addEventListener('turbo:load', () => {
    initSwiper();
    initCountdown();
});

function initCountdown() {
    let dest = new Date("nov 08, 2025 10:00:00").getTime();
    let x = setInterval(function () {
        let now = new Date().getTime();
        let diff = dest - now;

        if (diff <= 0) {
            clearInterval(x);
            return;
        }

        let days = Math.floor(diff / (1000 * 60 * 60 * 24));
        let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000);

        days = days < 10 ? `0${days}` : days;
        hours = hours < 10 ? `0${hours}` : hours;
        minutes = minutes < 10 ? `0${minutes}` : minutes;
        seconds = seconds < 10 ? `0${seconds}` : seconds;

        let countdownElements = document.getElementsByClassName("countdown-element");

        for (let i = 0; i < countdownElements.length; i++) {
            let className = countdownElements[i].classList[1];
            switch (className) {
                case "days":
                    countdownElements[i].innerHTML = days;
                    break;
                case "hours":
                    countdownElements[i].innerHTML = hours;
                    break;
                case "minutes":
                    countdownElements[i].innerHTML = minutes;
                    break;
                case "seconds":
                    countdownElements[i].innerHTML = seconds;
                    break;
            }
        }
    }, 1000);
}
