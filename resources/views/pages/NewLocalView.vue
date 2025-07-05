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

$section-breakpoints: (
    1320px: 45%,
    1220px: 50%,
    950px: 60%,
    800px: 70%,
    700px: 80%,
    670px: 90%
);

@each $screen, $width in $section-breakpoints {
    @media (max-width: #{$screen}) {
        main {
            section {
                width: $width;
            }
        }
    }
}

// Margen superior específico para tablet
@media (max-width: 1024px) {
    main {
        section {
            margin-top: 1.5rem;
        }
    }
}

</style>