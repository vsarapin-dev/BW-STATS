<template>
    <div class="loader">
        <div class="loader-inner"></div>
    </div>
</template>

<script>
import anime from 'animejs';

export default {
    name: "Loader",
    mounted() {
        anime({
            targets: '.loader-inner',
            rotate: '1turn',
            easing: 'easeInOutQuad',
            duration: 2000,
            loop: true
        });
    },
}
</script>

<style scoped>
.loader {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;

    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background-color: rgba(0, 0, 0, 0.5);
}

.loader-inner {
    position: relative;
    width: 50px;
    height: 50px;
    background-color: #FF00FF;
    border-radius: 50%;
    transform: scale(0);
    animation: loader 2s infinite;
}

@keyframes loader {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1);
        background-color: #00FFFF;
        box-shadow: 0 0 10px #00FFFF;
    }
    100% {
        transform: scale(0);
    }
}
</style>
