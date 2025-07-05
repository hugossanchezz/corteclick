<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: "LocalRequests",
    components: {
        ModalConfirm,
    },
    setup() {
        // Lista de solicitudes recibidas del backend
        const solicitudes = ref([]);

        // Estados disponibles de las solicitudes
        const estados = ref(["PENDIENTE", "RECHAZADA"]);

        // Variables para controlar el modal de confirmación
        const showModal = ref(false);
        const modalMessage = ref("");
        const modalAction = ref(null);
        const modalSolicitudId = ref(null);

        // Mapeo de IDs de localidades a sus nombres
        const localidadNames = ref({});

        // Obtiene todas las solicitudes del backend
        const cargarSolicitudes = async () => {
            try {
                const response = await axios.get(`/api/admin/requests`);
                solicitudes.value = response.data;
                await cargarNombresLocalidades(); // Carga los nombres de las localidades
            } catch (error) {
                console.error("Error fetching local requests:", error);
                solicitudes.value = [];
                modalMessage.value = "Error al cargar las solicitudes. Por favor, intenta de nuevo más tarde.";
                modalAction.value = null;
                showModal.value = true;
            }
        };

        // Carga los nombres de las localidades únicas encontradas en las solicitudes
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

        // Abre el modal de confirmación con mensaje, acción y solicitud asociada
        const abrirModal = (message, action, solicitudId) => {
            modalMessage.value = message;
            modalAction.value = action;
            modalSolicitudId.value = solicitudId;
            showModal.value = true;
        };

        // Ejecuta la acción seleccionada (aprobar o denegar)
        const ejecutarAccion = async () => {
            const action = modalAction.value;
            const solicitudId = modalSolicitudId.value;
            const estadoMap = {
                aprobar: 'APROBADA',
                denegar: 'RECHAZADA',
            };
            const mensajesExito = {
                aprobar: "Solicitud APROBADA exitosamente y peluquería creada.",
                denegar: "Solicitud DENEGADA exitosamente.",
            };

            try {
                const solicitud = solicitudes.value.find(s => s.id === solicitudId);
                if (!solicitud) throw new Error("Solicitud no encontrada");

                // Si se aprueba, crea el nuevo local
                if (action === 'aprobar') {
                    try {
                        const formData = new FormData();
                        formData.append("nombre", solicitud.nombre);
                        formData.append("descripcion", solicitud.descripcion);
                        formData.append("direccion", solicitud.direccion);
                        formData.append("localidad", solicitud.localidad);
                        formData.append("email", solicitud.email);
                        formData.append("telefono", solicitud.telefono);
                        formData.append("tipo", solicitud.tipo);
                        formData.append("user_id", solicitud.user_id);
                        formData.append("imagen", solicitud.imagen); // imagen principal

                        await axios.post(`/api/approve-request/${solicitudId}`);
                    } catch (newLocalError) {
                        console.error("Error al crear el nuevo local:", newLocalError.response ? newLocalError.response.data : newLocalError.message);
                        throw newLocalError;
                    }
                }

                // Cambia el estado de la solicitud (APROBADA o RECHAZADA)
                await axios.post(`/api/admin/requests/${solicitudId}/cambiarEstado`, {
                    estado: estadoMap[action]
                }, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                });

                // Elimina la solicitud de la lista
                solicitudes.value = solicitudes.value.filter(s => s.id !== solicitudId);
                abrirModal(mensajesExito[action], null, null); // Muestra mensaje de éxito
            } catch (error) {
                console.error(`Error al ${action}:`, error.response ? error.response.data : error.message);
                abrirModal(
                    `Error al ${action === 'aprobar' ? 'aprobar' : 'denegar'} la solicitud. ` +
                    (error.message || (error.response?.data?.error ?? 'Por favor, intenta de nuevo.')),
                    null,
                    null
                );
            }
        };

        // Inicia el proceso de aprobación con confirmación
        const aprobarSolicitud = (solicitudId) => {
            abrirModal("¿Estás seguro de aprobar esta solicitud?", "aprobar", solicitudId);
        };

        // Inicia el proceso de denegación con confirmación
        const denegarSolicitud = (solicitudId) => {
            abrirModal("¿Estás seguro de denegar esta solicitud?", "denegar", solicitudId);
        };

        // Ejecuta la carga inicial de solicitudes al montar el componente
        onMounted(() => {
            cargarSolicitudes();
        });

        return {
            solicitudes,
            estados,
            showModal,
            modalMessage,
            modalAction,
            aprobarSolicitud,
            denegarSolicitud,
            ejecutarAccion,
            localidadNames,
        };
    },
};
</script>

