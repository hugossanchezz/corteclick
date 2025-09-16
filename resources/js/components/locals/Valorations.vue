<script>
import { ref, onMounted, computed } from 'vue';

export default {
    name: 'ValoracionesLocal',
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

        const valoracionesMostradas = computed(() => {
            return mostrarTodas.value
                ? valoraciones.value
                : valoraciones.value.slice(0, 10);
        });

        const cargarValoraciones = async () => {
            try {
                // 1. Comprobar si el usuario puede valorar
                const resCheck = await fetch(
                    `/api/citas/${props.idPeluqueria}/usuario/${props.idUsuario}`
                );
                if (!resCheck.ok) {
                    throw new Error(`❌ Error en resCheck (${resCheck.status})`);
                }

                const dataCheck = await resCheck.json();
                puedeValorar.value = dataCheck.tieneCita;


                try {
                    // 2. Obtener valoraciones
                    const resVal = await fetch(`/api/valorations/${props.idPeluqueria}`);
                    const rawText = await resVal.text(); // obtenemos la respuesta como texto plano
                    const dataVal = JSON.parse(rawText); // intenta convertirla a JSON


                    valoraciones.value = dataVal.map((v) => ({
                        estrellas: v.puntuacion,
                        fecha: new Date(v.fecha).toLocaleDateString('es-ES', {
                            day: 'numeric',
                            year: 'numeric',
                            month: 'long',
                        }),
                        texto: v.valoracion,
                    }));

                } catch (jsonError) {
                    console.error("❌ Error al parsear JSON de /api/valorations:", rawText);
                    throw jsonError; // vuelve a lanzar para que lo capture el catch exterior
                }
            } catch (error) {
                console.error('❌ Error al cargar valoraciones:', error);
            }
        };

        const enviarValoracion = () => {
            console.log('Enviar valoración:', {
                idUsuario: props.idUsuario,
                idPeluqueria: props.idPeluqueria,
                estrellas: puntuacionSeleccionada.value,
                texto: comentario.value,
            });
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
            enviarValoracion,
            verMasValoraciones,
            mostrarTodas,
        };
    },
};
</script>

<template>
    <div class="valoraciones_local">

        <div class="valoracion_top flex">
            <p>
                Hay {{ valoraciones.length }} {{ valoraciones.length === 1 ? 'valoración' : 'valoraciones' }}
            </p>

            <!-- Si puede valorar -->
            <div v-if="puedeValorar" class="enviar_valoracion flex">
                <button v-if="!mostrarFormulario" class="ver_mas flex" @click="mostrarFormulario = true">
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
                    <div class="valoracion_text flex">
                        <textarea type="text" v-model="comentario"
                            placeholder="Escriba aquí su valoración del local"> </textarea>

                        <div class="valoracion_text_botones flex">
                            <button @click="enviarValoracion" class="btn btn-confirm">
                                <img src="/img/utils/send.svg" alt="Enviar valoración">
                            </button>
                            <button @click="mostrarFormulario = false" class="btn btn-cancel">
                                <img src="/img/utils/close.svg" alt="Enviar valoración">
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
                <div class="valoracion_estrellas estrellas_doradas">
                    <span v-for="n in valoracion.estrellas" :key="n">★</span>
                </div>
                <p class="valoracion_fecha">{{ valoracion.fecha }}</p>
            </div>
            <p class="valoracion_texto">{{ valoracion.texto }}</p>
        </div>

        <!-- Botón ver más/menos -->
        <button class="ver_mas flex" v-if="valoraciones.length > 10" @click="verMasValoraciones">
            {{ mostrarTodas ? 'Ver menos valoraciones' : 'Ver más valoraciones' }}
        </button>
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
        margin-bottom: 10px;

        .mensaje_info {
            font-style: italic;
            color: #797979;
        }

        .enviar_valoracion {
            width: 50%;
            justify-content: end;

            .valoracion_formulario {
                width: 100%;
                display: flex;
                gap: 0.5rem;
            }

            .estrellas_selector {
                gap: 5px;

                .btn-star {
                    background: none;
                    border: 2px solid map-get($colores, 'gris_claro');
                    border-radius: 5px;
                    padding: 0.3rem;
                    cursor: pointer;
                }

                .valoracion_seleccionada{
                    border-color: gold;
                    color: gold;
                }
            }

            .valoracion_text textarea {
                width: 100%;
                height: 5rem;
                border: 2px solid map-get($colores, "gris_claro");
                border-radius: 5px;
                padding: 10px;
                resize: none;

                &:focus {
                    border-color: map-get($colores, "naranja");
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
    margin-bottom: 1rem;

    .valoracion_card_top {
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .valoracion_estrellas {
        font-weight: bold;
        color: gold;
    }

    .valoracion_fecha {
        font-size: 0.9rem;
        color: gray;
    }
}

.ver_mas {
    background-color: #333;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    gap: 10px;
}
</style>
