<script>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { DateTime } from "luxon";
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
    const citas = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const isOpen = ref(false);

    // Variables for week handling
    const currentMonday = ref(null);
    const mondayDay = ref(0);
    const fridayDay = ref(0);
    const occupiedSlots = ref({});
    const currentWeekMonday = ref(null);

    // Helper to get DateTime in UTC+2 Madrid
    const toMadridDate = (date) => {
      return date instanceof DateTime
        ? date.setZone('Europe/Madrid')
        : DateTime.fromJSDate(date).setZone('Europe/Madrid');
    };

    // Format date to YYYY-MM-DD in UTC+2 Madrid
    const formatDateToString = (date) => {
      return toMadridDate(date).toFormat('yyyy-MM-dd');
    };

    // Function to get the current week's Monday in UTC+2
    const getCurrentWeekMonday = () => {
      const today = toMadridDate(DateTime.now());
      const dayOfWeek = today.weekday; // 1 (Monday) to 7 (Sunday)
      const daysToMonday = dayOfWeek === 7 ? 1 : dayOfWeek - 1;
      return today.minus({ days: daysToMonday }).startOf('day');
    };

    // Function to check if it's after 14:00 on Friday in UTC+2
    const isAfterFriday2PM = () => {
      const now = toMadridDate(DateTime.now());
      const dayOfWeek = now.weekday; // 1 (Monday) to 7 (Sunday)
      const hour = now.hour + now.minute / 60;
      return (
        (dayOfWeek === 5 && hour >= 14) || // Friday after 14:00
        dayOfWeek === 6 || // Saturday
        dayOfWeek === 7 // Sunday
      );
    };

    // Function to update week days
    const updateWeekDays = () => {
      let date = toMadridDate(currentMonday.value);
      const dayOfWeek = date.weekday; // 1 (Monday) to 7 (Sunday)
      const daysToMonday = dayOfWeek === 7 ? 1 : dayOfWeek - 1;
      date = date.minus({ days: daysToMonday }).startOf('day');
      currentMonday.value = date;

      mondayDay.value = date.day;
      const friday = date.plus({ days: 4 });
      fridayDay.value = friday.day;

      fetchCitas();
    };

    // Navigate to previous week
    const goToPreviousWeek = () => {
      const newMonday = toMadridDate(currentMonday.value).minus({ weeks: 1 });
      if (newMonday >= currentWeekMonday.value) {
        currentMonday.value = newMonday;
        updateWeekDays();
      }
    };

    // Navigate to next week
    const goToNextWeek = () => {
      const newMonday = toMadridDate(currentMonday.value).plus({ weeks: 1 });
      currentMonday.value = newMonday;
      updateWeekDays();
    };

    // Check salon status
    const checkStatus = () => {
      const now = toMadridDate(DateTime.now());
      const hour = now.hour + now.minute / 60;
      isOpen.value = hour >= 9 && hour < 14;
    };

    const fetchPeluqueria = async () => {
      try {
        const res = await fetch(`/api/locals/${peluqueriaId}`);
        if (!res.ok) throw new Error("Error al cargar la peluquería");
        const data = await res.json();
        peluqueria.value = data;

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
        servicios.value = data || [];
      } catch (err) {
        error.value = error.value || "Error al cargar los servicios.";
        console.error(err);
      }
    };

    const fetchCitas = async () => {
      try {
        const res = await fetch(`/api/appointments/${peluqueriaId}`);
        if (!res.ok) throw new Error("Error al cargar las citas");
        const data = await res.json();
        citas.value = data || [];
        console.log('Fetched citas:', citas.value);
        updateOccupiedSlots();
      } catch (err) {
        error.value = error.value || "Error al cargar las citas.";
        console.error(err);
      }
    };

    // Calculate occupied time slots
    const updateOccupiedSlots = () => {
      occupiedSlots.value = {};
      const monday = toMadridDate(currentMonday.value);
      const weekDates = Array.from({ length: 5 }, (_, i) =>
        formatDateToString(monday.plus({ days: i }))
      );

      citas.value.forEach(cita => {
        if (weekDates.includes(cita.fecha)) {
          const startTime = cita.hora_inicio;
          const startTimeFormatted = formatTime(parseTime(startTime));
          const slotKey = `${cita.fecha}:${startTimeFormatted}`;
          occupiedSlots.value[slotKey] = true;
        }
      });
      console.log('Week dates:', weekDates);
      console.log('Occupied slots:', occupiedSlots.value);
    };

    const parseTime = (timeStr) => {
      const [hours, minutes] = timeStr.split(':').slice(0, 2).map(Number);
      return hours * 60 + minutes;
    };

    const formatTime = (minutes) => {
      const hours = Math.floor(minutes / 60);
      const mins = minutes % 60;
      return `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
    };

    const isSlotOccupied = (date, time) => {
      // Ensure time is in HH:mm format with leading zeros
      const normalizedTime = time.match(/^\d{1,2}:\d{2}$/)
        ? time.padStart(5, '0')
        : time;
      const slotKey = `${date}:${normalizedTime}`;
      console.log('Checking slot:', slotKey, 'Occupied:', !!occupiedSlots.value[slotKey]); // Debug
      return !!occupiedSlots.value[slotKey];
    };

    const getDateForDay = (dayOffset) => {
      return formatDateToString(toMadridDate(currentMonday.value).plus({ days: dayOffset }));
    };

    const getCitasPorDia = (dia) => {
      return citas.value.filter((cita) => {
        const citaDate = DateTime.fromFormat(cita.fecha, 'yyyy-MM-dd', { zone: 'Europe/Madrid' });
        const inputDate = DateTime.fromFormat(dia.fecha, 'yyyy-MM-dd', { zone: 'Europe/Madrid' });
        return citaDate.hasSame(inputDate, 'day');
      });
    };

    const isPreviousWeekDisabled = () => {
      const newMonday = toMadridDate(currentMonday.value).minus({ weeks: 1 });
      return newMonday < currentWeekMonday.value;
    };

    onMounted(async () => {
      loading.value = true;
      currentWeekMonday.value = getCurrentWeekMonday();
      currentMonday.value = currentWeekMonday.value;

      // Adjust to next Monday if after Friday 14:00
      if (isAfterFriday2PM()) {
        currentMonday.value = currentMonday.value.plus({ weeks: 1 });
      }

      updateWeekDays();
      await Promise.all([fetchPeluqueria(), fetchServicios()]);
      loading.value = false;
      checkStatus();
    });

    return {
      peluqueria,
      servicios,
      citas,
      loading,
      error,
      peluqueriaId,
      isOpen,
      mondayDay,
      fridayDay,
      goToPreviousWeek,
      goToNextWeek,
      isSlotOccupied,
      getDateForDay,
      isPreviousWeekDisabled,
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
          <p class="schedule__week flex-center">
            <img src="/img/utils/arrow_back.svg" alt="Ir a semana anterior" @click="goToPreviousWeek"
              :class="{ 'disabled': isPreviousWeekDisabled() }" />
            Lun {{ mondayDay }} - Vie {{ fridayDay }}
            <img src="/img/utils/arrow_forward.svg" alt="Ir a semana siguiente" @click="goToNextWeek" />
          </p>
          <table>
            <tr>
              <th>Lunes</th>
              <th>Martes</th>
              <th>Miércoles</th>
              <th>Jueves</th>
              <th>Viernes</th>
            </tr>
            <tr
              v-for="time in ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00']"
              :key="time">
              <td v-for="day in [0, 1, 2, 3, 4]" :key="day" :class="{
                'hora__ocupada': isSlotOccupied(getDateForDay(day), time),
                'hora__disponible': !isSlotOccupied(getDateForDay(day), time),
                'debug-monday-930': day === 0 && time === '09:30'
              }">
                {{ time }}
              </td>
            </tr>
          </table>
          <div class="schedule__info flex-column">
            <div><span class="square square--green"></span> Huecos disponibles</div>
            <div><span class="square square--red"></span> Huecos ocupados</div>
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

        &.disabled {
          opacity: 0.5;
          cursor: auto;
          transform: none;
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
          background-color: #f0f0f0;
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

      .square--red {
        background-color: map-get($colores, "rojo");
      }
    }

    .hora__disponible {
      background-color: map-get($colores, "verde");
      color: map-get($colores, "blanco");
    }

    .hora__ocupada {
      background-color: map-get($colores, "rojo");
      color: map-get($colores, "blanco");
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