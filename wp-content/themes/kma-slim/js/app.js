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
        isVisible: true
    },

    methods: {
        toggleMenu(){
            this.isOpen = !this.isOpen;
        }
    },

});

