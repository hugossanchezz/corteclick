-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2025 a las 16:49:26
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
  `respuesta` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `id_usuario`, `id_peluqueria`, `id_servicio`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `valoracion`, `puntuacion`, `respuesta`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-06-02', '09:00:00', '09:30:00', 'CONFIRMADA', NULL, NULL, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(2, 1, 1, 1, '2025-06-03', '10:30:00', '11:00:00', 'CONFIRMADA', NULL, NULL, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(3, 1, 1, 1, '2025-06-04', '11:00:00', '11:30:00', 'CONFIRMADA', NULL, NULL, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 1, 1, 1, '2025-06-05', '09:30:00', '10:00:00', 'CONFIRMADA', NULL, NULL, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(5, 1, 1, 1, '2025-06-06', '13:00:00', '13:30:00', 'CONFIRMADA', NULL, NULL, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12');

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
(12, '2025_05_21_190020_create_citas_table', 1);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `peluquerias`
--

INSERT INTO `peluquerias` (`id`, `nombre`, `descripcion`, `direccion`, `localidad`, `email`, `telefono`, `tipo`, `valoracion`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Peluquería Rosa', 'Peluquería unisex con amplia experiencia. Cortes clásicos y modernos, coloración y tratamientos capilares.', 'Calle Corral, 590, 99º B', 4, 'rosa@example.com', '932-82-5330', 'UNISEX', 3.10, 2, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(2, 'Barber Shop El Barbero', 'Barbería especializada en cortes masculinos. Afeitados clásicos, arreglo de barba y cortes de tendencia.', 'Carrer Valdés, 591, Entre suelo 7º', 6, 'barber@example.com', '+34 921-70-0502', 'BARBERIA', 3.60, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(3, 'Peluquería Carmen', 'Peluquería para señoras. Peinados, recogidos para eventos y tratamientos de belleza.', 'Camiño Vera, 719, 26º E', 3, 'carmen@example.com', '+34 986 185877', 'PELUQUERIA', 4.70, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 'Peluquería María', 'Especialistas en coloración y mechas. Las últimas tendencias en color y cuidado del cabello.', 'Paseo Molina, 312, 1º C', 5, 'maria@example.com', '979-680167', 'PELUQUERIA', 3.90, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(5, 'Barbería Jose', 'Barbería clásica con un toque moderno. Cortes de pelo, arreglo de barba y afeitados tradicionales.', 'Praza Abril, 89, 39º 6º', 4, 'jose@example.com', '+34 906 618798', 'BARBERIA', 3.80, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(6, 'Salón de Belleza Ana', 'Salón de belleza con servicios de peluquería, estética y maquillaje.', 'Camiño Sánchez, 2, Bajos', 7, 'ana@example.com', '+34 941 06 4280', 'UNISEX', 4.20, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(7, 'Estilistas Laura', 'Peluquería de autor. Estilos personalizados y asesoramiento de imagen.', 'Rúa Natalia, 40, Bajos', 2, 'laura@example.com', '981-397791', 'PELUQUERIA', 1.90, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(8, 'Barbería Los Hermanos', 'Barbería tradicional con un ambiente familiar. Cortes de pelo y arreglo de barba.', 'Avinguda Gabriela, 95, 7º 3º', 6, 'hermanos@example.com', '+34 968-61-9901', 'BARBERIA', 2.00, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(9, 'Peluquería Moderna', 'Peluquería con las últimas tendencias en cortes, coloración y peinados.', 'Paseo Laboy, 25, 86º 3º', 2, 'moderna@example.com', '923-960132', 'UNISEX', 2.70, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(10, 'El Rincón del Barbero', 'Barbería especializada en afeitados clásicos con toalla caliente.', 'Praza Valadez, 326, 48º B', 4, 'rincon@example.com', '972-18-4863', 'BARBERIA', 1.80, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 'Peluquería Estilo', 'Peluquería unisex con un ambiente moderno y acogedor.', 'Ruela Guillem, 6, Bajo 6º', 7, 'estilo@example.com', '902 61 5139', 'UNISEX', 2.10, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 'Barbería Clásica', 'Barbería tradicional con el encanto de antaño. Cortes y afeitados clásicos.', 'Paseo Guillem, 568, 57º C', 2, 'clasica@example.com', '+34 919 530756', 'BARBERIA', 3.50, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(13, 'Peluquería Infantil', 'Peluquería especializada en cortes de pelo para niños y niñas. Ambiente divertido y personal amable.', 'Camiño Juan, 5, 45º A', 6, 'infantil@example.com', '+34 936084289', 'PELUQUERIA', 2.20, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(14, 'Barbería Urbana', 'Barbería con un estilo urbano y moderno. Cortes de pelo y arreglo de barba de tendencia.', 'Travesía Beatriz, 726, 0º A', 1, 'urbana@example.com', '+34 983-51-2054', 'BARBERIA', 1.90, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 'Peluquería Familiar', 'Peluquería para toda la familia. Cortes de pelo, coloración y tratamientos capilares.', 'Camiño Coronado, 523, 52º B', 6, 'familiar@example.com', '945 88 2307', 'UNISEX', 3.80, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(16, 'Barbería Vintage', 'Barbería con decoración vintage y ambiente retro. Cortes y afeitados clásicos.', 'Calle Fernando, 888, 2º 0º', 3, 'vintage@example.com', '+34 920 52 5614', 'BARBERIA', 2.90, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(17, 'Peluquería Creativa', 'Peluquería especializada en estilos creativos y vanguardistas. Cortes de pelo y coloración originales.', 'Paseo Romero, 72, 23º D', 1, 'creativa@example.com', '916-61-4111', 'PELUQUERIA', 2.30, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(18, 'Barbería de Autor', 'Barbería con un estilo único y personal. Cortes de pelo y arreglo de barba personalizados.', 'Ronda Zúñiga, 1, Ático 9º', 5, 'autor@example.com', '917832474', 'BARBERIA', 2.90, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(19, 'Peluquería y Estética', 'Centro de belleza que ofrece servicios de peluquería, estética y tratamientos de belleza. Ambiente moderno y profesional, con servicios personalizados.', 'Paseo Marc, 15, 90º F', 6, 'estetica@example.com', '910-846778', 'UNISEX', 1.30, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(20, 'Barbería Premium', 'Barbería de lujo con servicios exclusivos. Cortes de pelo, afeitados y tratamientos de alta gama.', 'Passeig Paula, 706, 73º A', 4, 'premium@example.com', '948-240198', 'BARBERIA', 2.40, NULL, '2025-06-01 17:06:12', '2025-06-01 17:06:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peluquerias_fotos`
--

CREATE TABLE `peluquerias_fotos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_peluqueria` bigint(20) UNSIGNED NOT NULL,
  `imagen` blob DEFAULT NULL,
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
(1, 'App\\Models\\User', 1, 'auth_token', 'b06dfa85ce6fd84a99e849a2908cf00fcd26cddb18e3236c896add8494b959eb', '[\"*\"]', NULL, NULL, '2025-06-04 13:48:30', '2025-06-04 13:48:30'),
(2, 'App\\Models\\User', 1, 'auth_token', '4787131d8bc16e0702d14e3ebac43129f43653ba03ae22962fd668149370220b', '[\"*\"]', NULL, NULL, '2025-06-05 11:55:55', '2025-06-05 11:55:55');

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
(1, 'Administrador', 'Rol con todos los privilegios.', '2025-06-01 17:06:11', '2025-06-01 17:06:11'),
(2, 'Usuario', 'Rol de usuario básico.', '2025-06-01 17:06:11', '2025-06-01 17:06:11');

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
(1, 'Corte de pelo clásico', 'Corte de cabello tradicional para hombre.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(2, 'Corte + arreglo de barba', 'Combo de corte masculino y perfilado de barba.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(3, 'Fade + diseño de cejas', 'Degradado moderno más depilación masculina de cejas.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 'Afeitado con toalla caliente', 'Afeitado tradicional con navaja y tratamiento térmico.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(5, 'Tinte para canas', 'Cobertura natural de canas para cabello masculino.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(6, 'Tratamiento anticaspa', 'Tratamiento especializado para eliminar la caspa.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(7, 'Masaje facial + barba', 'Relajación facial y mantenimiento de barba.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(8, 'Limpieza facial', 'Limpieza profunda del rostro para hombres.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(9, 'Diseño completo de barba', 'Recorte, línea y forma personalizada.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(10, 'Corte express', 'Corte rápido sin lavado ni peinado.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 'Corte y peinado', 'Corte de puntas y peinado final.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 'Coloración + peinado', 'Tinte completo seguido de peinado.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(13, 'Ondas naturales', 'Moldeado de cabello con ondas suaves.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(14, 'Mechas balayage', 'Técnica de coloración suave y degradada.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 'Tratamiento hidratante', 'Mascarilla profunda para cabello seco o dañado.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(16, 'Recogido de fiesta', 'Peinado elegante para eventos especiales.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(17, 'Extensiones de cabello', 'Colocación de extensiones naturales o sintéticas.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(18, 'Alisado con keratina', 'Alisado semi-permanente con productos nutritivos.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(19, 'Plancha + sérum', 'Alisado con plancha y acabado con sérum hidratante.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(20, 'Brushing con volumen', 'Cepillado con secador para lograr volumen natural.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 'Lavado + masaje capilar', 'Limpieza capilar con masaje relajante.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(22, 'Tratamiento fortalecedor', 'Aplicación para fortalecer y evitar la caída del cabello.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(23, 'Corte infantil', 'Corte unisex para niños y niñas.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(24, 'Mascarilla capilar unisex', 'Mascarilla nutritiva para todo tipo de cabello.', '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(25, 'Secado con estilo', 'Secado con brushing o moldeado personalizado.', '2025-06-01 17:06:12', '2025-06-01 17:06:12');

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
(1, 1, 15.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(1, 2, 12.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(1, 5, 14.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(1, 6, 16.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(1, 8, 13.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(1, 9, 15.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(1, 10, 14.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(2, 1, 25.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(2, 2, 20.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(2, 8, 22.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 2, 15.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 5, 16.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 8, 14.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(4, 10, 18.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(9, 2, 18.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(9, 5, 20.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(9, 8, 19.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(9, 10, 20.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 1, 20.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 3, 18.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 6, 22.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 7, 20.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(11, 9, 20.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 1, 35.00, 60, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 3, 40.00, 75, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 4, 38.00, 60, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 6, 40.00, 60, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 7, 42.00, 75, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(12, 9, 38.00, 60, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(14, 4, 50.00, 90, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(14, 7, 55.00, 90, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 1, 25.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 3, 22.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 4, 25.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 6, 28.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(15, 9, 25.00, 45, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(16, 3, 30.00, 60, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(16, 7, 35.00, 60, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(18, 4, 60.00, 120, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 1, 10.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 2, 8.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 3, 12.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 4, 10.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 5, 9.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 6, 12.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 7, 11.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 8, 8.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 9, 10.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(21, 10, 9.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(23, 1, 12.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(23, 5, 10.00, 15, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(23, 6, 14.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(23, 9, 12.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12'),
(23, 10, 11.00, 30, '2025-06-01 17:06:12', '2025-06-01 17:06:12');

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
('6pNMJlAThM2IuYySBh7rbXPUP1GFqIAz9T4UkZTb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibEdMNmJZMkpLRDhYSklZRXRLRUNINjhlUUNvMHdrc2Vma2tqakV0ciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZXF1ZXN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1749131831);

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
  `foto` blob DEFAULT NULL,
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

INSERT INTO `users` (`id`, `name`, `apellidos`, `telefono`, `localidad`, `foto`, `rol_id`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Administrador', '600000001', 5, NULL, 1, 'admin@example.com', '2025-06-01 17:06:11', '$2y$12$9h4ciTtBU0Ym5KTw.WokjO3.5RNFBQJzOJBd096jIat0kSJeDiQ0m', 'CENHdbkFpA', '2025-06-01 17:06:11', '2025-06-01 17:06:11'),
(2, 'Usuario', 'López', '600000003', 6, NULL, 2, 'usuario@example.com', '2025-06-01 17:06:11', '$2y$12$f7tZNb6IluFvYhVNsxEZPuY8JIU47F/enPQ0oYgAoQBedmPYw3ype', 'w3GyOH5rAG', '2025-06-01 17:06:11', '2025-06-01 17:06:11');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- AUTO_INCREMENT de la tabla `peluquerias_solicitudes`
--
ALTER TABLE `peluquerias_solicitudes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
