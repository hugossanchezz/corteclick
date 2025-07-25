<script>
import SecondaryButton from "@/js/components/actions/SecondaryButton.vue";
import { ref, onMounted, watch, computed } from "vue";
import { useRouter, useRoute } from "vue-router";

export default {
    name: "LocalsView",
    components: { SecondaryButton },
    setup() {
        const peluquerias = ref([]);
        const peluqueriasFiltradas = ref([]);
        const errorBusqueda = ref(null);
        const loading = ref(true);
        const tipoLocalFilter = ref("");
        const ordenValoracion = ref("");
        const codigoPostalBusqueda = ref("");
        const paginaActual = ref(1);
        const itemsPorPagina = 12;

        const router = useRouter();

        const cargarPeluquerias = async () => {
            loading.value = true;
            try {
                const res = await fetch(`/api/locals`);
                if (!res.ok) throw new Error("Error al cargar peluquerías");
                const data = await res.json();

                for (const p of data) {
                    const resLoc = await fetch(`/api/localities/${p.localidad}/name`);
                    if (resLoc.ok) {
                        p.nombreLocalidad = await resLoc.text();
                    } else {
                        p.nombreLocalidad = "Desconocido";
                    }
                }

                peluquerias.value = data;
                aplicarFiltros();
            } catch (error) {
                console.error("Error al cargar las peluquerías o localidades:", error);
                errorBusqueda.value = "Error al cargar los datos.";
            } finally {
                loading.value = false;
            }
        };

        const aplicarFiltros = async () => {
            let resultado = [...peluquerias.value];
            errorBusqueda.value = null;

            if (tipoLocalFilter.value) {
                resultado = resultado.filter((p) => p.tipo === tipoLocalFilter.value);
            }

            const valorBusqueda = codigoPostalBusqueda.value.trim().toLowerCase();

            if (valorBusqueda) {
                try {
                    const res = await fetch(`/api/locals/search/${encodeURIComponent(valorBusqueda)}`);
                    if (!res.ok) throw new Error("Localidad no encontrada");

                    const idsLocalidad = await res.json();

                    if (!Array.isArray(idsLocalidad)) {
                        throw new Error("Respuesta de búsqueda inválida");
                    }

                    resultado = resultado.filter((p) => {
                        const coincideLocalidad = idsLocalidad.includes(p.localidad);
                        const coincideNombre = p.nombre.toLowerCase().includes(valorBusqueda);
                        return coincideLocalidad || coincideNombre;
                    });

                    if (resultado.length === 0) {
                        errorBusqueda.value = "No se encontraron peluquerías con ese nombre o localidad.";
                    }

                } catch (error) {
                    errorBusqueda.value = "Error al buscar la localidad.";
                    console.error("Error en búsqueda por localidad:", error);
                    resultado = [];
                }
            }

            ordenarYGuardar(resultado);
        };


        const ordenarYGuardar = (lista) => {
            let listaOrdenada = [...lista];

            if (ordenValoracion.value === "mayor-menor") {
                listaOrdenada.sort((a, b) => b.valoracion - a.valoracion);
            } else if (ordenValoracion.value === "menor-mayor") {
                listaOrdenada.sort((a, b) => a.valoracion - b.valoracion);
            }

            peluqueriasFiltradas.value = listaOrdenada;
            paginaActual.value = 1;
        };

        const resetFilters = () => {
            tipoLocalFilter.value = "";
            ordenValoracion.value = "";
            codigoPostalBusqueda.value = "";
            aplicarFiltros();
        };

        const paginaSiguiente = () => {
            if (paginaActual.value < totalPages.value) paginaActual.value++;
        };

        const paginaAnterior = () => {
            if (paginaActual.value > 1) paginaActual.value--;
        };

        const totalPages = computed(() => Math.ceil(peluqueriasFiltradas.value.length / itemsPorPagina));

        const peluqueriasPaginadas = computed(() => {
            const start = (paginaActual.value - 1) * itemsPorPagina;
            return peluqueriasFiltradas.value.slice(start, start + itemsPorPagina);
        });

        const goToPeluqueria = (id) => {
            router.push({ name: "Local", params: { id } });
        };


        watch([tipoLocalFilter, ordenValoracion, codigoPostalBusqueda], aplicarFiltros, { deep: true });

        onMounted(() => {
            cargarPeluquerias();
        });

        return {
            peluqueriasPaginadas,
            peluqueriasFiltradas,
            loading,
            errorBusqueda,
            tipoLocalFilter,
            ordenValoracion,
            codigoPostalBusqueda,
            resetFilters,
            paginaActual,
            totalPages,
            paginaSiguiente,
            paginaAnterior,
            goToPeluqueria,
        };
    },
};
</script>

