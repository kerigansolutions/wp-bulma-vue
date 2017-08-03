window.Vue = require('vue');

import message from './components/message.vue';
import modal from './components/modal.vue';
import tabs from './components/tabs.vue';
import tab from './components/tab.vue';
import slider from './components/slider.vue';
import slide from './components/slide.vue';

var app = new Vue({

    el: '#app',

    components: {
        message,
        modal,
        tabs,
        tab,
        slider,
        slide
    },

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

