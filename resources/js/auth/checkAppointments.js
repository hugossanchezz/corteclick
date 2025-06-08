// checkAppointments.js
import axios from "axios";

const API_BASE = "http://127.0.0.1:8000"; // URL del backend Laravel

// Constantes de tiempo en milisegundos
const INTERVAL_1_MIN = 1 * 60 * 1000;
const INTERVAL_5_MIN = 5 * 60 * 1000;
const INTERVAL_15_MIN = 15 * 60 * 1000;
const INTERVAL_30_MIN = 30 * 60 * 1000;
const INTERVAL_1_HOUR = 60 * 60 * 1000;

// Selecciona el intervalo deseado aquí
const INTERVAL_ACTUAL = INTERVAL_30_MIN;

async function verificarCitas() {
    try {
        const res = await axios.patch(`${API_BASE}/api/appointments/check-expired`);
        console.log("✅ " + (res.data?.message || "Citas actualizadas correctamente."));
    } catch (error) {
        if (error.response) {
            console.error("❌ Error al verificar citas:", error.response.data);
        } else if (error.request) {
            console.error("❌ No se recibió respuesta del servidor Laravel.");
        } else {
            console.error("❌ Error desconocido:", error.message);
        }
    }
}

// Espera 5 segundos antes del primer intento, luego ejecuta cada INTERVAL_ACTUAL
setTimeout(() => {
    verificarCitas();
    setInterval(verificarCitas, INTERVAL_ACTUAL);
}, 5000);
