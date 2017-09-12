<template>
    <div class="modal is-active" :active="activeModal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <slot></slot>
            </div>
        </div>
        <button class="modal-close is-large" @click="$emit('close')"></button>
    </div>
</template>

<script>
    export default {
        props: {
            active: { default: false }
        },
        data() {
            return {
                showModal: this.active
            }
        },
        methods: {
            toggleModal(){
                this.showModal = !this.showModal;
                if(this.$parent.modalOpen !== ''){
                    this.$parent.modalOpen = ''
                }
            }
        },
        mounted() {
            console.log('Component mounted.');

            this.$parent.$on('toggleModal', function (modal) {
                this.activeModal = modal;
            });

        }
    }
</script>