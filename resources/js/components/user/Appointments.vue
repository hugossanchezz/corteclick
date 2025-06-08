<script>
import { ref, computed, onMounted, watch } from "vue";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: "Appointments",

    components: {
        ModalConfirm
    },
    setup() {
        const userId = ref(null);
        const citas = ref([]);
        const estadoFiltro = ref("TODAS");
        const loading = ref(true);
        const error = ref(null);
        const showModal = ref(false);
        const citaSeleccionada = ref(null);
        const accionModal = ref(null);

        const fetchNombreServicio = async (id) => {
            try {
                const res = await fetch(`/api/services/${id}/name`);
                if (!res.ok) return null;
                const json = await res.json();
                return json.nombre || null;
            } catch {
                return null;
            }
        };

        const fetchNombrePeluqueria = async (id) => {
            try {
                const res = await fetch(`/api/local/${id}/name`);
                if (!res.ok) return null;
                return await res.text();
            } catch {
                return null;
            }
        };


        const fetchCitas = async (idUsuario) => {
            try {
                let url = `/api/appointments/user/${idUsuario}`;
                if (estadoFiltro.value !== "TODAS") {
                    url += `?estado=${estadoFiltro.value}`;
                }

                const res = await fetch(url);
                if (!res.ok) throw new Error("No se pudieron obtener las citas");

                const data = await res.json();

                const citasConNombres = await Promise.all(
                    data.map(async (cita) => {
                        const [nombreServicio, nombrePeluqueria] = await Promise.all([
                            fetchNombreServicio(cita.id_servicio),
                            fetchNombrePeluqueria(cita.id_peluqueria),
                        ]);
                        return {
                            ...cita,
                            nombre_servicio: nombreServicio,
                            nombre_peluqueria: nombrePeluqueria,
                        };
                    })
                );

                citas.value = citasConNombres;
            } catch (err) {
                error.value = "Error al cargar las citas del usuario.";
                console.error(err);
            } finally {
                loading.value = false;
            }
        };

        const citasFiltradas = computed(() => {
            if (estadoFiltro.value === "TODAS") {
                return citas.value;
            }
            return citas.value.filter(c => c.estado === estadoFiltro.value);
        });

        const abrirModalAccionCita = (cita, accion = "cancel") => {
            citaSeleccionada.value = cita;
            accionModal.value = accion;
            showModal.value = true;
        };

        const confirmarAccion = async () => {
            try {
                const idCita = citaSeleccionada.value.id;
                let url = `/api/appointments/${idCita}`;
                let method = "PATCH";

                if (accionModal.value === "cancel") {
                    url += "/cancel";
                } else if (accionModal.value === "delete") {
                    url += "/delete";
                    method = "DELETE";
                }

                const res = await fetch(url, { method });

                if (!res.ok) {
                    throw new Error(`Error al ${accionModal.value === "cancel" ? "cancelar" : "eliminar"} la cita`);
                }

                if (userId.value) await fetchCitas(userId.value);
                showModal.value = false;
            } catch (error) {
                console.error("Error al procesar la cita:", error);
                showModal.value = false;
            }
        };

        watch(estadoFiltro, () => {
            if (userId.value) {
                fetchCitas(userId.value);
            }
        });

        onMounted(() => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                const user = JSON.parse(storedUser);
                userId.value = user.id;
                fetchCitas(userId.value);
            } else {
                error.value = "No se encontró el usuario en sesión.";
                loading.value = false;
            }
        });

        return {
            citas,
            loading,
            error,
            citasFiltradas,
            estadoFiltro,
            showModal,
            abrirModalAccionCita,
            confirmarAccion,
            citaSeleccionada,
            accionModal
        };
    },
};
</script>

<template>
    <div class="appointments__container">
        <div class="filter-section">
            <label for="estado">Filtrar por estado:</label>
            <select id="estado" v-model="estadoFiltro">
                <option value="TODAS">Todas</option>
                <option value="CANCELADA">Canceladas</option>
                <option value="TERMINADA">Terminadas</option>
            </select>
        </div>

        <h2>Mis Citas</h2>

        <div v-if="loading" class="mg-tb-4 loading w-100 flex-center">Cargando citas...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else-if="citasFiltradas.length === 0">
            <p v-if="estadoFiltro === 'CANCELADA'">
                No hay solicitudes con el estado "CANCELADA".
            </p>
            <p v-else-if="estadoFiltro === 'TERMINADA'">
                No hay solicitudes con el estado "TERMINADA".
            </p>
            <p v-else>
                No tienes citas registradas.
            </p>
        </div>

        <table v-else>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Servicio</th>
                    <th>Peluquería</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cita, index) in citasFiltradas" :key="index">
                    <td>{{ cita.fecha }}</td>
                    <td>{{ cita.hora_inicio }}</td>
                    <td>{{ cita.hora_fin }}</td>
                    <td>{{ cita.nombre_servicio }}</td>
                    <td>
                        <router-link :to="`/locals/${cita.id_peluqueria}`" class="peluqueria-link">
                            {{ cita.nombre_peluqueria }}
                        </router-link>
                    </td>
                    <td class="flex">
                        <div class="flex-center">
                            <button v-if="cita.estado === 'CONFIRMADA'" @click="abrirModalAccionCita(cita)"
                                class="btn btn-edit flex">
                                Cancelar cita
                                <img src="/img/utils/cancel_white.svg" alt="Cancelar cita">
                            </button>
                            <span v-else>{{ cita.estado }}</span>
                        </div>
                        <button class="btn btn-cancel" @click="abrirModalAccionCita(cita, 'delete')">
                            <img src="/img/utils/delete_white.svg" alt="Eliminar cita">
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <ModalConfirm :show="showModal"
        :message="accionModal === 'cancel' ? '¿Estás seguro de que deseas cancelar esta cita?' : '¿Estás seguro de que deseas eliminar esta cita?'"
        confirmText="Aceptar" cancelText="Cancelar" :showCancel="true" @confirm="confirmarAccion"
        @cancel="showModal = false" @close="showModal = false" />


</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.appointments__container {
    margin: 0 auto;
    width: 85%;

    padding: 20px;

    .filter-section {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;

        select {
            padding: 8px;
            border: 1px solid map-get($colores, "gris_claro");
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;

            &:focus {
                outline: none;
                border-color: map-get($colores, "naranja");
            }
        }
    }

    h2 {
        margin-bottom: 1rem;
    }

    table {

        a {
            color: map-get($colores, "azul_oscuro");
            text-decoration: none;

            &:hover {
                color: map-get($colores, "naranja");
                text-decoration: underline;
            }
        }

        td.flex {
            justify-content: space-between;

            div {
                width: 100%;

                span {
                    font-weight: bold;
                }
            }
        }
    }
}
</style>