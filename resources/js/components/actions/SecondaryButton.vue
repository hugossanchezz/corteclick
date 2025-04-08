<script>
export default {
    name: "SecondaryButton",
    props: {
        label: {
            type: String, // Tipo de dato esperado
            default: "Secondary Button", // Valor por defecto
        },
    },
};
</script>

<template>
    <button>{{ label }}</button>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

button {
    background-color: map-get($colores, "blanco");
    color: map-get($colores, "azul_oscuro");
    @include fuente("titulo_secundario");

    padding: 0.50rem 1.15rem ;
    margin: 0.5rem;

    border: 0.5px solid map-get($colores, "gris_claro");
    border-radius: 5px;
    cursor: pointer;
}
</style>