<template>
    <div class="locals__space">
        <aside class="flex-column">
            <h2>Filtros</h2>
            <div class="filtros">
                <div class="filtro__seccion">
                    <p>Tipo de local</p>
                    <div class="opcion__filtro">
                        <input type="radio" id="barberias" name="tipo-local" value="BARBERIA"
                            v-model="tipoLocalFilter" />
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
            </div>
            <p class="filtro__reset" @click="resetFilters">Restablecer filtros</p>
        </aside>
        <main class="flex-column">
            <div class="peluquerias__buscador">
                <input type="text" placeholder="Introduce el código postal, localidad o nombre del local"
                    v-model="codigoPostalBusqueda" @input="aplicarFiltros" />
                <img src="/img/utils/search.svg" alt="Buscador de locales por código postal" />
            </div>
            <div v-if="loading">
                <p class="mg-tb-4 loading">Cargando peluquerías...</p>
            </div>
            <div v-else-if="errorBusqueda">
                <p class="mg-tb-4 error-message">{{ errorBusqueda }}</p>
            </div>
            <div v-else class="peluquerias__grid grid">
                <div @click="goToPeluqueria(peluqueria.id)" v-for="peluqueria in peluqueriasPaginadas"
                    :key="peluqueria.id" class="peluqueria__card flex-column">
                    <img :src="peluqueria.imagen || '/img/utils/corteclick.png'"
                        alt="Imagen principal de la peluquería" />

                    <h2 class="card__info flex">
                        {{ peluqueria.nombre }}
                        <div>{{ peluqueria.valoracion || "Sin valoración" }} <span>★</span></div>
                    </h2>
                    <p class="card__description">{{ peluqueria.descripcion }}</p>
                    <div class="card__info flex">
                        <p class="flex">
                            <img class="card__location" src="/img/locals/location.svg" alt="Icono de la ubicación" />
                            {{ peluqueria.direccion }} {{ peluqueria.nombreLocalidad }}
                        </p>
                        <!-- <img class="card__bookmark" @click.stop="peluqueria.favorito = !peluqueria.favorito"
                                src="/img/locals/bookmark.svg" alt="Guardar peluquería como favorita" /> -->
                    </div>
                </div>
            </div>
            <div class="pagination__controls flex-center" v-if="totalPages > 1">
                <button @click="paginaAnterior" :disabled="paginaActual === 1">
                    << </button>
                        <p>Página {{ paginaActual }} de {{ totalPages }}</p>
                        <button @click="paginaSiguiente" :disabled="paginaActual === totalPages">>></button>
            </div>
        </main>
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.locals__space {
    display: grid;
    grid-template-columns: 1fr 5fr;

    aside {
        border-right: 2px solid map-get($colores, "gris_claro");
        padding: 1.5rem;
        align-items: center;

        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }

        .filtros {
            .filtro__seccion {
                margin-bottom: 2rem;
                width: 100%;

                p {
                    margin-bottom: 5px;
                }

                .opcion__filtro {
                    margin-bottom: 5px;
                    white-space: nowrap;

                    label {
                        @include fuente("parrafo_max");
                        margin-left: 5px;
                        margin-bottom: 5px;

                        span {
                            color: map-get($colores, "naranja");
                        }
                    }
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
        min-height: 85vh;

        .peluquerias__buscador {
            position: relative;
            width: 60%;

            input {
                @include fuente("parrafo");
                border: 2px solid map-get($colores, "gris_claro");
                border-radius: 5px;
                width: 100%;
                padding: 5px 2rem;

                &:focus {
                    border: 2px solid map-get($colores, "gris_oscuro");
                    outline: none;
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
            margin: 20px 0;

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
                    cursor: pointer;
                }

                strong {
                    font-weight: bold;
                }

                img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 8px;
                }

                .card__description {
                    height: 48px;
                    overflow: hidden;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    line-clamp: 3;
                    -webkit-box-orient: vertical;
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

                    span {
                        color: map-get($colores, "naranja");
                    }
                }
            }
        }

        .pagination__controls {
            @include fuente("parrafo");
            padding: 1rem;
            gap: 1rem;

            button {
                border: 2px solid map-get($colores, "gris_claro");
                background-color: transparent;
                border-radius: 5px;
                padding: 2.5px 10px;
                cursor: pointer;
            }

            button[disabled] {
                cursor: auto;
            }
        }
    }

    .error-message {
        color: map-get($colores, "naranja");
        margin-top: 10px;
    }

    .local-details {
        background-color: red;
        padding: 3rem 4rem;
        text-align: center;
        min-height: 100vh;
        /* Ensure it takes full height for visibility */

        .back-button {
            @include fuente("parrafo");
            background-color: map-get($colores, "gris_claro");
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            margin-bottom: 1rem;

            &:hover {
                background-color: map-get($colores, "naranja");
                color: white;
            }
        }
    }

}

@media (max-width: 1268px) {
    .locals__space {
        main {
            .peluquerias__buscador {
                width: 100%;
            }
        }
    }
}

@media (max-width: 1024px) {
    .locals__space {
        display: flex;
        flex-direction: column;
        gap: 2rem;

        aside {
            border-bottom: 2px solid map-get($colores, "gris_claro");
            padding-bottom: 2rem;
            align-items: center;

            .filtros {
                display: flex;
                gap: 2rem;

                .filtro__seccion {
                    margin-bottom: 1rem;
                }
            }
        }

        main {
            padding: 2rem 1.5rem;
        }
    }
}

@media (max-width: 580px) {
    .locals__space {
        aside {
            .filtros {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    }
}
</style>