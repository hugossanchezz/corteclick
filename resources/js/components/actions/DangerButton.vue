<script>
export default {
    name: "DangerButton",
    props: {
        label: {
            type: String, // Tipo de dato esperado
            default: "Danger Button", // Valor por defecto
        },
    },
};
</script>

<template>
    <button v-html="label" class="flex-center"></button>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

button {
    background-color: map-get($colores, "rojo");
    color: map-get($colores, "blanco");
    @include fuente("titulo_secundario");

    padding: 0.35rem 1rem;

    border: none;
    border-radius: 5px;
    gap: 5px;
    cursor: pointer;
    white-space: nowrap;
    transition: transform 0.2s ease;

    &:hover {
        transform: scale(1.02);
        box-shadow: 0 0 3px rgba(255, 0, 0, 0.75);
    }

    &:disabled {
        opacity: 0.5;
        cursor: auto;
    }
}
</style>
