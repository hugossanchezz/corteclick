<script>
import SecondaryButton from "@/js/components/actions/SecondaryButton.vue";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import DangerButton from "@/js/components/actions/DangerButton.vue";

export default {
    name: "LoginView",

    components: {
        PrimaryButton,
        SecondaryButton,
        DangerButton,
    },
    methods: {
        clickLogin() {
            console.log("Ir a login");
        },
        clickRegister() {
            console.log("Ir a register");
        },
    }
};
</script>

<template>
    landing
    <br />

    iniciar sesion | registrarse

    <br />

    <PrimaryButton label="Iniciar Sesión" @click="clickLogin" />
    <PrimaryButton label="Registrarme" @click="clickRegister" />

    <SecondaryButton label="Secundario"/>

    <DangerButton label="Danger"/>

    <br />
    svgs
    <br />
    que ofrecemos
    <br />
    como funcionamos
</template>

<style scoped lang="scss">

</style>
