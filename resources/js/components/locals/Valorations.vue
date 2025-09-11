<!-- // prueba -->
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
                    `/api/citas/${props.idPeluqueria}/usuario-${props.idUsuario}`
                );
                if (!resCheck.ok) {
                    throw new Error(`❌ Error en resCheck (${resCheck.status})`);
                }

                const dataCheck = await resCheck.json();
                puedeValorar.value = dataCheck.tieneCita;

                // 2. Obtener valoraciones
                const resVal = await fetch(`/api/valorations/${props.idPeluqueria}`);
                const rawText = await resVal.text(); // obtenemos la respuesta como texto plano

                try {
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
            <p>Hay {{ valoraciones.length }} valoraciones</p>

            <!-- Si puede valorar -->
            <div v-if="puedeValorar">
                <button v-if="!mostrarFormulario" class="ver-mas" @click="mostrarFormulario = true">
                    Publicar valoración
                </button>

                <!-- Formulario de valoración -->
                <div v-else class="valoracion-formulario">
                    <div class="estrellas-selector">
                        <button v-for="n in 5" :key="n" @click="puntuacionSeleccionada = n">
                            {{ n }} ★
                        </button>
                    </div>
                    <input type="text" v-model="comentario" placeholder="Escriba aquí su valoración del local" />
                    <button @click="enviarValoracion">
                        <span>➡️</span>
                    </button>
                </div>
            </div>

            <!-- Si no puede valorar -->
            <p v-else class="mensaje_info">
                Debes haber completado al menos una cita para poder valorar este local.
            </p>
        </div>

        <!-- Lista de valoraciones -->
        <div class="valoracion-card" v-for="(valoracion, index) in valoracionesMostradas" :key="index">
            <div class="valoracion-estrellas">
                <span>{{ valoracion.estrellas }} ★</span>
            </div>
            <p class="valoracion-fecha">{{ valoracion.fecha }}</p>
            <p class="valoracion-texto">{{ valoracion.texto }}</p>
        </div>

        <!-- Botón ver más/menos -->
        <button class="ver-mas" v-if="valoraciones.length > 10" @click="verMasValoraciones">
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
    width: 60%;
    padding: 1rem 4rem;

    .valoracion_top {
        justify-content: space-between;

        .mensaje_info {
            font-style: italic;
            color: #797979;
        }
    }
}

.valoracion-formulario {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1rem 0;
}

.estrellas-selector button {
    background: none;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 0.3rem;
    cursor: pointer;
}

.valoracion-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.valoracion-estrellas {
    font-weight: bold;
}

.valoracion-fecha {
    font-size: 0.9rem;
    color: gray;
}

.ver-mas {
    background-color: #333;
    color: white;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}
</style>
