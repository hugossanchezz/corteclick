import { createApp } from 'vue';
import App from './App.vue'; // Importa el componente raíz App
import router from './router/router'; // Importa tu instancia de Vue Router

const app = createApp(App); // Crea la instancia de la aplicación Vue con el componente App
app.use(router); // Usa Vue Router
app.mount('#app'); // Monta la aplicación en el elemento con el ID 'app' en tu index.blade.php
