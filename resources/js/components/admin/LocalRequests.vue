<script>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: "LocalRequests",
    components: {
        ModalConfirm,
    },
    setup() {
        const solicitudes = ref([]);
        const estadoSeleccionado = ref("PENDIENTE");
        const estados = ref(["PENDIENTE", "APROBADA", "RECHAZADA"]);

        const showModal = ref(false);
        const modalMessage = ref("");
        const modalAction = ref(null);
        const modalSolicitudId = ref(null);

        const localidadNames = ref({});

        const cargarSolicitudes = async (estado) => {
            try {
                const response = await axios.get(`/api/admin/requests?estado=${estado}`)
                solicitudes.value = response.data;
                await cargarNombresLocalidades();
            } catch (error) {
                console.error("Error fetching local requests:", error);
                solicitudes.value = [];
                modalMessage.value = "Error al cargar las solicitudes. Por favor, intenta de nuevo más tarde.";
                modalAction.value = null;
                showModal.value = true;
            }
        };

        const cargarNombresLocalidades = async () => {
            const uniqueLocalityIds = [...new Set(solicitudes.value.map(s => s.localidad))];
            for (const id of uniqueLocalityIds) {
                if (!localidadNames.value[id]) {
                    try {
                        const response = await axios.get(`/api/localities/${id}/name`, {
                            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                        });
                        localidadNames.value[id] = response.data;
                    } catch (error) {
                        console.error(`Error fetching locality name for ID ${id}:`, error);
                        localidadNames.value[id] = "Desconocido";
                    }
                }
            }
        };

        onMounted(() => {
            cargarSolicitudes(estadoSeleccionado.value);
        });

        watch(estadoSeleccionado, (nuevoEstado) => {
            cargarSolicitudes(nuevoEstado);
        });

        const abrirModal = (message, action, solicitudId) => {
            modalMessage.value = message;
            modalAction.value = action;
            modalSolicitudId.value = solicitudId;
            showModal.value = true;
        };

        const ejecutarAccion = async () => {
            const action = modalAction.value;
            const solicitudId = modalSolicitudId.value;
            const estadoMap = {
                aprobar: 'APROBADA',
                denegar: 'RECHAZADA',
                devolverAPendiente: 'PENDIENTE',
            };
            const mensajesExito = {
                aprobar: "Solicitud APROBADA exitosamente y peluquería creada.",
                denegar: "Solicitud DENEGADA exitosamente.",
                devolverAPendiente: "Solicitud devuelta a PENDIENTE exitosamente y peluquería eliminada si existía.",
            };

            try {
                const solicitud = solicitudes.value.find(s => s.id === solicitudId);
                if (!solicitud) {
                    throw new Error("Solicitud no encontrada");
                }

                if (action === 'aprobar') {
                    // let normalizedTipo = solicitud.tipo;
                    // if (normalizedTipo) {
                    //     normalizedTipo = normalizedTipo.toLowerCase();
                    //     if (!['peluqueria', 'barberia', 'estetica'].includes(normalizedTipo)) {
                    //         throw new Error("El tipo de solicitud no es válido. Debe ser 'peluqueria', 'barberia' o 'estetica'.");
                    //     }
                    // } else {
                    //     throw new Error("El campo 'tipo' está vacío o no definido.");
                    // }

                    try {
                        const response = await axios.post('/api/new-local', {
                            nombre: solicitud.nombre,
                            descripcion: solicitud.descripcion,
                            direccion: solicitud.direccion,
                            localidad: solicitud.localidad,
                            email: solicitud.email,
                            telefono: solicitud.telefono,
                            tipo: solicitud.tipo,
                            user_id: solicitud.user_id,
                        });
                    } catch (newLocalError) {
                        console.error("Error al crear el nuevo local:", newLocalError.response ? newLocalError.response.data : newLocalError.message);
                        throw newLocalError;
                    }
                }

                // Si la acción es devolver a pendiente y el estado actual es APROBADA, eliminar la peluquería por email
                if (action === 'devolverAPendiente' && solicitud.estado === 'APROBADA') {
                    try {
                        const response = await axios.delete(`/api/delete-local/${encodeURIComponent(solicitud.email)}`);
                    } catch (deleteError) {
                        // Si la peluquería no existe (404), continuamos con el cambio de estado
                        if (deleteError.response && deleteError.response.status !== 404) {
                            console.error("Error al eliminar la peluquería:", deleteError.response ? deleteError.response.data : deleteError.message);
                            throw deleteError;
                        } else {
                            console.warn("No se encontró una peluquería para eliminar con el email:", solicitud.email);
                        }
                    }
                }

                // Cambiar el estado de la solicitud
                await axios.post(`/api/admin/requests/${solicitudId}/cambiarEstado`, { estado: estadoMap[action] }, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                });

                // Filtrar la solicitud de la lista
                solicitudes.value = solicitudes.value.filter(s => s.id !== solicitudId);
                abrirModal(mensajesExito[action], null, null);
            } catch (error) {
                console.error(`Error al ${action}:`, error.response ? error.response.data : error.message);
                abrirModal(
                    `Error al ${action === 'aprobar' ? 'aprobar' : action === 'denegar' ? 'denegar' : 'devolver a pendiente'} la solicitud. ` +
                    (error.message || (error.response && error.response.data && error.response.data.error ? error.response.data.error : 'Por favor, intenta de nuevo.')),
                    null,
                    null
                );
            }
        };

        const aprobarSolicitud = (solicitudId) => {
            abrirModal("¿Estás seguro de aprobar esta solicitud?", "aprobar", solicitudId);
        };

        const denegarSolicitud = (solicitudId) => {
            abrirModal("¿Estás seguro de denegar esta solicitud?", "denegar", solicitudId);
        };

        const devolverAPendiente = (solicitudId) => {
            abrirModal("¿Estás seguro de devolver esta solicitud a pendiente?", "devolverAPendiente", solicitudId);
        };

        return {
            solicitudes,
            estadoSeleccionado,
            estados,
            showModal,
            modalMessage,
            modalAction,
            aprobarSolicitud,
            denegarSolicitud,
            devolverAPendiente,
            ejecutarAccion,
            localidadNames,
        };
    },
};
</script>

