<script>
import { ref, onMounted, computed } from 'vue';
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: 'ValoracionesLocal',
    components: { ModalConfirm },
    props: {
        idPeluqueria: {
            type: Number,
            required: true,
        },
        idUsuario: {
            type: Number,
            required: true,
        },
    },
    setup(props) {
        const valoraciones = ref([]);
        const puntuacionSeleccionada = ref(null);
        const mostrarTodas = ref(false);
        const puedeValorar = ref(false);
        const mostrarFormulario = ref(false);
        const comentario = ref('');
        const loadingValoraciones = ref(true);
        // Variables para el modal
        const showModal = ref(false);
        const modalTitle = ref('');
        const modalMessage = ref('');

        const valoracionesMostradas = computed(() => {
            return mostrarTodas.value
                ? valoraciones.value
                : valoraciones.value.slice(0, 5);
        });

        const cargarValoraciones = async () => {
            try {
                // 1. Comprobar si el usuario puede valorar
                const resCheck = await fetch(
                    `/api/citas/${props.idPeluqueria}/usuario/${props.idUsuario}`
                );
                if (!resCheck.ok) throw new Error(`❌ Error en resCheck (${resCheck.status})`);
                const dataCheck = await resCheck.json();
                puedeValorar.value = dataCheck.tieneCita;

                // 2. Obtener valoraciones
                const resVal = await fetch(`/api/valorations/${props.idPeluqueria}`);
                const rawText = await resVal.text();
                const dataVal = JSON.parse(rawText);

                // 3. Obtener nombres de usuarios
                const valoracionesConNombres = await Promise.all(
                    dataVal.map(async (v) => {
                        let nombreUsuario = 'Usuario';
                        try {
                            console.log(`Obteniendo nombre del usuario ${v.id_usuario}`);
                            const resUser = await fetch(`/api/user/${v.id_usuario}/name`);
                            if (resUser.ok) {
                                const dataUser = await resUser.text();
                                nombreUsuario = dataUser || 'Usuario';
                            }
                        } catch (e) {
                            console.warn(`No se pudo obtener el nombre del usuario ${v.id_usuario}`);
                        }

                        return {
                            estrellas: v.puntuacion,
                            fecha: new Date(v.fecha).toLocaleDateString('es-ES', {
                                day: 'numeric',
                                year: 'numeric',
                                month: 'long',
                            }),
                            texto: v.valoracion,
                            nombreUsuario,
                        };
                    })
                );

                valoraciones.value = valoracionesConNombres;
                loadingValoraciones.value = false;
            } catch (error) {
                console.error('❌ Error al cargar valoraciones:', error);
            }
        };

        const confirmarValoracion = () => {
            if (!puntuacionSeleccionada.value) {
                modalTitle.value = 'Error';
                modalMessage.value = 'Selecciona una puntuación antes de enviar la valoración.';
                showModal.value = true;
                return;
            }

            // Mostrar modal de confirmación
            modalTitle.value = 'Confirmar valoración';
            modalMessage.value = comentario.value.trim()
                ? '¿Estás seguro de que quieres enviar esta valoración?'
                : '¿Estás seguro de que quieres enviar una valoración sin descripción?';
            showModal.value = true;
        };

        const enviarValoracion = async () => {
            try {
                const response = await fetch("/api/valorations", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        id_usuario: props.idUsuario,
                        id_peluqueria: props.idPeluqueria,
                        puntuacion: puntuacionSeleccionada.value,
                        valoracion: comentario.value,
                    }),
                });

                if (!response.ok) {
                    const err = await response.json();
                    modalTitle.value = 'Error al enviar valoración';
                    modalMessage.value = err.error || 'Error al enviar valoración.';
                    showModal.value = true;
                    return;
                }

                const resJson = await response.json();
                modalTitle.value = 'Valoración enviada';
                modalMessage.value = 'La valoración se ha enviado correctamente.';
                showModal.value = true;

                // Reset formulario
                puntuacionSeleccionada.value = null;
                comentario.value = '';
                mostrarFormulario.value = false;

                // Recargar valoraciones y volver a comprobar
                await cargarValoraciones();
            } catch (error) {
                console.error("❌ Error en la petición:", error);
                modalTitle.value = 'Error';
                modalMessage.value = 'Error inesperado al enviar valoración.';
                showModal.value = true;
            }
        };

        const handleModalConfirm = () => {
            // Si el usuario confirma el modal, enviar la valoración
            if (modalTitle.value === 'Confirmar valoración') {
                enviarValoracion();
            }
            showModal.value = false; // Cerrar el modal
        };

        const verMasValoraciones = () => {
            mostrarTodas.value = !mostrarTodas.value;
        };

        onMounted(() => {
            cargarValoraciones();
        });

        return {
            valoraciones,
            valoracionesMostradas,
            puedeValorar,
            mostrarFormulario,
            comentario,
            puntuacionSeleccionada,
            confirmarValoracion,
            verMasValoraciones,
            mostrarTodas,
            showModal,
            modalTitle,
            modalMessage,
            handleModalConfirm,
            loadingValoraciones,
        };
    },
};
</script>

