<script>
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';

export default {
    name: "Terms",
    setup() {
        const router = useRoute();
        const currentYear = ref(new Date().getFullYear());
        // Usa computed para que isRouteTerms se actualice automáticamente
        const isRouteTerms = computed(() => router.path === '/auth/register/terms');

        return {
            currentYear,
            isRouteTerms,
        };
    },
};
</script>
<template>
    <section v-if="isRouteTerms">
        <div>
            <h1>Términos y Condiciones de Uso</h1>
            <p>
                <strong>Última actualización:</strong>
                13/04/2025
            </p>
        </div>
        <p>
            Bienvenido a <span class="span__corteclick">Corteclick</span>, una
            plataforma online que facilita la reserva de citas en peluquerías y
            barberías. Al acceder y utilizar este sitio web, aceptas los
            siguientes términos y condiciones. Si no estás de acuerdo con estos
            términos, te pedimos que <strong>no</strong> utilices esta
            plataforma.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Aceptación de los Términos
        </h2>
        <p>
            Al acceder y utilizar los servicios de
            <span class="span__corteclick">Corteclick</span>, aceptas estos
            términos y condiciones de uso. Nos reservamos el derecho de
            modificar, actualizar o cambiar estos términos en cualquier momento,
            por lo que te recomendamos revisarlos periódicamente.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Uso de la Plataforma
        </h2>
        <p>
            <span class="span__corteclick">Corteclick</span> te proporciona
            acceso a una variedad de servicios relacionados con la reserva de
            citas en peluquerías y barberías, como:
        </p>
        <ul>
            <li>Búsqueda de peluquerías y barberías cercanas.</li>
            <li>
                Visualización de servicios ofrecidos, precios y disponibilidad.
            </li>
            <li>Reserva y gestión de citas online.</li>
            <li>Valoración y reseñas de establecimientos.</li>
        </ul>
        <p>
            Te comprometes a usar la plataforma de manera legal y de acuerdo con
            todas las leyes aplicables. No podrás:
        </p>
        <ul>
            <li>
                Utilizar el sitio para realizar actividades ilegales o no
                autorizadas.
            </li>
            <li>
                Publicar contenido falso, engañoso o difamatorio sobre
                establecimientos.
            </li>
            <li>
                Interrumpir o impedir el correcto funcionamiento del sitio web o
                los servicios ofrecidos.
            </li>
            <li>
                Intentar acceder a cuentas de otros usuarios o a información
                privada de los establecimientos.
            </li>
        </ul>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Cuenta de Usuario
        </h2>
        <p>
            Para acceder a ciertos servicios, como la reserva de citas, deberás
            crear una cuenta. Al hacerlo, te comprometes a proporcionar
            información veraz y actualizada. Eres responsable de mantener la
            confidencialidad de tu cuenta y contraseña, y de todas las
            actividades que ocurran bajo tu cuenta.
        </p>
        <p>
            <span class="span__corteclick">Corteclick</span> se reserva el
            derecho de suspender o cancelar cualquier cuenta si se detecta
            actividad sospechosa o infracción de estos términos.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Propiedad Intelectual
        </h2>
        <p>
            Todos los contenidos, incluyendo pero no limitado a texto, imágenes,
            logotipos, gráficos, videos, música y software, son propiedad de
            <span class="span__corteclick">Corteclick</span> o de sus
            respectivos propietarios. El uso no autorizado de cualquiera de
            estos materiales está prohibido.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Contenido de Terceros
        </h2>
        <p>
            <span class="span__corteclick">Corteclick</span> puede mostrar
            enlaces a sitios web de terceros o permitir a los usuarios
            interactuar con servicios de terceros (por ejemplo, pasarelas de
            pago). Estos sitios no están bajo nuestro control y no somos
            responsables de su contenido, prácticas de privacidad o políticas.
            Al acceder a estos sitios, lo haces bajo tu propio riesgo.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Protección de Datos y Privacidad
        </h2>
        <p>
            La protección de tu privacidad es importante para nosotros. Al crear
            una cuenta en <span class="span__corteclick">Corteclick</span>,
            aceptas nuestra
            <span>
                <router-link to="/auth/register/terms/privacy">
                    Política de Privacidad
                </router-link> </span
            >, que explica cómo recopilamos, usamos y protegemos tu información
            personal.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Modificaciones del Servicio
        </h2>
        <p>
            <span class="span__corteclick">Corteclick</span> se reserva el
            derecho de modificar, suspender o descontinuar el servicio, total o
            parcialmente, en cualquier momento. También nos reservamos el
            derecho de modificar estos Términos y Condiciones en cualquier
            momento.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Limitación de Responsabilidad
        </h2>
        <p>
            <span class="span__corteclick">Corteclick</span> no será responsable
            por daños directos, indirectos, incidentales, especiales o
            consecuentes derivados de:
        </p>
        <ul>
            <li>El uso o la incapacidad para usar el sitio.</li>
            <li>Errores en la disponibilidad de citas.</li>
            <li>
                La calidad de los servicios prestados por los establecimientos
                (peluquerías y barberías).
            </li>
            <li>La pérdida de datos, información o ganancias.</li>
            <li>La interrupción del servicio.</li>
        </ul>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Comunicaciones y Notificaciones
        </h2>
        <p>
            Al registrarte en <span class="span__corteclick">Corteclick</span>,
            aceptas recibir comunicaciones de nuestra parte, incluidas
            notificaciones sobre actualizaciones, confirmaciones de citas,
            recordatorios y otros anuncios relacionados con la plataforma.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Jurisdicción y Ley Aplicable
        </h2>
        <p>
            Estos Términos y Condiciones se regirán e interpretarán de acuerdo
            con las leyes del país o jurisdicción en la que se base la
            plataforma, en nuestro caso el Reino de España. Cualquier disputa
            relacionada con estos términos será resuelta en los tribunales del
            país en cuestión.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Consentimiento para el Uso de Cookies
        </h2>
        <p>
            Al utilizar este sitio web, aceptas el uso de cookies. Las cookies
            se utilizan para mejorar tu experiencia en el sitio y analizar su
            uso. Para obtener más información, consulta nuestra
            <span
                ><router-link to="/auth/register/terms/cookies"
                    >Política de Cookies</router-link
                ></span
            >.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Renuncia de Responsabilidad
        </h2>
        <p>
            <span class="span__corteclick">Corteclick</span> no garantiza que el
            servicio estará libre de interrupciones o errores, ni que las fallas
            serán corregidas. No somos responsables de los daños resultantes del
            uso del sitio, ni de los contenidos que puedan estar
            desactualizados, incorrectos o incompletos. Tampoco somos
            responsables de la calidad de los servicios prestados por las
            peluquerías y barberías, ni de la disponibilidad real de las citas
            mostradas en la plataforma.
        </p>

        <h2 class="flex">
            <img src="/img/auth/category.svg" alt="Icono para cada apartado" />
            Contacto
        </h2>
        <p>
            Si tienes alguna pregunta o inquietud sobre estos Términos y
            Condiciones, puedes contactarnos a través de nuestra sección de
            contacto en el sitio web.
        </p>

        <hr />

        <h2 class="flex">
            <img src="/img/auth/pencil.svg" alt="Icono de firma" /> Firma del
            Usuario
        </h2>
        <p>
            Al crear una cuenta en
            <span class="span__corteclick">Corteclick</span>, confirmas que has
            leído, comprendido y aceptado estos Términos y Condiciones.
        </p>

        <div class="final">
            <p>
                &copy; {{ currentYear }} Corteclick S.L. Todos los derechos
                reservados.
            </p>
        </div>
        <!-- Botón para volver al Registro -->
        <div class="btn-return">
            <router-link to="/auth/register" class="flex-center"
                >Volver</router-link
            >
        </div>
    </section>
    <div v-else>
        <router-view />
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;
@use "@/sass/common" as *;

section {
    border: 1px solid map-get($colores, "gris_claro");
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 50%;
    height: 40rem;
    margin: 1rem;
    padding: 1rem;
    border-radius: 10px;
    overflow-y: auto;

    span {
        a {
            color: map-get($colores, "naranja");
            text-decoration: none;
            &:hover {
                text-decoration: underline;
            }
        }
    }

    .final {
        color: map-get($colores, "gris");
        text-align: center;
        margin-top: 20px;
    }

    /* Asegurarse de que el contenido no se solape */
    p,
    h2,
    ul {
        margin: 20px 0;
    }

    h2 {
        align-items: center;
        gap: 10px;
    }

    ul {
        margin-left: 1.5rem;

        li {
            margin-bottom: 5px;
        }
    }
}

.btn-return {
    background-color: map-get($colores, "naranja");
    position: sticky;
    width: 5rem;
    height: 1.5rem;
    border-radius: 10px;

    bottom: 0;
    left: 100%;
    z-index: 10;
    text-align: center;

    a {
        height: 100%;
        width: 100%;
        text-decoration: none;
        color: map-get($colores, "blanco");
    }
}
</style>