<template>
    <div class="requests-container">
        <div class="filter-section">
            <label for="estado-select">Filtrar por estado:</label>
            <select id="estado-select" v-model="estadoSeleccionado">
                <option v-for="estado in estados" :key="estado" :value="estado">
                    {{ estado }}
                </option>
            </select>
        </div>

        <h2>Solicitudes de locales</h2>

        <div v-if="solicitudes.length === 0" class="no-data-message">
            No hay solicitudes con el estado "{{ estadoSeleccionado }}".
        </div>

        <div v-else class="card-grid">
            <div v-for="solicitud in solicitudes" :key="solicitud.id" class="solicitud-card">
                <img :src="`data:image/jpeg;base64,${solicitud.imagen}`" alt="Imagen principal" class="main-image" />
                <div class="card-content">
                    <p><strong>Estado:</strong> {{ solicitud.estado }}</p>
                    <p><strong>Fecha:</strong> {{ solicitud.fecha }}</p>
                    <p><strong>Nombre:</strong> {{ solicitud.nombre }}</p>
                    <p><strong>Descripción:</strong> {{ solicitud.descripcion }}</p>
                    <p><strong>Dirección:</strong> {{ solicitud.direccion }}</p>
                    <p><strong>Localidad:</strong> {{ localidadNames[solicitud.localidad] || "Cargando..." }}</p>
                    <p><strong>Email:</strong> {{ solicitud.email }}</p>
                    <p><strong>Teléfono:</strong> {{ solicitud.telefono }}</p>
                    <p><strong>Tipo:</strong> {{ solicitud.tipo }}</p>
                    <p><strong>ID Usuario:</strong> {{ solicitud.user_id }}</p>

                    <div class="imagenes-secundarias" v-if="solicitud.fotos_temporales">
                        <p><strong>Imágenes secundarias:</strong></p>
                        <div class="miniaturas">
                            <img v-for="(foto, index) in solicitud.fotos_temporales" :key="index"
                                :src="`data:image/jpeg;base64,${foto.imagen}`" alt="Imagen secundaria"
                                class="miniatura" />
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button v-if="solicitud.estado === 'PENDIENTE'" class="btn btn-confirm"
                            @click="aprobarSolicitud(solicitud.id)">Aprobar</button>
                        <button v-if="solicitud.estado === 'PENDIENTE'" class="btn btn-cancel"
                            @click="denegarSolicitud(solicitud.id)">Denegar</button>
                        <button v-if="solicitud.estado !== 'PENDIENTE'" class="revert-btn"
                            @click="devolverAPendiente(solicitud.id)">Devolver a pendiente</button>
                    </div>
                </div>
            </div>
        </div>

        <ModalConfirm v-model:show="showModal" :message="modalMessage" :show-cancel="modalAction !== null"
            @confirm="modalAction ? ejecutarAccion() : null" @cancel="modalAction = null" />
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;
@use "@/sass/common" as *;

* {
    @include fuente("parrafo");
}

.filter-section {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

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

.action-buttons {
    gap: 10px;
}

.revert-btn {
    background-color: map-get($colores, 'naranja');
    color: map-get($colores, 'blanco');
}

.revert-btn:hover {
    background-color: map-get($colores, 'naranja_oscuro');
}

.no-data-message {
    text-align: center;
    margin-top: 20px;
    color: map-get($colores, 'gris_oscuro');
    font-style: italic;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.solicitud-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 16px;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.main-image {
    width: 100%;
    max-height: 180px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.card-content {
    width: 100%;
}

.imagenes-secundarias {
    margin-top: 1rem;
}

.miniaturas {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 0.5rem;
}

.miniatura {
    width: 120px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ccc;
}
</style>