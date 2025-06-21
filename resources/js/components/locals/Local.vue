<script>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import { DateTime } from "luxon";
import axios from "axios";
import RequireAuth from "@/js/components/auth/RequireAuth.vue";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
  name: "Local",
  components: { RequireAuth, PrimaryButton, ModalConfirm },
  setup() {
    const route = useRoute();
    const peluqueriaId = route.params.id;

    const userId = ref(null);
    const peluqueria = ref(null);
    const servicios = ref([]);
    const citas = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const isOpen = ref(false);
    const selectedServiceDuration = ref(null);
    const selectedServiceId = ref("");
    const mensaje = ref("Selecciona un servicio para ver las horas disponibles");
    const selectedSlot = ref(null);

    const currentMonday = ref(null);
    const mondayDay = ref(0);
    const fridayDay = ref(0);
    const occupiedSlots = ref({});
    const currentWeekMonday = ref(null);

    // Carrusel
    const imagenesCarrusel = ref([]);
    const indiceActual = ref(0);

    // Modal
    const showModal = ref(false);
    const modalTitle = ref("");
    const modalMessage = ref("");

    const toMadridDate = (date) => {
      return date instanceof DateTime
        ? date.setZone('Europe/Madrid')
        : DateTime.fromJSDate(date).setZone('Europe/Madrid');
    };

    const formatDateToString = (date) => {
      return toMadridDate(date).toFormat('yyyy-MM-dd');
    };

    const getCurrentWeekMonday = () => {
      const now = toMadridDate(DateTime.now());
      const dayOfWeek = now.weekday;
      const hour = now.hour + now.minute / 60;

      let monday;
      if ((dayOfWeek === 5 && hour >= 14) || dayOfWeek === 6 || dayOfWeek === 7) {
        // Viernes después de las 14h, sábado o domingo → devolver el lunes siguiente
        const daysToNextMonday = 8 - dayOfWeek;
        monday = now.plus({ days: daysToNextMonday }).startOf('day');
      } else {
        // Lunes a viernes antes de las 14h → devolver el lunes de esta semana
        const daysToMonday = dayOfWeek === 7 ? 1 : dayOfWeek - 1;
        monday = now.minus({ days: daysToMonday }).startOf('day');
      }

      return monday;
    };

    const updateWeekDays = () => {
      let date = toMadridDate(currentMonday.value);
      const dayOfWeek = date.weekday;
      const daysToMonday = dayOfWeek === 7 ? 1 : dayOfWeek - 1;
      date = date.minus({ days: daysToMonday }).startOf('day');
      currentMonday.value = date;

      mondayDay.value = date.day;
      const friday = date.plus({ days: 4 });
      fridayDay.value = friday.day;

      fetchCitas();
    };

    const goToPreviousWeek = () => {
      const newMonday = toMadridDate(currentMonday.value).minus({ weeks: 1 });
      if (newMonday >= currentWeekMonday.value) {
        currentMonday.value = newMonday;
        updateWeekDays();
      }
    };

    const goToNextWeek = () => {
      const newMonday = toMadridDate(currentMonday.value).plus({ weeks: 1 });
      currentMonday.value = newMonday;
      updateWeekDays();
    };

    const checkStatus = () => {
      const now = toMadridDate(DateTime.now());
      const hour = now.hour + now.minute / 60;
      const day = now.weekday; // 1 = lunes, 7 = domingo

      // Solo abre de lunes a viernes entre 9:00 y 14:00
      isOpen.value = day >= 1 && day <= 5 && hour >= 9 && hour < 14;
    };

    const fetchPeluqueria = async () => {
      try {
        const res = await fetch(`/api/locals/${peluqueriaId}`);
        if (!res.ok) throw new Error("Error al cargar la peluquería");
        const data = await res.json();
        peluqueria.value = data;

        const resLoc = await fetch(`/api/localities/${data.localidad}/name`);
        peluqueria.value.nombreLocalidad = resLoc.ok
          ? await resLoc.text()
          : "Desconocido";
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
        updateOccupiedSlots();
      } catch (err) {
        error.value = error.value || "Error al cargar las citas.";
        console.error(err);
      }
    };

    const updateOccupiedSlots = () => {
      occupiedSlots.value = {};
      const monday = toMadridDate(currentMonday.value);
      const weekDates = Array.from({ length: 5 }, (_, i) =>
        formatDateToString(monday.plus({ days: i }))
      );

      citas.value.forEach(cita => {
        const start = parseTime(cita.hora_inicio); // en minutos
        const end = parseTime(cita.hora_fin);       // en minutos
        const fecha = cita.fecha;

        if (!weekDates.includes(fecha)) return;

        // Recorre de 30 en 30 minutos desde inicio hasta justo antes de fin
        for (let time = start; time < end; time += 30) {
          const slotKey = `${fecha}:${formatTime(time)}`;
          occupiedSlots.value[slotKey] = true;
        }
      });
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
      const normalizedTime = time.match(/^\d{1,2}:\d{2}$/)
        ? time.padStart(5, '0')
        : time;
      const slotKey = `${date}:${normalizedTime}`;
      return !!occupiedSlots.value[slotKey];
    };

    const isSlotAvailable = (date, time) => {
      if (!selectedServiceDuration.value) return false;

      const startMinutes = parseTime(time);
      const neededSlots = selectedServiceDuration.value / 30;
      const endMinutes = startMinutes + selectedServiceDuration.value;

      const cierreEnMinutos = 14 * 60 + 30; // 14:30 → 870 min

      // Si termina después de 14:30, no está permitido
      if (endMinutes > cierreEnMinutos) return false;

      for (let i = 0; i < neededSlots; i++) {
        const nextTime = formatTime(startMinutes + i * 30);
        if (isSlotOccupied(date, nextTime)) return false;
      }

      return true;
    };

    const getCellClass = (date, time) => {
      const classes = [];

      if (isSlotOccupied(date, time)) {
        classes.push('hora__ocupada');
      } else if (isSlotAvailable(date, time)) {
        classes.push('hora__disponible');
      }

      if (selectedServiceDuration.value && selectedSlot.value?.date === date) {
        const startMinutes = parseTime(selectedSlot.value.time);
        const neededSlots = selectedServiceDuration.value / 30;
        const selectedTimes = Array.from({ length: neededSlots }, (_, i) =>
          formatTime(startMinutes + i * 30)
        );

        const index = selectedTimes.indexOf(time);
        if (index !== -1) {
          classes.push('hora__seleccionada');
          if (index === 0) classes.push('hora__seleccionada--start');
          else if (index === neededSlots - 1) classes.push('hora__seleccionada--end');
          else classes.push('hora__seleccionada--middle');
        }
      }

      return classes.join(' ');
    };

    const handleServiceChange = (event) => {
      const selected = servicios.value.find(s => s.id === parseInt(event.target.value));
      selectedServiceId.value = selected?.id || "";
      selectedServiceDuration.value = selected?.duracion || null;
      mensaje.value = null;
      selectedSlot.value = null;
    };

    const selectSlot = (date, time) => {
      if (!selectedServiceDuration.value) return;
      if (isSlotAvailable(date, time)) {
        selectedSlot.value = { date, time };
      }
    };

    const getDateForDay = (dayOffset) => {
      return formatDateToString(toMadridDate(currentMonday.value).plus({ days: dayOffset }));
    };

    const isPreviousWeekDisabled = () => {
      const newMonday = toMadridDate(currentMonday.value).minus({ weeks: 1 });
      return newMonday < currentWeekMonday.value;
    };

    // Lógica de mandar la cita
    const canSubmit = computed(() => {
      return selectedServiceId.value && selectedSlot.value;
    });

    const pedirCita = async () => {
      const start = parseTime(selectedSlot.value.time);
      const end = start + selectedServiceDuration.value;
      const hora_inicio = formatTime(start);
      const hora_fin = formatTime(end);

      const citaPayload = {
        id_usuario: userId.value,
        id_peluqueria: peluqueriaId,
        id_servicio: selectedServiceId.value,
        fecha: selectedSlot.value.date,
        hora_inicio,
        hora_fin,
        estado: 'CONFIRMADA',
        valoracion: null,
        puntuacion: null,
        respuesta: null
      };

      try {
        const response = await fetch('/api/appointments/new', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(citaPayload)
        });

        if (!response.ok) {
          const errorData = await response.json();
          modalTitle.value = "Error al crear la cita";
          modalMessage.value = errorData?.message || "No se pudo crear la cita. Verifica los datos.";
          showModal.value = true;
          return;
        }

        const result = await response.json();
        modalTitle.value = "Cita registrada";
        modalMessage.value = result.mensaje || "La cita se ha creado correctamente.";
        showModal.value = true;

        // Opcional: limpiar selección
        selectedSlot.value = null;
        selectedServiceId.value = "";
        selectedServiceDuration.value = null;
        mensaje.value = "Selecciona un servicio para ver las horas disponibles";

        // Refrescar citas para mostrar nuevas ocupadas
        await fetchCitas();
      } catch (err) {
        console.error("Error en la solicitud:", err);
        alert("Ocurrió un error al crear la cita.");
      }
    };

    // Carrusel
    const cargarImagenesCarrusel = async () => {
      try {
        const responseFotos = await axios.get(`/api/local/${peluqueria.value.id}/photos`);
        const imagenes = [];

        // Solo agregar la imagen principal si existe
        if (peluqueria.value.imagen) {
          imagenes.push(`data:image/jpeg;base64,${peluqueria.value.imagen}`);
        }

        // Añadir las secundarias si existen
        if (Array.isArray(responseFotos.data)) {
          imagenes.push(...responseFotos.data.map(f => `data:image/jpeg;base64,${f.imagen}`));
        }

        // Si no hay imágenes, usar una por defecto
        if (imagenes.length === 0) {
          imagenes.push('/img/utils/corteclick.png');
        }

        imagenesCarrusel.value = imagenes;
        indiceActual.value = 0;
      } catch (error) {
        console.error("❌ Error al cargar imágenes del carrusel:", error);
        imagenesCarrusel.value = ['/img/utils/corteclick.png'];
      }
    };


    const siguienteImagen = () => {
      if (imagenesCarrusel.value.length === 0) return;
      indiceActual.value = (indiceActual.value + 1) % imagenesCarrusel.value.length;
    };

    const anteriorImagen = () => {
      if (imagenesCarrusel.value.length === 0) return;
      indiceActual.value =
        (indiceActual.value - 1 + imagenesCarrusel.value.length) % imagenesCarrusel.value.length;
    };

    onMounted(async () => {
      const storedUser = sessionStorage.getItem("user");
      if (storedUser) {
        const user = JSON.parse(storedUser);
        userId.value = user.id;
      }

      loading.value = true;
      currentWeekMonday.value = getCurrentWeekMonday();
      currentMonday.value = currentWeekMonday.value;

      try {
        // Esperar a que cargue la peluquería
        await fetchPeluqueria();

        // Cargar el carrusel solo si la peluquería está definida
        if (peluqueria.value && peluqueria.value.id) {
          await cargarImagenesCarrusel();
        }

        await fetchServicios();
        updateWeekDays();
        checkStatus();
      } catch (e) {
        console.error("❌ Error en carga inicial:", e);
        error.value = "No se pudo cargar la información del local.";
      }

      loading.value = false;
    });


    return {
      peluqueria,
      servicios,
      citas,
      loading,
      error,
      isOpen,
      mondayDay,
      fridayDay,
      goToPreviousWeek,
      goToNextWeek,
      getDateForDay,
      isPreviousWeekDisabled,
      handleServiceChange,
      selectedServiceId,
      mensaje,
      getCellClass,
      selectSlot,
      userId,
      canSubmit,
      pedirCita,
      selectedSlot,
      selectedServiceId,
      selectedServiceDuration,

      // Carrusel
      imagenesCarrusel,
      indiceActual,
      cargarImagenesCarrusel,
      siguienteImagen,
      anteriorImagen,

      // Modal
      showModal,
      modalTitle,
      modalMessage,
    };
  }
};
</script>

