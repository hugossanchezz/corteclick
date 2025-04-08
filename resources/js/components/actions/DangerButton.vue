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
    <button>{{ label }}</button>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

button {
    background-color: map-get($colores, "rojo");
    color: map-get($colores, "blanco");
    @include fuente("titulo_secundario");

    padding: 0.35rem 1rem ;
    margin: 0.5rem;

    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>
