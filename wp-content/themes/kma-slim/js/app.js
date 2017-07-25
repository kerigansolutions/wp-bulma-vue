window.Vue = require('vue');

Vue.component('message', {

    props: ['title', 'body'],

    data(){
        return {
            isVisible: true
        };
    },

    template: `
    <article class="message" v-show="isVisible">
        <div class="message-header"> {{ title }} <button class="delete" @click="$emit('close')"></button></div>
        <div class="message-body">
            <slot></slot>
        </div>
    </article>
    `,

});

Vue.component('modal', {

    template: `
    <div class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-content">
        <slot></slot>
      </div>
      <button class="modal-close is-large" @click="$emit('close')"></button>
    </div>
    `,

});

var app = new Vue({

    el: '#app',

    data: {
        isOpen: false,
        siteby: 'Site by KMA.',
        copyright: 'Kerigan Marketing Associates. All rights reserved.',
        isVisible: true,
        sliderSlides: [
            '/wp-content/themes/kma-slim/img/placeholder-1.jpg',
            '/wp-content/themes/kma-slim/img/placeholder-2.jpg',
            '/wp-content/themes/kma-slim/img/placeholder-3.jpg',
            '/wp-content/themes/kma-slim/img/placeholder-4.jpg',
            '/wp-content/themes/kma-slim/img/placeholder-5.jpg'
        ],
        currentImage: '/wp-content/themes/kma-slim/img/placeholder-1.jpg',
        counter: 0,
        paused: false
    },

    methods: {

        toggleMenu(){
            this.isOpen = !this.isOpen;
        },

        clickNext(){
            this.paused = true;
            this.nextSlide();
        },

        clickPrev(){
            this.paused = true;
            this.prevSlide();
        },

        nextSlide(){
            this.counter++
            if(this.counter == 4){ this.counter = 0 }
            this.currentImage = this.sliderSlides[this.counter];
        },

        prevSlide(){
            this.counter = this.counter - 1;
            if(this.counter == -1){ this.counter = 4 }
            this.currentImage = this.sliderSlides[this.counter];
        }

    },

    beforeUpdate() {

    },

    created() {

        this.currentImage = this.sliderSlides[0];

        setInterval(() => {
            if(this.paused == false) {
                this.nextSlide();
            }
        }, 6000)

    },

});

