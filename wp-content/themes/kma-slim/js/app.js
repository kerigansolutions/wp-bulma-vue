window.Vue = require('vue');

Vue.component('slider', {

    template: `
    <div class="slider">
        <div class="slider-left icon is-large" @click="clickPrev" >
            <i class="fa fa-angle-left is-large" aria-hidden="true"></i>
        </div>
        
        <div class="slides">
            <slot></slot>
        </div>
        
        <div class="slider-right icon is-large" @click="clickNext" >
            <i class="fa fa-angle-right is-large" aria-hidden="true"></i>
        </div>
    </div>
    `,

    data(){
        return {
            slides: [],
            activeSlide: 0,
            paused: false
        };
    },

    created(){

        this.slides = this.$children
        setInterval(() => { if(this.paused == false){ this.nextSlide() } }, 6000)

    },

    methods: {

        nextSlide(){
            this.slides[this.activeSlide]._data.isActive = false
            if(this.activeSlide == this.slides.length-1){
                this.activeSlide = -1
            }
            this.activeSlide++
            this.slides[this.activeSlide]._data.isActive = true
        },

        prevSlide(){
            this.slides[this.activeSlide]._data.isActive = false
            this.activeSlide--
            if(this.activeSlide == -1){
                this.activeSlide = this.slides.length-1
            }
            this.slides[this.activeSlide]._data.isActive = true
        },

        clickNext(){
            this.nextSlide()
            this.togglePause()
        },

        clickPrev(){
            this.prevSlide()
            this.togglePause()
        },

        togglePause(){
            this.paused = !this.paused;
        }

    }

});

Vue.component('slide', {

    props: {
        image: { required: true },
        active: { default: false }
    },

    template: `
    <div class="slide full-bg" :style="{ 'background-image': 'url(' + image + ')' }" :class="{ 'is-active': this.isActive }">
        <slot></slot>
    </div>
    `,

    data(){
        return {
            isActive: false
        };
    },

    created(){
        if(this.active == true){ this.isActive = true }
    }

});

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
    },

    methods: {

        toggleMenu(){
            this.isOpen = !this.isOpen;
        }

    }

});

