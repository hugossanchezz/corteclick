<script>
import FormLocal from "@/js/components/utils/FormLocal.vue";
export default {
    name: "NewLocalView",
    components: { FormLocal },
    methods: {
        guardarNegocio(negocio) {
            console.log(negocio);
        }
    }
};
</script>

<template>
    <section>

        <FormLocal @guardar="guardarNegocio" />
    </section>
</template>

<style scoped lang="scss">
section {
    width: 40%;
    margin: 0 auto;
}

</style>