<template>
  <RequireAuth>
    <div class="space__container">
      <div class="btn-back__container">
        <router-link to="/locals">
          <button class="btn btn-back">
            <img src="/img/utils/arrow_back.svg" alt="Volver al catálogo de locales" /> Volver
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
          <img :src="imagenesCarrusel[indiceActual]" alt="Imagen del local" class="main_image" />

          <button class="carrusel-btn izquierda" @click="anteriorImagen" :disabled="imagenesCarrusel.length <= 1">
            <img src="/img/utils/arrow_back_white.svg" alt="">
          </button>
          <button class="carrusel-btn derecha" @click="siguienteImagen" :disabled="imagenesCarrusel.length <= 1">
            <img src="/img/utils/arrow_forward_white.svg" alt="">
          </button>
        </div>

        <div class="local__name flex">
          <h1>{{ peluqueria.nombre }}</h1>
          <div class="local__status">
            <div v-if="isOpen" class="status status--open">ABIERTO</div>
            <div v-else class="status status--closed">CERRADO</div>
          </div>
          <div class="type__value flex-column">
            <p>{{ peluqueria.tipo }}</p>
            <strong class="flex">{{ peluqueria.valoracion || "Sin valoración" }} <span class="star">★</span></strong>
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
          <select name="servicios" id="servicios" @change="handleServiceChange" v-model="selectedServiceId">
            <option value="" disabled>Selecciona un servicio</option>
            <option v-for="servicio in servicios" :key="servicio.id" :value="servicio.id">
              {{ servicio.nombre }} - {{ servicio.precio }}€ ({{ servicio.duracion }} min)
            </option>
          </select>
        </div>

        <div class="local__schedule">
          <p class="schedule__week flex-center">
            <img src="/img/utils/arrow_back.svg" alt="Ir a semana anterior" @click="goToPreviousWeek"
              :class="{ disabled: isPreviousWeekDisabled() }" />
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
              <td v-for="day in [0, 1, 2, 3, 4]" :key="day" :class="getCellClass(getDateForDay(day), time)"
                @click="selectSlot(getDateForDay(day), time)">

                {{ time }}
              </td>
            </tr>
          </table>
          <span v-if="mensaje" class="mensaje__servicio">{{ mensaje }}</span>

          <div class="schedule__info flex">
            <div><span class="square square--green"></span> Huecos disponibles</div>
            <div><span class="square square--red"></span> Huecos ocupados</div>
          </div>
        </div>

        <div class="local__button flex-center">
          <PrimaryButton label="Pedir cita" :disabled="!canSubmit" @click="pedirCita" />
        </div>
      </div>

      <div v-else>
        <p>No se encontraron datos para esta peluquería.</p>
      </div>
    </div>

    <!-- Modal -->
    <ModalConfirm v-model:show="showModal" :message="modalMessage" :showCancel="false" confirmText="Aceptar" />

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
  width: 80%;
  height: 80%;
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
    max-height: 500px;
    position: relative;
    overflow: hidden;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-top-left-radius: 8px;
    }

    .carrusel-btn {
      height: 2.5rem;
      width: 2rem;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(94, 94, 94, 0.4);
      border-radius: 5px;
      border: none;
      cursor: pointer;
      z-index: 1;

      &:disabled {
        cursor: auto;
      }
    }

    .carrusel-btn.izquierda {
      left: 10px;
    }

    .carrusel-btn.derecha {
      right: 10px;
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
    position: relative;

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


      filter: blur(4px);
      pointer-events: none;

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
        border: 2px solid map-get($colores, "gris_claro");

        &:hover {
          outline: 2px solid map-get($colores, "naranja");
          cursor: pointer;
        }
      }

    }

    .mensaje__servicio {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(255, 255, 255, 0.8);
      color: map-get($colores, "azul_oscuro");
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      white-space: nowrap;
      font-size: 1.2rem;
      font-weight: bold;
      padding: 1rem 2rem;
      border-radius: 8px;
      pointer-events: none;
    }

    .schedule__info {
      padding: 10px 2px;
      gap: 20px;

      .square {
        display: inline-block;
        width: 10px;
        height: 10px;
        margin-right: 2.5px;
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

    .hora__seleccionada {
      background-color: map-get($colores, "naranja");
      color: map-get($colores, "blanco");
      outline: none;
      font-weight: bold;
    }

    .hora__seleccionada--start {
      border-bottom: none;
    }

    .hora__seleccionada--middle {
      border-top: none;
      border-bottom: none;
    }

    .hora__seleccionada--end {
      color: map-get($colores, "blanco");
      border-top: none;
    }
  }

  .local__schedule:not(:has(.mensaje__servicio)) table {
    filter: none;
    pointer-events: auto;
  }

  .local__button {
    grid-area: 6 / 2 / 7 / 3;
  }

  .error {
    color: map-get($colores, "naranja");
  }
}
</style>