<template>
    <div class="requests_container">

        <h1>Solicitudes de locales</h1>

        <div v-if="solicitudes.length === 0" class="no_data_message">
            No hay solicitudes pendientes en este momento.
        </div>

        <div v-else class="card_grid">
            <div v-for="solicitud in solicitudes" :key="solicitud.id" class="solicitud_card">
                <img v-if="solicitud.imagen" :src="`data:image/jpeg;base64,${solicitud.imagen}`" alt="Imagen principal"
                    class="main_image" />
                <img v-else src="/img/utils/corteclick.png" alt="Imagen principal" class="main_image">

                <div class="card_content flex-column">
                    <div class="informacion_principal">
                        <div class="flex">
                            <p><strong>Estado:</strong> {{ solicitud.estado }}</p>
                            <p><strong>Fecha:</strong> {{ solicitud.fecha }}</p>
                            <p><strong>ID Usuario:</strong> {{ solicitud.user_id }}</p>
                        </div>
                        <p><strong>Nombre:</strong> {{ solicitud.nombre }}</p>
                        <p><strong>Descripción:</strong> <span class="descripcion">{{ solicitud.descripcion }}</span>
                        </p>
                        <p><strong>Dirección:</strong> {{ solicitud.direccion }}</p>
                        <div class="flex">
                            <p><strong>Tipo:</strong> {{ solicitud.tipo }}</p>
                            <p><strong>Localidad:</strong> {{ localidadNames[solicitud.localidad] || "Cargando..." }}
                            </p>
                            <p><strong>Teléfono:</strong> {{ solicitud.telefono }}</p>
                        </div>
                        <p><strong>Email:</strong> {{ solicitud.email }}</p>
                    </div>

                    <div class="imagenes_secundarias" v-if="solicitud.fotos_temporales">
                        <strong>Imágenes secundarias:</strong>
                        <div class="miniaturas grid">
                            <p v-if="!solicitud.fotos_temporales.length" class="no_images_message">No hay imágenes
                                secundarias.</p>
                            <img v-else v-for="(foto, index) in solicitud.fotos_temporales" :key="index"
                                :src="`data:image/jpeg;base64,${foto.imagen}`" alt="Imagen secundaria"
                                class="miniatura" />
                        </div>
                    </div>

                    <div class="action_buttons flex">
                        <button v-if="solicitud.estado === 'PENDIENTE'" class="btn btn-confirm"
                            @click="aprobarSolicitud(solicitud.id)">Aprobar <img src="/img/utils/tick.svg"
                                alt="Aprobar solicitud"></button>
                        <button v-if="solicitud.estado === 'PENDIENTE'" class="btn btn-cancel"
                            @click="denegarSolicitud(solicitud.id)">Denegar <img src="/img/utils/close.svg"
                                alt="Denegar solicitud"></button>
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

.filter_section {
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

.no_data_message {
    text-align: center;
    margin-top: 20px;
    color: map-get($colores, 'gris_oscuro');
    font-style: italic;
}

.card_grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 450px));
    gap: 1.5rem;
    margin-top: 2rem;

    .solicitud_card {
        height: 100%;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);

        .main_image {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .card_content {
            width: 100%;
            flex-grow: 1;

            p {
                border: 2px solid map-get($colores, "gris_claro");
                display: flex;
                flex-direction: column;
                border-radius: 4px;
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
                padding-bottom: 2.5px;

                strong {
                    background-color: map-get($colores, "blanco");
                    margin-bottom: 5px;
                    text-align: start;
                    padding-left: 5px;
                }

                .descripcion {
                    height: 2.5rem;
                    overflow: hidden;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    line-clamp: 2;
                    -webkit-box-orient: vertical;
                }
            }

            .action_buttons {
                width: 100%;
                margin-top: auto;
                padding: 5px 2.5px;
                justify-content: space-evenly;
            }

            .imagenes_secundarias {
                margin-top: 1rem;

                .miniaturas {
                    margin-top: 0.5rem;
                    grid-template-columns: 1fr 1fr;
                    grid-template-rows: 1fr 1fr;
                    gap: 2.5px;

                    .miniatura {
                        width: 100%;
                        height: 80px;
                        object-fit: cover;
                        border-radius: 4px;
                    }

                    .no_images_message {
                        padding: 5px 0;
                    }
                }
            }
        }
    }
}

@media (max-width: 1224px) {
    .card_grid {
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    }
}

@media (max-width: 1024px) {
    .requests_container {
        margin-top: 1rem;
        padding: 1rem;
    }
}

@media (max-width: 800px) {
    .requests_container {
        padding: 0 2.5rem;
    }
}

@media (max-width: 600px) {
    .requests_container {
        padding: 0 1rem;
    }
}

@media (max-width: 500px) {
    .requests_container {
        padding: 0;
    }
}
</style>