<script>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import RequireAuth from "@/js/components/auth/RequireAuth.vue";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";

export default {
  name: "Local",

  components: { RequireAuth, PrimaryButton },
  setup() {
    const route = useRoute();
    const peluqueriaId = route.params.id;

    const peluqueria = ref(null);
    const servicios = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const fetchPeluqueria = async () => {
      try {
        const res = await fetch(`/api/locals/${peluqueriaId}`);
        if (!res.ok) throw new Error("Error al cargar la peluquería");
        const data = await res.json();
        peluqueria.value = data;

        // Cargar el nombre de la localidad
        const resLoc = await fetch(`/api/localities/${data.localidad}/name`);
        if (resLoc.ok) {
          peluqueria.value.nombreLocalidad = await resLoc.text();
        } else {
          peluqueria.value.nombreLocalidad = "Desconocido";
        }
      } catch (err) {
        error.value = "Error al cargar los datos de la peluquería.";
        console.error(err);
      }
    };

    const fetchServicios = async () => {
      try {
        const res = await fetch(`/api/locals/${peluqueriaId}/services`);
        if (!res.ok) throw new Error("Error al cargar los servicios");
        const data = await res.json();
        servicios.value = data || []; // Asignamos el array directamente
      } catch (err) {
        error.value = error.value || "Error al cargar los servicios.";
        console.error(err);
      }
    };


    onMounted(async () => {
      loading.value = true;
      await Promise.all([fetchPeluqueria(), fetchServicios()]);
      loading.value = false;
    });

    return {
      peluqueria,
      servicios,
      loading,
      error,
      peluqueriaId,
    };
  },
};
</script>

<template>
  <RequireAuth>
    <div class="btn-back__container">
      <router-link to="/locals">
        <button class="btn btn-back">
          <img src="/img/utils/arrow_back.svg" alt="Volver al catálogo de locales"> Volver
        </button>
      </router-link>
    </div>
    <div class="space__container">
      <div v-if="loading" class="loading">
        <p>Cargando datos...</p>
      </div>
      <div v-else-if="error">
        <p class="error">{{ error }}</p>
      </div>
      <div v-else-if="peluqueria" class="local grid">
        <div class="local__image">
          <img src="/img/utils/corteclick.png" alt="">
        </div>

        <div class="local__name flex">
          <h1>{{ peluqueria.nombre }}</h1>
          <div class="type__value flex-column">
            <p>{{ peluqueria.tipo }}</p>
            <strong>{{ peluqueria.valoracion || "Sin valoración" }} ★ </strong>
          </div>
        </div>

        <div class="local__address flex">
          <img class="card__location" src="/img/locals/location.svg" alt="Ubicación" />
          {{ peluqueria.direccion }} {{ peluqueria.nombreLocalidad }}
        </div>

        <div class="local__description">
          <p>{{ peluqueria.descripcion }}</p>
        </div>

        <div class="local__appointment">
          <select name="servicios" id="servicios">
            <option value="" disabled selected>Selecciona un servicio</option>
            <option v-for="servicio in servicios" :key="servicio.id" :value="servicio.id">
              {{ servicio.nombre }} - {{ servicio.precio }}€ ({{ servicio.duracion }} min)
            </option>
          </select>
        </div>

        <div class="local__schedule">
          <p>Horarios: </p>
        </div>

        <div class="local__button flex-center">
          <PrimaryButton label="Pedir cita" />
        </div>
      </div>

      <div v-else>
        <p>No se encontraron datos para esta peluquería.</p>
      </div>
    </div>
  </RequireAuth>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;
@use "@/sass/common" as *;

.btn-back__container {
  padding: 2rem 0 0 2rem;
  margin-bottom: 1rem;
  a {
    text-decoration: none;
  }

  button {
    display: flex;
    align-items: center;

    img {
      width: 14px;
      height: 14px;
      margin-right: 0.5rem;
    }
  }
}

.space__container {
  width: 70%;
  height: auto;
  margin: 0 auto;

  .loading {
    width: 100%;
    text-align: center;

    p {
      margin: 0 auto;
    }
  }

  .local {
    height: 100%;
    grid-template-columns: 1fr 1.5fr;
    grid-template-rows: repeat(4, auto) auto auto;
    grid-column-gap: 0px;
    grid-row-gap: 0px;

    &>div:not(:first-child) {
      padding: 5px 10px;
    }
  }

  .local__image {
    grid-area: 1 / 1 / 4 / 2;
  }

  .local__name {
    grid-area: 4 / 1 / 5 / 2;
    justify-content: space-between;
    align-items: center;

    .type__value {
      align-items: center;
      justify-content: space-between;

      strong {
        font-size: 16px !important;
      }

      p {
        margin: 2.5px 0;
      }
    }
  }

  .local__address {
    grid-area: 5 / 1 / 6 / 2;

    align-items: center;
    color: map-get($colores, 'azul_oscuro');
  }

  .local__description {
    grid-area: 6 / 1 / 7 / 2;

    p {
      border: 1px solid map-get($colores, "gris_claro");
      padding: 10px 5px;
      border-radius: 10px;
    }
  }

  .local__appointment {
    grid-area: 1 / 2 / 2 / 3;
  }

  .local__schedule {
    grid-area: 2 / 2 / 6 / 3;
  }

  .local__button {
    grid-area: 6 / 2 / 7 / 3;
  }

  h2 {
    margin-bottom: 1rem;
    color: map-get($colores, "negro");
  }

  .local__image {
    width: 100%;

    img {
      width: 100%;
      height: auto;
    }
  }

  p {
    margin: 0.5rem 0;
    color: map-get($colores, "gris_oscuro");
  }

  .error {
    color: map-get($colores, "naranja");
  }
}
</style>