<template>
    <div class="valoraciones_local">
        <div class="valoracion_top flex">
            <!-- Estado: cargando -->
            <p v-if="loadingValoraciones">Cargando valoraciones...</p>

            <!-- Estado: sin valoraciones -->
            <p v-else-if="valoraciones.length === 0">No hay valoraciones para este local.</p>

            <!-- Estado: con valoraciones -->
            <p v-else>
                Hay {{ valoraciones.length }}
                {{ valoraciones.length === 1 ? 'valoración' : 'valoraciones' }}.
            </p>

            <!-- Si puede valorar -->
            <div v-if="puedeValorar" class="enviar_valoracion flex">
                <button v-if="!mostrarFormulario" class="btn_valoraciones flex" @click="mostrarFormulario = true">
                    Publicar valoración <img src="/img/utils/send.svg" alt="Publicar valoración">
                </button>

                <!-- Formulario de valoración -->
                <div v-else class="valoracion_formulario flex-column">
                    <div class="estrellas_selector flex">
                        <button v-for="n in 5" :key="n" @click="puntuacionSeleccionada = n"
                            :class="['btn', 'btn-star', { valoracion_seleccionada: puntuacionSeleccionada === n }]">
                            {{ n }} ★
                        </button>
                    </div>
                    <p class="mensaje_info">La valoración se asignará automaticamente a la última cita terminada sin valoración.</p>
                    <div class="valoracion_text flex">
                        <textarea type="text" v-model="comentario"
                            placeholder="Escriba aquí su valoración del local"> </textarea>

                        <div class="valoracion_text_botones flex">
                            <button @click="confirmarValoracion" class="btn btn-confirm">
                                <img src="/img/utils/send.svg" alt="Enviar valoración">
                            </button>
                            <button @click="mostrarFormulario = false" class="btn btn-cancel">
                                <img src="/img/utils/close.svg" alt="Cancelar valoración">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Si no puede valorar -->
            <p v-else class="mensaje_info">
                Debes haber completado al menos una cita para poder valorar este local.
            </p>
        </div>

        <!-- Lista de valoraciones -->
        <div class="valoracion_card" v-for="(valoracion, index) in valoracionesMostradas" :key="index">
            <div class="valoracion_card_top flex">
                <div class="valoracion_estrellas flex">
                    <div class="estrellas flex">
                        <span v-for="n in valoracion.estrellas" :key="n" class="estrellas_doradas">★</span>
                    </div>
                    <div class="valoracion_usuario">{{ valoracion.nombreUsuario }}</div>
                </div>
                <div>
                    <p class="valoracion_fecha">{{ valoracion.fecha }}</p>
                </div>
            </div>
            <p class="valoracion_texto">{{ valoracion.texto }}</p>
        </div>

        <!-- Botón ver más/menos -->
        <button class="btn_valoraciones ver_mas flex" v-if="valoraciones.length > 5" @click="verMasValoraciones">
            {{ mostrarTodas ? 'Ver menos valoraciones' : 'Ver más valoraciones' }}
        </button>

        <!-- Modal de confirmación -->
        <ModalConfirm v-model:show="showModal" :message="modalMessage" :title="modalTitle"
            :showCancel="modalTitle === 'Confirmar valoración'" confirmText="Aceptar" @confirm="handleModalConfirm"
            @cancel="showModal = false" />
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.valoraciones_local {
    border: 2px solid #ccc;
    border-radius: 10px;
    margin: auto;
    margin-bottom: 40px;
    width: 60%;
    padding: 1rem 4rem;

    .valoracion_top {
        justify-content: space-between;
        align-items: center;

        .mensaje_info {
            font-style: italic;
            color: #797979;
        }

        .enviar_valoracion {
            width: 60%;
            justify-content: end;

            .valoracion_formulario {
                width: 100%;
                display: flex;
                gap: 0.5rem;

                .mensaje_info {
                    font-style: italic;
                    color: map-get($colores, 'gris');
                    font-size: smaller !important;
                }
            }

            .estrellas_selector {
                gap: 5px;

                .btn-star {
                    background: none;
                    border: 2px solid map-get($colores, 'gris_claro');
                    border-radius: 5px;
                    padding: 0.3rem;
                    cursor: pointer;

                    &:hover {
                        border-color: gold;
                    }
                }

                .valoracion_seleccionada {
                    border-color: gold;
                    color: gold;
                }
            }

            .valoracion_text textarea {
                width: 100%;
                height: 5rem;
                border: 2px solid map-get($colores, "gris");
                border-radius: 5px;
                padding: 10px;
                resize: none;

                &:focus {
                    border-color: map-get($colores, "naranja");
                    outline: map-get($colores, 'naranja');
                }
            }

            .valoracion_text .valoracion_text_botones {
                padding: 5px;
                gap: 5px;
            }
        }
    }
}

.valoracion_card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;

    .valoracion_card_top {
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .valoracion_estrellas {
        font-weight: bold;

        .estrellas {
            width: 5rem;
        }

        .valoracion_usuario {
            color: rgb(180, 180, 180);
        }
    }

    .estrellas_doradas {
        color: gold;
    }

    .valoracion_fecha {
        font-size: 0.9rem;
        color: gray;
    }
}

.btn_valoraciones {
    background-color: #333;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    gap: 10px;
}

.ver_mas {
    margin: 1rem auto;
}

@media (max-width: 1024px) {
    .valoraciones_local {
        width: 70%;
        padding: 1rem 3rem;
    }
}

@media (max-width: 800px) {
    .valoraciones_local {
        width: 80%;
        padding: 1rem 2rem;
    }
}

@media (max-width: 650px) {
    .valoraciones_local {
        width: 90%;
        padding: 1rem;
    }
}
</style>
