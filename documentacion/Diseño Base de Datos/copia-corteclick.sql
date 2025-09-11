-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2025 a las 18:38:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `corteclick`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_peluqueria` bigint(20) UNSIGNED NOT NULL,
  `id_servicio` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('CONFIRMADA','CANCELADA','TERMINADA') NOT NULL,
  `valoracion` varchar(200) DEFAULT NULL,
  `puntuacion` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `id_usuario`, `id_peluqueria`, `id_servicio`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `valoracion`, `puntuacion`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-06-05', '09:00:00', '10:30:00', 'TERMINADA', NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:17:07'),
(2, 1, 1, 1, '2025-06-13', '10:30:00', '11:00:00', 'TERMINADA', NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:17:07'),
(3, 1, 1, 1, '2025-06-12', '11:00:00', '11:30:00', 'TERMINADA', NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:17:07'),
(4, 1, 1, 1, '2025-06-11', '09:30:00', '10:00:00', 'TERMINADA', NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:17:07'),
(5, 1, 1, 1, '2025-06-10', '13:00:00', '13:30:00', 'TERMINADA', NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:17:07'),
(6, 1, 1, 1, '2025-07-16', '11:00:00', '11:30:00', 'TERMINADA', NULL, NULL, '2025-07-13 15:25:16', '2025-07-13 15:25:16'),
(7, 1, 1, 12, '2025-09-09', '11:00:00', '12:00:00', 'TERMINADA', NULL, NULL, '2025-09-11 14:20:16', '2025-09-11 14:21:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidades`
--

CREATE TABLE `localidades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_postal` varchar(5) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `localidades`
--

