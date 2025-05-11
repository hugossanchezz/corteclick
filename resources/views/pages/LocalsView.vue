<script>
import SecondaryButton from "@/js/components/actions/SecondaryButton.vue";
import { ref, onMounted, computed, watch } from "vue";

export default {
    name: "LocalsView",
    components: { SecondaryButton },
    setup() {
        const peluquerias = ref([]);
        const loading = ref(true);
        const tipoLocalFilter = ref("");
        const ordenValoracion = ref("");
        const codigoPostalBusqueda = ref("");
        const peluqueriasFiltradas = ref([]);
        const errorBusqueda = ref(null);

        const cargarPeluquerias = () => {
            loading.value = true;
            fetch("/api/locals")
                .then((response) => response.json())
                .then((data) => {
                    peluquerias.value = data;
                    loading.value = false;
                    aplicarFiltros();
                })
                .catch((error) => {
                    console.error("Error al obtener las peluquerías:", error);
                    loading.value = false;
                    errorBusqueda.value = "Error al cargar las peluquerías. Por favor, intenta de nuevo.";
                });
        };

        onMounted(() => {
            cargarPeluquerias();
        });

        const aplicarFiltros = () => {
            let resultado = peluquerias.value;
            errorBusqueda.value = null;

            // Filtrar por tipo de local
            if (tipoLocalFilter.value) {
                resultado = resultado.filter(
                    (p) => p.tipo === tipoLocalFilter.value
                );
            }

            // Filtrar por código postal / nombre de localidad
            if (codigoPostalBusqueda.value) {
                const buscarLocalidad = (local) => {
                    return fetch(`/api/localities/${codigoPostalBusqueda.value}`)
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 404) {
                                    throw new Error('Localidad no encontrada');
                                }
                                throw new Error(`Error al buscar localidad: ${response.status} ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            return data === local.localidad;
                        })
                        .catch(error => {
                            console.error("Error al obtener la localidad:", error);
                            if (error.message === 'Localidad no encontrada') {
                                errorBusqueda.value = "No se encontró ninguna localidad con ese código postal o nombre.";
                            } else {
                                errorBusqueda.value = "Error al buscar la localidad. Por favor, inténtalo de nuevo.";
                            }
                            return false;
                        });
                };

                const peluqueriasFiltradasTemp = [];
                const promesas = resultado.map(peluqueria => {
                    return buscarLocalidad(peluqueria).then(coincide => {
                        if (coincide) {
                            peluqueriasFiltradasTemp.push(peluqueria);
                        }
                    });
                });

                Promise.all(promesas).then(() => {
                    resultado = peluqueriasFiltradasTemp;

                    // Ordenar por valoración
                    if (ordenValoracion.value) {
                        if (ordenValoracion.value === "mayor-menor") {
                            resultado = [...resultado].sort((a, b) => b.valoracion - a.valoracion);
                        } else if (ordenValoracion.value === "menor-mayor") {
                            resultado = [...resultado].sort((a, b) => a.valoracion - b.valoracion);
                        }
                    }
                    peluqueriasFiltradas.value = resultado;
                });
                return;
            }

            // Ordenar por valoración
            if (ordenValoracion.value) {
                if (ordenValoracion.value === "mayor-menor") {
                    resultado = [...resultado].sort((a, b) => b.valoracion - a.valoracion);
                } else if (ordenValoracion.value === "menor-mayor") {
                    resultado = [...resultado].sort((a, b) => a.valoracion - b.valoracion);
                }
            }
            peluqueriasFiltradas.value = resultado;
        };

        watch([tipoLocalFilter, ordenValoracion, codigoPostalBusqueda], () => {
            aplicarFiltros();
        });

        const resetFilters = () => {
            tipoLocalFilter.value = "";
            ordenValoracion.value = "";
            codigoPostalBusqueda.value = "";
            aplicarFiltros();
        };

        return {
            peluquerias,
            loading,
            tipoLocalFilter,
            ordenValoracion,
            codigoPostalBusqueda,
            resetFilters,
            peluqueriasFiltradas,
            errorBusqueda
        };
    },
};
</script>

<template>
    <div class="locals__space grid">
        <aside class="flex-column">
            <h2>Filtros</h2>

            <div class="filtro__seccion">
                <p>Tipo de local</p>
                <div class="opcion__filtro">
                    <input type="radio" id="barberias" name="tipo-local" value="BARBERIA" v-model="tipoLocalFilter" />
                    <label for="barberias">Barberías para Hombres</label>
                </div>
                <div class="opcion__filtro">
                    <input type="radio" id="peluquerias" name="tipo-local" value="PELUQUERIA"
                        v-model="tipoLocalFilter" />
                    <label for="peluquerias">Peluquerías para Mujeres</label>
                </div>
                <div class="opcion__filtro">
                    <input type="radio" id="unisex" name="tipo-local" value="UNISEX" v-model="tipoLocalFilter" />
                    <label for="unisex">Locales Unisex</label>
                </div>
            </div>

            <div class="filtro__seccion">
                <p>Ordenar por valoración</p>
                <div class="opcion__filtro">
                    <input type="radio" id="mayor-menor" name="orden-valoracion" value="mayor-menor"
                        v-model="ordenValoracion" />
                    <label for="mayor-menor">De mayor a menor <span>★ ★ ★ ★</span></label>
                </div>
                <div class="opcion__filtro">
                    <input type="radio" id="menor-mayor" name="orden-valoracion" value="menor-mayor"
                        v-model="ordenValoracion" />
                    <label for="menor-mayor">De menor a mayor <span>★ ★ </span></label>
                </div>
            </div>

            <p class="filtro__reset" @click="resetFilters">Restablecer filtros</p>
        </aside>
        <main class="flex-column">
            <div class="peluquerias__buscador">
                <input type="text" placeholder="Introduce el código postal o nombre de la localidad"
                    v-model="codigoPostalBusqueda" />
                <img src="/img/utils/search.svg" alt="Buscador de locales por código postal" />
            </div>
            <div v-if="loading">Cargando peluquerías...</div>
            <div v-else-if="errorBusqueda">
                <p class="error-message">{{ errorBusqueda }}</p>
            </div>
            <div v-else-if="peluqueriasFiltradas.length === 0">
                No hay peluquerías disponibles.
            </div>

            <div v-else class="peluquerias__grid grid">
                <div v-for="peluqueria in peluqueriasFiltradas" :key="peluqueria.id"
                    class="peluqueria__card flex-column">
                    <img src="/img/utils/corteclick.png" alt="Imagen principal de la peluquería" />
                    <h2 class="card__info flex">
                        {{ peluqueria.nombre }}
                        <span>4,4 ★</span>
                    </h2>
                    <p class="card__description">
                        {{ peluqueria.descripcion }}
                    </p>
                    <div class="card__info flex">
                        <p class="flex">
                            <img class="card__location" src="/img/locals/location.svg" alt="Icono de la ubicación" />
                            {{ peluqueria.direccion }}
                        </p>

                        <img class="card__bookmark" @click="peluqueria.favorito = !peluqueria.favorito"
                            src="/img/locals/bookmark.svg" alt="Guardar peluquería como favorita" />
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.locals__space {
    grid-template-columns: 1fr 5fr;

    aside {
        border-right: 2px solid map-get($colores, "gris_claro");
        padding: 1.5rem;
        align-items: center;

        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }

        .filtro__seccion {
            margin-bottom: 2rem;
            width: 100%;

            p {
                margin-bottom: 5px;
            }

            .opcion__filtro label {
                @include fuente("parrafo_max");
                margin-left: 5px;
                margin-bottom: 5px;

                span {
                    color: map-get($colores, "naranja");
                }
            }
        }

        .filtro__reset {
            color: map-get($colores, "negro");
            text-decoration: none;

            &:hover {
                color: map-get($colores, "naranja");
                text-decoration: underline;
                cursor: pointer;
            }
        }
    }

    main {

        align-items: center;
        padding: 3rem 4rem;

        .peluquerias__buscador {
            position: relative;
            width: 40%;

            input {
                @include fuente("parrafo");
                border: 2px solid map-get($colores, "gris_claro");
                border-radius: 5px;
                width: 100%;
                padding: 5px 2rem;

                &:focus {
                    outline: 1px solid map-get($colores, "naranja");
                }
            }

            img {
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
            }
        }

        .peluquerias__grid {
            width: 100%;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;

            .peluqueria__card {
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                padding: 20px;
                gap: 10px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;

                &:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
                }

                strong {
                    font-weight: bold;
                }

                img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 8px
                }

                .card__description {
                    height: 48px;
                    overflow: hidden;
                    /* Oculta el texto que se desborda del contenedor */
                    display: -webkit-box;
                    /* Necesario para la propiedad -webkit-line-clamp */
                    -webkit-line-clamp: 3;
                    line-clamp: 3;
                    /* Limita el contenido a tres líneas */
                    -webkit-box-orient: vertical;
                    /* Establece la orientación vertical del contenido */
                    text-overflow: ellipsis;
                }

                .card__info {
                    width: 100%;
                    justify-content: space-between;
                    justify-self: end;

                    p {
                        align-items: center;
                    }

                    .card__location {
                        width: 25px;
                        height: 25px;
                    }

                    .card__bookmark {
                        width: 30px;
                        height: 30px;
                        cursor: pointer;
                    }
                }

            }

        }
    }

}

.error-message {
    color: red;
    margin-top: 10px;
}

@media (max-width: 768px) {
    .peluquerias__grid {
        grid-template-columns: 1fr;
    }

    .peluqueria__card {
        margin-bottom: 15px;
    }
}
</style>