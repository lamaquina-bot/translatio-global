<?php
/**
 * Configuración de WordPress - Ejemplo
 * Translatio Global Theme
 * 
 * Este archivo contiene las configuraciones básicas de WordPress.
 * Copia este archivo como wp-config.php y completa los valores.
 * 
 * @package Translatio_Global
 */

// ** Configuración de MySQL ** //
/** Nombre de la base de datos de WordPress */
define( 'DB_NAME', 'nombre_base_datos_aqui' );

/** Usuario de la base de datos MySQL */
define( 'DB_USER', 'usuario_mysql_aqui' );

/** Contraseña de la base de datos MySQL */
define( 'DB_PASSWORD', 'contraseña_mysql_aqui' );

/** Servidor de la base de datos MySQL */
define( 'DB_HOST', 'localhost' );

/** Codificación de caracteres para la base de datos */
define( 'DB_CHARSET', 'utf8mb4' );

/** Cotejamiento de la base de datos. No cambiar si tienes dudas. */
define( 'DB_COLLATE', '' );

/**
 * Claves únicas de autenticación y sales.
 *
 * Cambia cada clave por una frase única. Genera las claves en:
 * https://api.wordpress.org/secret-key/1.1/salt/
 */
define( 'AUTH_KEY',         'pon aquí tu frase aleatoria' );
define( 'SECURE_AUTH_KEY',  'pon aquí tu frase aleatoria' );
define( 'LOGGED_IN_KEY',    'pon aquí tu frase aleatoria' );
define( 'NONCE_KEY',        'pon aquí tu frase aleatoria' );
define( 'AUTH_SALT',        'pon aquí tu frase aleatoria' );
define( 'SECURE_AUTH_SALT', 'pon aquí tu frase aleatoria' );
define( 'LOGGED_IN_SALT',   'pon aquí tu frase aleatoria' );
define( 'NONCE_SALT',       'pon aquí tu frase aleatoria' );

/**
 * Prefijo de la tabla de la base de datos de WordPress.
 * Cambia esto si tienes múltiples instalaciones en una sola base de datos.
 */
$table_prefix = 'wp_';

/**
 * Modo de desarrollo de WordPress.
 * 
 * true: Activa el modo de desarrollo (mostrar errores, scripts sin minificar)
 * false: Modo producción (recomendado para sitios en vivo)
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );

/**
 * Configuraciones adicionales de seguridad y rendimiento
 */

// Deshabilitar el editor de plugins y temas en el admin
define( 'DISALLOW_FILE_EDIT', true );

// Deshabilitar la instalación de plugins y temas (opcional, hosting gestionado)
// define( 'DISALLOW_FILE_MODS', true );

// Forzar SSL en el admin
define( 'FORCE_SSL_ADMIN', true );

// Límite de memoria de PHP (ajustar según necesidades)
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

// Deshabilitar revisiones de posts o limitar cantidad
define( 'WP_POST_REVISIONS', 3 );

// Papelera de posts (días antes de eliminación permanente)
define( 'EMPTY_TRASH_DAYS', 30 );

// Compresión de scripts y CSS para el admin
define( 'COMPRESS_CSS', true );
define( 'COMPRESS_SCRIPTS', true );
define( 'CONCATENATE_SCRIPTS', true );
define( 'ENFORCE_GZIP', true );

// Configuración de actualizaciones automáticas
define( 'WP_AUTO_UPDATE_CORE', 'minor' ); // false|true|'minor'|'major'

/**
 * Configuración de entorno (desarrollo, staging, producción)
 */
if ( ! defined( 'WP_ENV' ) ) {
    define( 'WP_ENV', 'production' );
}

/**
 * URLs de WordPress
 * Descomenta estas líneas si tienes problemas con el dominio
 */
// define( 'WP_HOME', 'https://tudominio.com' );
// define( 'WP_SITEURL', 'https://tudominio.com/wp' );

/**
 * Configuración para multisitio (si aplica)
 */
// define( 'WP_ALLOW_MULTISITE', false );

/**
 * ¡No editar más allá de este punto!
 */

/** Ruta absoluta al directorio de WordPress */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura las vars de WordPress y los archivos incluidos. */
require_once ABSPATH . 'wp-settings.php';
