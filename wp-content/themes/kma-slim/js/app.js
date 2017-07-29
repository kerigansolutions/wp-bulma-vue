window.Vue = require('vue');

Vue.component('message', {

    props: ['title'],

    data(){
        return {
            isVisible: true
        };
    },

    template: `
    <article class="message" v-show="isVisible">
        <div class="message-header"> {{ title }} <button class="delete" @click="hideMessage"></button></div>
        <div class="message-body">
            <slot></slot>
        </div>
    </article>
    `,

    methods: {
        hideMessage(){
            this.isVisible = !this.isVisible;
        }
    }

});

Vue.component('modal', {

    template: `
    <div class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-content">
          <div class="box">
            <slot></slot>
          </div>
      </div>
      <button class="modal-close is-large" @click="$emit('close')"></button>
    </div>
    `,

});

Vue.component('tabs', {

    template: `
    <div>
        <div class="tabs is-toggle is-fullwidth">
          <ul>
            <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }" >
                <a :href="tab.href" @click="selectTab(tab)">{{ tab.name }}</a>
            </li>
          </ul>
        </div>
        
        <div class="tabs-details">
            <slot></slot>
        </div>
    </div>
    `,

    data(){
        return { tabs: [] };
    },

    created(){
        this.tabs = this.$children;
    },

    methods:{
        selectTab(selectedTab){
            this.tabs.forEach(tab => {
                tab.isActive = (tab.name == selectedTab.name);
            });
        }
    }

});

Vue.component('tab', {

    props: {
        name: { required: true },
        selected: { default: false }
    },

    data(){
        return {
            isActive: false
        };
    },

    computed: {
        href(){
            return '#' + this.name.toLowerCase().replace(/ /g, '-');
        }
    },

    mounted(){
        this.isActive = this.selected;
    },

    template: `
    <div class="tab-item" v-show="isActive">
        <slot></slot>
    </div>
    `

});


var app = new Vue({

    el: '#app',

    data: {
        isOpen: false,
        modalOpen: false,
        siteby: 'Site by KMA.',
        copyright: 'Kerigan Marketing Associates. All rights reserved.',
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

