<template>
    <div class="slide full-bg" :style="{ 'background-image': 'url(' + image + ')' }" :class="{ 'is-active': this.isActive }">
        <slot></slot>
    </div>
</template>

<script>
    export default {

        props: {
            image: { required: true },
            active: { default: false }
        },

        data(){
            return {
                isActive: false
            };
        },

        created(){
            if(this.active == true){ this.isActive = true }
        }

    }
</script>