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
    background: rgba(66, 66, 66, 0.4);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);

    color: map-get($colores, "blanco");
    @include fuente("titulo_secundario");

    padding: 0.50rem 1.15rem ;
    margin: 0 0.5rem;

    border: 2px solid map-get($colores, "gris_claro");
    border-radius: 5px;
    cursor: pointer;

    transition: transform 0.2s ease;

    &:hover {
        transform: scale(1.05);
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }
}
</style>
