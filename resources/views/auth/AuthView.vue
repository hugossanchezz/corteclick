<template>
    <div class="router__container flex-center">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "AuthView",
};
</script>
<style scoped lang="scss">
.router__container {
    width: 100%;
    height: 100%;
}
</style>
