<template>
    <div class="loader-container">
        <vue-loaders-line-scale-pulse-out color="green"/>
    </div>
</template>

<script>
export default {
    name: "Loader"
}
</script>

<style scoped>
.loader-container {
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
</style>
