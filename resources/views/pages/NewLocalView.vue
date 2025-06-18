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
    <main>
        <section>
            <FormLocal />
        </section>
    </main>
</template>

<style scoped lang="scss">

main {
    width: 100%;
    height: 100%;
    section {
        width: 40%;
        margin: 0 auto;
    }
}

</style>