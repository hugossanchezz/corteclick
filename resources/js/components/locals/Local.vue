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
    const servicios = ref([]); // Servicios de la peluquería
    const loading = ref(true);
    const error = ref(null);
    const isOpen = ref(false); // Local abierto o cerrado

    const checkStatus = () => {
      const now = new Date();
      const hours = now.getUTCHours() + 2; // UTC+2 Madrid
      const minutes = now.getUTCMinutes();
      const currentTime = hours + minutes / 60;

      // Comprobar si la peluquería está abierta de 9:00 and 14:00 (14:00 = 14.00)
      isOpen.value = currentTime >= 9 && currentTime < 14;
    };

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
      checkStatus();
    });

    return {
      peluqueria,
      servicios,
      loading,
      error,
      peluqueriaId,
      isOpen,
    };
  },
};
</script>

<template>
  <RequireAuth>
    <div class="space__container">
      <div class="btn-back__container">
        <router-link to="/locals">
          <button class="btn btn-back">
            <img src="/img/utils/arrow_back.svg" alt="Volver al catálogo de locales"> Volver
          </button>
        </router-link>
      </div>
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

          <!-- Estado de la peluquería abierto o cerrado -->
          <div class="local__status">
            <div v-if="isOpen" class="status status--open">ABIERTO</div>
            <div v-else class="status status--closed">CERRADO</div>
          </div>

          <div class="type__value flex-column">
            <p>{{ peluqueria.tipo }}</p>
            <strong class="flex">{{ peluqueria.valoracion || "Sin valoración" }} <span class="star">★</span> </strong>
          </div>
        </div>

        <div class="local__address flex">
          <img class="card__location" src="/img/locals/location.svg" alt="Ubicación" />
          {{ peluqueria.direccion }} {{ peluqueria.nombreLocalidad }}
        </div>

        <div class="local__description">
          <p>{{ peluqueria.descripcion }}</p>
        </div>

        <div class="local__service flex-center">
          <select name="servicios" id="servicios">
            <option value="" disabled selected>Selecciona un servicio</option>
            <option v-for="servicio in servicios" :key="servicio.id" :value="servicio.id">
              {{ servicio.nombre }} - {{ servicio.precio }}€ ({{ servicio.duracion }} min)
            </option>
          </select>
        </div>

        <div class="local__schedule">
          <p class="flex-center">
            <img src="/img/utils/arrow_back.svg" alt="Ir a semana anterior"> Lun 2 - Vie 6 <img
              src="/img/utils/arrow_forward.svg" alt="Ir a semana siguiente">
          </p>
          <table>
            <tr>
              <th>Lunes</th>
              <th>Martes</th>
              <th>Miércoles</th>
              <th>Jueves</th>
              <th>Viernes</th>
            </tr>
            <tr>
              <td class="green-cell">9:00</td>
              <td class="green-cell">9:00</td>
              <td class="green-cell">9:00</td>
              <td class="green-cell">9:00</td>
              <td class="green-cell">9:00</td>
            </tr>
            <tr>
              <td class="green-cell">9:30</td>
              <td class="green-cell">9:30</td>
              <td class="green-cell">9:30</td>
              <td class="green-cell">9:30</td>
              <td class="green-cell">9:30</td>
            </tr>
            <tr>
              <td class="green-cell">10:00</td>
              <td class="green-cell">10:00</td>
              <td class="green-cell">10:00</td>
              <td class="green-cell">10:00</td>
              <td class="green-cell">10:00</td>
            </tr>
            <tr>
              <td class="green-cell">10:30</td>
              <td class="green-cell">10:30</td>
              <td class="green-cell">10:30</td>
              <td class="green-cell">10:30</td>
              <td class="green-cell">10:30</td>
            </tr>
            <tr>
              <td class="green-cell">11:00</td>
              <td class="green-cell">11:00</td>
              <td class="green-cell">11:00</td>
              <td class="green-cell">11:00</td>
              <td class="green-cell">11:00</td>
            </tr>
            <tr>
              <td class="green-cell">11:30</td>
              <td class="green-cell">11:30</td>
              <td class="green-cell">11:30</td>
              <td class="green-cell">11:30</td>
              <td class="green-cell">11:30</td>
            </tr>
            <tr>
              <td class="green-cell">12:00</td>
              <td class="green-cell">12:00</td>
              <td class="green-cell">12:00</td>
              <td class="green-cell">12:00</td>
              <td class="green-cell">12:00</td>
            </tr>
            <tr>
              <td class="green-cell">12:30</td>
              <td class="green-cell">12:30</td>
              <td class="green-cell">12:30</td>
              <td class="green-cell">12:30</td>
              <td class="green-cell">12:30</td>
            </tr>
            <tr>
              <td class="green-cell">13:00</td>
              <td class="green-cell">13:00</td>
              <td class="green-cell">13:00</td>
              <td class="green-cell">13:00</td>
              <td class="green-cell">13:00</td>
            </tr>
            <tr>
              <td class="green-cell">13:30</td>
              <td class="green-cell">13:30</td>
              <td class="green-cell">13:30</td>
              <td class="green-cell">13:30</td>
              <td class="green-cell">13:30</td>
            </tr>
            <tr>
              <td class="green-cell">14:00</td>
              <td class="green-cell">14:00</td>
              <td class="green-cell">14:00</td>
              <td class="green-cell">14:00</td>
              <td class="green-cell">14:00</td>
            </tr>
          </table>
          <div class="schedule__info flex-column">
            <div><span class="square square--green"></span> Huecos disponibles</div>
          </div>
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
  position: absolute;
  z-index: 10;
  left: -12rem;

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
  border: 2px solid map-get($colores, "gris_claro");
  width: 70%;
  height: 85%;
  margin: 3rem auto;
  border-radius: 10px;
  position: relative;

  .loading {
    width: 100%;
    padding: 2rem 0;
    text-align: center;

    p {
      margin: 0 auto;
    }
  }

  .local {
    height: 100%;
    grid-template-columns: 1.5fr 1.6fr;
    grid-template-rows: repeat(4, auto) auto auto;
    grid-column-gap: 0px;
    grid-row-gap: 0px;

    &>div:not(:first-child) {
      padding: 5px 10px;
    }
  }

  .local__image {
    grid-area: 1 / 1 / 4 / 2;
    height: 100%;

    img {
      border-top-left-radius: 8px;
    }
  }

  .local__name {
    grid-area: 4 / 1 / 5 / 2;
    justify-content: space-between;
    align-items: center;

    .type__value {
      align-items: center;
      justify-content: space-between;

      strong {
        font-size: 20px !important;
        align-items: end;
        gap: 5px;
      }

      .star {
        color: map-get($colores, "naranja");
        font-size: 20px !important;
      }

      p {
        margin: 2.5px 0;
      }
    }

    .status {
      color: map-get($colores, 'blanco');
      border-radius: 5px;
      padding: 5px 10px;
      font-weight: bold;
    }

    .status--open {
      background-color: map-get($colores, 'verde');
    }

    .status--closed {
      background-color: map-get($colores, 'rojo');
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
      padding: 15px 10px;
      border-radius: 10px;
    }
  }

  .local__service {
    grid-area: 1 / 2 / 2 / 3;

    select {
      padding: 10px;
      border: 2px solid map-get($colores, "gris_claro");
      border-radius: 10px;

      &:focus {
        outline: none;
        border: 2px solid map-get($colores, "naranja");
      }
    }
  }

  .local__schedule {
    grid-area: 2 / 2 / 6 / 3;

    p {
      align-items: center;
      margin-bottom: 1rem;

      img {
        width: 14px;
        height: 14px;
        margin: 0 0.5rem;
        cursor: pointer;

        &:hover {
          transform: scale(1.2);
        }
      }
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border: 2px solid map-get($colores, "gris_claro");
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
      table-layout: fixed;

      th {
        background-color: map-get($colores, 'azul_oscuro');
        color: map-get($colores, 'blanco');
      }

      th,
      td {
        padding: 5px;
        text-align: center;
      }

      td {
        border: 1px solid map-get($colores, "gris_claro");

        &:hover {
          font-size: 1.2em;
          /* Puedes ajustar este valor: 1.2em significa un 20% más grande */
          background-color: #f0f0f0;
          /* Opcional: cambia el fondo para un feedback visual */
          cursor: pointer;
        }
      }
    }

    .schedule__info {
      padding: 10px;

      .square {
        display: inline-block;
        width: 10px;
        height: 10px;
        margin-right: 5px;
      }

      .square--green {
        background-color: map-get($colores, "verde");
      }
    }
  }

  .local__button {
    grid-area: 6 / 2 / 7 / 3;
  }

  .local__image {
    width: 100%;

    img {
      width: 100%;
      height: auto;
    }
  }

  .error {
    color: map-get($colores, "naranja");
  }
}
</style>