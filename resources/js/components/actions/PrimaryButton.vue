<script>
export default {
    name: "PrimaryButton",
    props: {
        label: {
            type: String, // Tipo de dato esperado
            default: "Primary Button", // Valor por defecto
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
    background-color: map-get($colores, "naranja");
    color: map-get($colores, "blanco");
    @include fuente("titulo_secundario");

    padding: 0.65rem 1.25rem ;
    width: fit-content;

    border: none;
    border-radius: 5px;
    cursor: pointer;

    transition: transform 0.2s ease;

    &:hover {
        transform: scale(1.02);
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

}
</style>
