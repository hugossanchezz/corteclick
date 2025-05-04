<template>
    <div>
        <h1>Lista de Peluquerías</h1>
        <div v-if="loading">Cargando peluquerías...</div>
        <div v-else-if="peluquerias.length === 0">
            No hay peluquerías disponibles.
        </div>
        <div v-else class="peluquerias-grid">
            <div
                v-for="peluqueria in peluquerias"
                :key="peluqueria.id"
                class="peluqueria-card"
            >
                <h2>{{ peluqueria.nombre }}</h2>
                <p><strong>Dirección:</strong> {{ peluqueria.direccion }}</p>
                <p><strong>Teléfono:</strong> {{ peluqueria.telefono }}</p>
                <p>
                    <strong>Descripción:</strong> {{ peluqueria.descripcion }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
    name: "LocalsView",
    setup() {
        const peluquerias = ref([]);
        const loading = ref(true);

        onMounted(() => {
            fetch("/api/locals")
                .then((response) => response.json())
                .then((data) => {
                    peluquerias.value = data;
                    loading.value = false;
                })
                .catch((error) => {
                    console.error("Error al obtener las peluquerías:", error);
                    loading.value = false;
                });
        });

        return {
            peluquerias,
            loading,
        };
    },
};
</script>

<style scoped lang="scss">
.peluquerias-grid {
    display: grid;
    grid-template-columns: repeat(
        auto-fill,
        minmax(300px, 1fr)
    );
    gap: 20px;
    margin-top: 20px;
}

.peluqueria-card {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    strong {
        font-weight: bold;
    }
}

@media (max-width: 768px) {
    .peluquerias-grid {
        grid-template-columns: 1fr; /* Una columna en pantallas pequeñas */
    }

    .peluqueria-card {
        margin-bottom: 15px;
    }
}
</style>