INSERT INTO `localidades` (`id`, `codigo_postal`, `nombre`, `created_at`, `updated_at`) VALUES
(1, '02001', 'ALBACETE', NULL, NULL),
(2, '04001', 'ALMERIA', NULL, NULL),
(3, '13001', 'CIUDAD REAL', NULL, NULL),
(4, '16001', 'CUENCA', NULL, NULL),
(5, '19001', 'GUADALAJARA', NULL, NULL),
(6, '40001', 'SEGOVIA', NULL, NULL),
(7, '45001', 'TOLEDO', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_04_20_111254_create_personal_access_tokens_table', 1),
(4, '2025_04_23_152215_create_localidades_table', 1),
(5, '2025_04_23_152254_create_servicios_table', 1),
(6, '2025_05_18_140537_create_roles_table', 1),
(7, '2025_05_18_143313_create_users_table', 1),
(8, '2025_05_21_185706_create_peluquerias_table', 1),
(9, '2025_05_21_185741_create_peluquerias_solicitudes_table', 1),
(10, '2025_05_21_185842_create_peluquerias_fotos_table', 1),
(11, '2025_05_21_185936_create_servicios_peluquerias_table', 1),
(12, '2025_05_21_190020_create_citas_table', 1),
(13, '2025_06_09_145458_create_peluquerias_fotos_temporales_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquerias`
--

CREATE TABLE `peluquerias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `direccion` varchar(200) NOT NULL,
  `localidad` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo` enum('BARBERIA','PELUQUERIA','UNISEX') DEFAULT NULL,
  `valoracion` decimal(3,2) DEFAULT NULL COMMENT 'Media de las valoraciones del establecimiento',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `imagen` longblob DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `peluquerias`
--

INSERT INTO `peluquerias` (`id`, `nombre`, `descripcion`, `direccion`, `localidad`, `email`, `telefono`, `tipo`, `valoracion`, `user_id`, `imagen`, `created_at`, `updated_at`) VALUES
(1, 'Salón de Belleza Ana', 'Salón de belleza con servicios de peluquería, estética y maquillaje.', 'Rúa Ian, 68, 7º D', 7, 'ana@gmail.com', '705022968', 'UNISEX', 3.10, 1, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 'Peluquería Rosa', 'Peluquería unisex con amplia experiencia. Cortes clásicos y modernos, coloración y tratamientos capilares.', 'Ronda Daniela, 8, Bajo 7º', 1, 'rosa@gmail.com', '829313926', 'UNISEX', 2.00, 2, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(3, 'Barber Shop El Barbero', 'Barbería especializada en cortes masculinos. Afeitados clásicos, arreglo de barba y cortes de tendencia.', 'Praza Soria, 62, 64º A', 7, 'barber@gmail.com', '683363416', 'BARBERIA', 3.40, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 'Peluquería Carmen', 'Peluquería para señoras. Peinados, recogidos para eventos y tratamientos de belleza.', 'Avinguda Rosa María, 1, 43º 0º', 1, 'carmen@gmail.com', '611799167', 'PELUQUERIA', 4.30, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(5, 'Peluquería María', 'Especialistas en coloración y mechas. Las últimas tendencias en color y cuidado del cabello.', 'Plaça Aaron, 3, 21º A', 7, 'maria@gmail.com', '864687977', 'PELUQUERIA', 2.20, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(6, 'Barbería Jose', 'Barbería clásica con un toque moderno. Cortes de pelo, arreglo de barba y afeitados tradicionales.', 'Ronda Calvo, 98, 4º B', 5, 'jose@gmail.com', '797602130', 'BARBERIA', 4.10, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(7, 'Estilistas Laura', 'Peluquería de autor. Estilos personalizados y asesoramiento de imagen.', 'Camino Inés, 209, Entre suelo 5º', 6, 'laura@gmail.com', '961412573', 'PELUQUERIA', 2.20, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(8, 'Barbería Los Hermanos', 'Barbería tradicional con un ambiente familiar. Cortes de pelo y arreglo de barba.', 'Ronda Marta, 874, 51º C', 2, 'hermanos@gmail.com', '998165319', 'BARBERIA', 3.70, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 'Peluquería Moderna', 'Peluquería con las últimas tendencias en cortes, coloración y peinados.', 'Plaza Canales, 42, 5º C', 7, 'moderna@gmail.com', '944379256', 'UNISEX', 4.30, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(10, 'El Rincón del Barbero', 'Barbería especializada en afeitados clásicos con toalla caliente.', 'Plaça Gamboa, 5, 3º C', 5, 'rincon@gmail.com', '850492553', 'BARBERIA', 5.00, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 'Peluquería Estilo', 'Peluquería unisex con un ambiente moderno y acogedor.', 'Camiño Vega, 77, 1º E', 1, 'estilo@gmail.com', '941845362', 'UNISEX', 4.70, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 'Barbería Clásica', 'Barbería tradicional con el encanto de antaño. Cortes y afeitados clásicos.', 'Rúa Pablo, 776, Bajos', 7, 'clasica@gmail.com', '974484549', 'BARBERIA', 2.00, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(13, 'Peluquería Infantil', 'Peluquería especializada en cortes de pelo para niños y niñas. Ambiente divertido y personal amable.', 'Camiño Patricia, 5, 3º C', 2, 'infantil@gmail.com', '644211052', 'PELUQUERIA', 4.10, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 'Barbería Urbana', 'Barbería con un estilo urbano y moderno. Cortes de pelo y arreglo de barba de tendencia.', 'Camino Yeray, 61, Entre suelo 7º', 4, 'urbana@gmail.com', '708797052', 'BARBERIA', 2.10, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 'Peluquería Familiar', 'Peluquería para toda la familia. Cortes de pelo, coloración y tratamientos capilares.', 'Camiño Soto, 7, 1º F', 7, 'familiar@gmail.com', '673427813', 'UNISEX', 3.20, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 'Barbería Vintage', 'Barbería con decoración vintage y ambiente retro. Cortes y afeitados clásicos.', 'Plaça Santiago, 66, 0º 1º', 3, 'vintage@gmail.com', '712469544', 'BARBERIA', 3.80, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(17, 'Peluquería Creativa', 'Peluquería especializada en estilos creativos y vanguardistas. Cortes de pelo y coloración originales.', 'Plaça Rosado, 5, 77º B', 3, 'creativa@gmail.com', '701316496', 'PELUQUERIA', 4.80, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 'Barbería de Autor', 'Barbería con un estilo único y personal. Cortes de pelo y arreglo de barba personalizados.', 'Travesía Nora, 413, Ático 2º', 5, 'autor@gmail.com', '784151135', 'BARBERIA', 4.70, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(19, 'Peluquería y Estética', 'Centro de belleza que ofrece servicios de peluquería, estética y tratamientos de belleza. Ambiente moderno y profesional, con servicios personalizados.', 'Carrer Claudia, 91, 5º', 1, 'estetica@gmail.com', '751624576', 'UNISEX', 1.20, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(20, 'Barbería Premium', 'Barbería de lujo con servicios exclusivos. Cortes de pelo, afeitados y tratamientos de alta gama.', 'Rúa Celia, 34, 0º C', 4, 'premium@gmail.com', '850679365', 'BARBERIA', 4.40, NULL, NULL, '2025-07-13 15:16:02', '2025-07-13 15:16:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquerias_fotos`
--

CREATE TABLE `peluquerias_fotos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_peluqueria` bigint(20) UNSIGNED NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquerias_fotos_temporales`
--

CREATE TABLE `peluquerias_fotos_temporales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_peluqueria_solicitud` bigint(20) UNSIGNED NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquerias_solicitudes`
--

CREATE TABLE `peluquerias_solicitudes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('PENDIENTE','APROBADA','RECHAZADA') NOT NULL,
  `fecha` datetime NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `localidad` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `tipo` enum('BARBERIA','PELUQUERIA','UNISEX') NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `imagen` longblob DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '2b8c4418751040237bdb417e4099660b4e7bd4751eecdb48a2a6d3a19cf808dc', '[\"*\"]', NULL, NULL, '2025-09-11 14:16:54', '2025-09-11 14:16:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrador', 'Usuario con todos los privilegios sobre la aplicación y sobre los administradores.', '2025-07-13 15:16:01', '2025-07-13 15:16:01'),
(2, 'Administrador', 'Usuario con todos los privilegios sobre la aplicación.', '2025-07-13 15:16:01', '2025-07-13 15:16:01'),
(3, 'Usuario', 'Usuario básico.', '2025-07-13 15:16:01', '2025-07-13 15:16:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Corte de pelo clásico', 'Corte de cabello tradicional para hombre.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 'Corte + arreglo de barba', 'Combo de corte masculino y perfilado de barba.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(3, 'Fade + diseño de cejas', 'Degradado moderno más depilación masculina de cejas.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 'Afeitado con toalla caliente', 'Afeitado tradicional con navaja y tratamiento térmico.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(5, 'Tinte para canas', 'Cobertura natural de canas para cabello masculino.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(6, 'Tratamiento anticaspa', 'Tratamiento especializado para eliminar la caspa.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(7, 'Masaje facial + barba', 'Relajación facial y mantenimiento de barba.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(8, 'Limpieza facial', 'Limpieza profunda del rostro para hombres.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 'Diseño completo de barba', 'Recorte, línea y forma personalizada.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(10, 'Corte express', 'Corte rápido sin lavado ni peinado.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 'Corte y peinado', 'Corte de puntas y peinado final.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 'Coloración + peinado', 'Tinte completo seguido de peinado.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(13, 'Ondas naturales', 'Moldeado de cabello con ondas suaves.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 'Mechas balayage', 'Técnica de coloración suave y degradada.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 'Tratamiento hidratante', 'Mascarilla profunda para cabello seco o dañado.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 'Recogido de fiesta', 'Peinado elegante para eventos especiales.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(17, 'Extensiones de cabello', 'Colocación de extensiones naturales o sintéticas.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 'Alisado con keratina', 'Alisado semi-permanente con productos nutritivos.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(19, 'Plancha + sérum', 'Alisado con plancha y acabado con sérum hidratante.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(20, 'Brushing con volumen', 'Cepillado con secador para lograr volumen natural.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 'Lavado + masaje capilar', 'Limpieza capilar con masaje relajante.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(22, 'Tratamiento fortalecedor', 'Aplicación para fortalecer y evitar la caída del cabello.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 'Corte infantil', 'Corte unisex para niños y niñas.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(24, 'Mascarilla capilar unisex', 'Mascarilla nutritiva para todo tipo de cabello.', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(25, 'Secado con estilo', 'Secado con brushing o moldeado personalizado.', '2025-07-13 15:16:02', '2025-07-13 15:16:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_peluqueria`
--

CREATE TABLE `servicios_peluqueria` (
  `id_servicio` bigint(20) UNSIGNED NOT NULL,
  `id_peluqueria` bigint(20) UNSIGNED NOT NULL,
  `precio` decimal(4,2) NOT NULL,
  `duracion` int(11) NOT NULL COMMENT 'En minutos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios_peluqueria`
--

INSERT INTO `servicios_peluqueria` (`id_servicio`, `id_peluqueria`, `precio`, `duracion`, `created_at`, `updated_at`) VALUES
(1, 1, 15.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 2, 12.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 5, 14.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 6, 16.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 8, 13.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 9, 15.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 10, 14.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 11, 12.39, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 13, 15.67, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 14, 13.94, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 18, 12.49, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(1, 20, 12.38, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 1, 25.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 2, 20.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 8, 22.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 11, 24.96, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 13, 23.87, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 14, 21.53, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 15, 23.59, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 16, 23.29, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 17, 23.54, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 19, 20.06, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(2, 20, 22.24, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 2, 15.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 5, 16.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 8, 14.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 10, 18.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 12, 14.12, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 15, 13.65, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 18, 15.92, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(4, 20, 17.86, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 2, 18.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 5, 20.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 8, 19.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 10, 20.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 12, 21.02, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 13, 19.55, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 14, 20.23, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 17, 19.81, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 18, 17.90, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(9, 19, 17.51, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 1, 20.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 3, 18.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 6, 22.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 7, 20.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 9, 20.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 12, 19.02, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 13, 20.71, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 14, 19.13, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 15, 18.32, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 16, 20.23, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 19, 19.24, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(11, 20, 17.42, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 1, 35.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 3, 40.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 4, 38.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 6, 40.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 7, 42.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 9, 38.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 11, 33.35, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 12, 42.43, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 14, 43.10, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 15, 36.78, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(12, 16, 34.67, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 4, 50.00, 90, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 7, 55.00, 90, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 11, 55.28, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 12, 44.73, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 14, 57.76, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 15, 59.20, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 17, 47.03, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(14, 18, 59.65, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 1, 25.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 3, 22.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 4, 25.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 6, 28.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 9, 25.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 11, 24.41, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 12, 28.43, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 13, 27.47, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 16, 27.89, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 17, 24.14, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 18, 26.06, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(15, 19, 25.06, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 3, 30.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 7, 35.00, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 11, 27.88, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 14, 35.96, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 15, 35.12, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 16, 28.99, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 17, 34.99, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 18, 34.27, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 19, 36.58, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(16, 20, 28.19, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 4, 60.00, 120, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 11, 54.94, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 12, 57.12, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 13, 68.52, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 15, 55.86, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 16, 64.41, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 17, 59.88, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 19, 66.15, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(18, 20, 64.79, 60, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 1, 10.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 2, 8.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 3, 12.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 4, 10.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 5, 9.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 6, 12.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 7, 11.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 8, 8.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 9, 10.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 10, 9.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 13, 9.54, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 15, 10.86, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 16, 9.05, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 17, 8.89, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 18, 9.71, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 19, 11.15, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(21, 20, 11.02, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 1, 12.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 5, 10.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 6, 14.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 9, 12.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 10, 11.00, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 11, 11.82, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 12, 12.50, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 13, 11.93, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 14, 10.61, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 16, 11.80, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 17, 13.08, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 18, 11.64, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 19, 13.41, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(23, 20, 12.72, 30, '2025-07-13 15:16:02', '2025-07-13 15:16:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Cjsnz84KVsO54TKGUmUWRMnAgY1MLiVn73dBtrz0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmVCMUxTcTV4c0VGRFZyM3ZVakU0SG5XeXl4UG5iWmVueVhSMHNabiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvY2l0YXMvMS91c3VhcmlvLTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1752428115),
('lEuvWcDc2i4DP4jW23ChryZUzPsCFkdKYchDcKiQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTJDbzlYOWZ2REdPY2dKckx0bzl3OUdmeER6T2l0SFd5Q1F2MGNIZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvY2l0YXMvMS91c3VhcmlvLTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757608289);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `apellidos` varchar(200) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `localidad` bigint(20) UNSIGNED DEFAULT NULL,
  `rol_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `apellidos`, `telefono`, `localidad`, `rol_id`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Administrador', '600495081', 3, 1, 'admin@gmail.com', '2025-07-13 15:16:01', '$2y$12$w320p25xOD.cLqdSa.DGd.46KkATHHRODoxudx4DmxnCLXPEg50iy', 'r53SCGm7Mx', '2025-07-13 15:16:01', '2025-07-13 15:16:01'),
(2, 'Ana', 'López', '606184510', 6, 2, 'ana.admin@gmail.com', '2025-07-13 15:16:01', '$2y$12$.hNJUw9ypYXQ41lgjijOg.xq9P2uvDnVrML6c2tbtyolmNv8kl4Z.', 'rDJAPIm1mD', '2025-07-13 15:16:01', '2025-07-13 15:16:01'),
(3, 'Pol', 'Esquibel', '824863431', 2, 3, 'cesar.briones@example.org', '2025-07-13 15:16:01', '$2y$12$kfGb4/u/NurAV73Evy.GCOGak3ziYhxDEaQP0TbA0URyWp7UFO.Aa', '0Iw1G77zRq', '2025-07-13 15:16:01', '2025-07-13 15:16:01'),
(4, 'Rosario', 'Sauceda', '795485741', 1, 3, 'manuel.madera@example.org', '2025-07-13 15:16:01', '$2y$12$ZFHup.YKuWkAUp7rLkO9pewZyv.s/OBdWKLUiTcJYmpcfSbCbOV4G', '8goX2GWqXi', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(5, 'Omar', 'Requena', '758939547', 5, 3, 'cuenca.andres@example.com', '2025-07-13 15:16:02', '$2y$12$so8b.o/OZCJaah/Z.FWCSOYMZp9Kk/X0J9bZ5phJVGsZWXYTa.i4O', 'NNGtI8hH8H', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(6, 'David', 'Pedroza', '887460599', 4, 3, 'pedro87@example.org', '2025-07-13 15:16:02', '$2y$12$9kY7/ZoWyB.lt6E9Jj59AOy04MpsjXk/UZeMHrUdV0dF2rB50aHKe', '6BNekkFcmc', '2025-07-13 15:16:02', '2025-07-13 15:16:02'),
(7, 'Nerea', 'Herrero', '957848521', 5, 3, 'bruno.chacon@example.com', '2025-07-13 15:16:02', '$2y$12$4idwWaVwD28pjn2fnYHs2esIqAzOSlodw6dkHRzeayLM66OXirH2m', 'AZgyrNkUBZ', '2025-07-13 15:16:02', '2025-07-13 15:16:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citas_id_usuario_foreign` (`id_usuario`),
  ADD KEY `citas_id_peluqueria_foreign` (`id_peluqueria`),
  ADD KEY `citas_id_servicio_foreign` (`id_servicio`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `localidades`
--
ALTER TABLE `localidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `peluquerias`
--
ALTER TABLE `peluquerias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `peluquerias_email_unique` (`email`),
  ADD KEY `peluquerias_localidad_foreign` (`localidad`),
  ADD KEY `peluquerias_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `peluquerias_fotos`
--
ALTER TABLE `peluquerias_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peluquerias_fotos_id_peluqueria_foreign` (`id_peluqueria`);

--
-- Indices de la tabla `peluquerias_fotos_temporales`
--
ALTER TABLE `peluquerias_fotos_temporales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peluquerias_fotos_temporales_id_peluqueria_solicitud_foreign` (`id_peluqueria_solicitud`);

--
-- Indices de la tabla `peluquerias_solicitudes`
--
ALTER TABLE `peluquerias_solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `peluquerias_solicitudes_email_unique` (`email`),
  ADD KEY `peluquerias_solicitudes_user_id_foreign` (`user_id`),
  ADD KEY `peluquerias_solicitudes_localidad_foreign` (`localidad`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios_peluqueria`
--
ALTER TABLE `servicios_peluqueria`
  ADD PRIMARY KEY (`id_servicio`,`id_peluqueria`),
  ADD KEY `servicios_peluqueria_id_peluqueria_foreign` (`id_peluqueria`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_localidad_foreign` (`localidad`),
  ADD KEY `users_rol_id_foreign` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `localidades`
--
ALTER TABLE `localidades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `peluquerias`
--
ALTER TABLE `peluquerias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `peluquerias_fotos`
--
ALTER TABLE `peluquerias_fotos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `peluquerias_fotos_temporales`
--
ALTER TABLE `peluquerias_fotos_temporales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `peluquerias_solicitudes`
--
ALTER TABLE `peluquerias_solicitudes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_id_peluqueria_foreign` FOREIGN KEY (`id_peluqueria`) REFERENCES `peluquerias` (`id`),
  ADD CONSTRAINT `citas_id_servicio_foreign` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `citas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `peluquerias`
--
ALTER TABLE `peluquerias`
  ADD CONSTRAINT `peluquerias_localidad_foreign` FOREIGN KEY (`localidad`) REFERENCES `localidades` (`id`),
  ADD CONSTRAINT `peluquerias_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `peluquerias_fotos`
--
ALTER TABLE `peluquerias_fotos`
  ADD CONSTRAINT `peluquerias_fotos_id_peluqueria_foreign` FOREIGN KEY (`id_peluqueria`) REFERENCES `peluquerias` (`id`);

--
-- Filtros para la tabla `peluquerias_fotos_temporales`
--
ALTER TABLE `peluquerias_fotos_temporales`
  ADD CONSTRAINT `peluquerias_fotos_temporales_id_peluqueria_solicitud_foreign` FOREIGN KEY (`id_peluqueria_solicitud`) REFERENCES `peluquerias_solicitudes` (`id`);

--
-- Filtros para la tabla `peluquerias_solicitudes`
--
ALTER TABLE `peluquerias_solicitudes`
  ADD CONSTRAINT `peluquerias_solicitudes_localidad_foreign` FOREIGN KEY (`localidad`) REFERENCES `localidades` (`id`),
  ADD CONSTRAINT `peluquerias_solicitudes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `servicios_peluqueria`
--
ALTER TABLE `servicios_peluqueria`
  ADD CONSTRAINT `servicios_peluqueria_id_peluqueria_foreign` FOREIGN KEY (`id_peluqueria`) REFERENCES `peluquerias` (`id`),
  ADD CONSTRAINT `servicios_peluqueria_id_servicio_foreign` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_localidad_foreign` FOREIGN KEY (`localidad`) REFERENCES `localidades` (`id`),
  ADD CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
