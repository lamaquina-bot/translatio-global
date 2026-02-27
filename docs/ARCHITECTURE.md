# 🏗️ FASE 3: ARCHITECTURE - Translatio Global

**Fecha:** 27 Feb 2026
**Agente:** Architect Agent
**Estado:** ✅ COMPLETADO

---

## 📁 ESTRUCTURA DE ARCHIVOS WORDPRESS

```
translatio-global/
│
├── 📂 wp-content/
│   ├── 📂 themes/
│   │   └── 📂 translatio/                    # TEMA PRINCIPAL
│   │       │
│   │       ├── 📄 style.css                  # Stylesheet principal
│   │       ├── 📄 functions.php              # Funciones del tema
│   │       ├── 📄 header.php                 # Header global
│   │       ├── 📄 footer.php                 # Footer global
│   │       ├── 📄 index.php                  # Fallback template
│   │       ├── 📄 404.php                    # Página 404
│   │       ├── 📄 screenshot.png             # Preview del tema
│   │       │
│   │       ├── 📂 assets/                    # Assets compilados
│   │       │   ├── 📂 css/
│   │       │   │   ├── main.min.css          # CSS principal
│   │       │   │   └── critical.css          # Critical CSS inline
│   │       │   │
│   │       │   ├── 📂 js/
│   │       │   │   ├── main.min.js           # JS principal
│   │       │   │   └── navigation.min.js     # Navegación
│   │       │   │
│   │       │   ├── 📂 images/
│   │       │   │   ├── logo.svg              # Logo principal
│   │       │   │   ├── logo-white.svg        # Logo footer
│   │       │   │   ├── hero-bg.webp          # Fondo hero
│   │       │   │   ├── favicon.ico           # Favicon
│   │       │   │   │
│   │       │   │   └── 📂 icons/             # Iconos SVG
│   │       │   │       ├── icon-arrendamiento.svg
│   │       │   │       ├── icon-comercial.svg
│   │       │   │       ├── icon-inmobiliario.svg
│   │       │   │       ├── icon-check.svg
│   │       │   │       └── icon-whatsapp.svg
│   │       │   │
│   │       │   └── 📂 fonts/
│   │       │       ├── Inter-Variable.woff2   # Fuente latina
│   │       │       └── NotoSansSC.woff2      # Fuente china
│   │       │
│   │       ├── 📂 inc/                       # Funciones PHP
│   │       │   ├── setup.php                 # Theme setup
│   │       │   ├── enqueue.php               # Scripts & styles
│   │       │   ├── customizer.php            # WP Customizer
│   │       │   ├── cpt.php                   # Custom Post Types
│   │       │   ├── metaboxes.php             # CMB2 Metaboxes
│   │       │   ├── polylang.php              # Integración Polylang
│   │       │   ├── ajax.php                  # Handlers AJAX
│   │       │   ├── walker.php                # Custom menu walker
│   │       │   └── helpers.php               # Funciones helper
│   │       │
│   │       ├── 📂 template-parts/            # Componentes
│   │       │   │
│   │       │   ├── 📂 components/            # Componentes reutilizables
│   │       │   │   ├── hero.php              # Sección hero
│   │       │   │   ├── servicios.php         # Grid servicios
│   │       │   │   ├── beneficios.php        # Lista beneficios
│   │       │   │   ├── estadisticas.php      # Contadores
│   │       │   │   ├── testimonios.php       # Slider testimonios
│   │       │   │   ├── cta.php               # Call to action
│   │       │   │   ├── proceso.php           # Pasos proceso
│   │       │   │   ├── casos-exito.php       # Grid casos
│   │       │   │   ├── contacto-form.php     # Formulario
│   │       │   │   └── mapa.php              # Google Maps
│   │       │   │
│   │       │   ├── 📂 sections/              # Secciones estructurales
│   │       │   │   ├── header.php            # Header interno
│   │       │   │   ├── navigation.php        # Navegación
│   │       │   │   ├── footer-widgets.php    # Widgets footer
│   │       │   │   └── mobile-menu.php       # Menú móvil
│   │       │   │
│   │       │   └── 📂 cards/                 # Tarjetas
│   │       │       ├── servicio-card.php     # Card servicio
│   │       │       ├── testimonio-card.php   # Card testimonio
│   │       │       └── caso-card.php         # Card caso
│   │       │
│   │       ├── 📂 templates/                 # Templates de página
│   │       │   ├── front-page.php            # Home
│   │       │   ├── page-quienes.php          # Quiénes Somos
│   │       │   ├── page-proceso.php          # Nuestro Proceso
│   │       │   ├── page-casos.php            # Casos de Éxito
│   │       │   ├── page-contacto.php         # Contacto
│   │       │   ├── single-casos_exito.php    # Single caso
│   │       │   ├── archive-casos_exito.php   # Archivo casos
│   │       │   └── template-blank.php        # Blank template
│   │       │
│   │       └── 📂 languages/                 # Traducciones
│   │           ├── translatio.pot            # Template
│   │           ├── translatio-es_ES.po       # Español
│   │           ├── translatio-en_US.po       # English
│   │           ├── translatio-pt_BR.po       # Português
│   │           ├── translatio-zh_CN.po       # 中文
│   │           └── translatio-fr_FR.po       # Français
│   │
│   └── 📂 plugins/                           # Plugins (instalar)
│       ├── polylang/                         # Multilenguaje
│       ├── cmb2/                             # Custom fields
│       ├── wordpress-seo/                    # Yoast SEO
│       ├── wp-super-cache/                   # Cache
│       ├── contact-form-7/                   # Formularios
│       ├── wp-mail-smtp/                     # Email
│       ├── wordfence/                        # Seguridad
│       ├── wp-optimize/                      # Optimización
│       └── smush/                            # Imágenes
│
├── 📂 src/                                   # DESARROLLO
│   │
│   ├── 📂 scss/                              # Estilos SCSS
│   │   ├── main.scss                         # Entry point
│   │   │
│   │   ├── 📂 base/
│   │   │   ├── _variables.scss               # Variables globales
│   │   │   ├── _reset.scss                   # Reset CSS
│   │   │   ├── _typography.scss              # Tipografía
│   │   │   └── _mixins.scss                  # Mixins SCSS
│   │   │
│   │   ├── 📂 components/
│   │   │   ├── _buttons.scss                 # Botones
│   │   │   ├── _cards.scss                   # Tarjetas
│   │   │   ├── _forms.scss                   # Formularios
│   │   │   ├── _hero.scss                    # Hero section
│   │   │   ├── _navigation.scss              # Navegación
│   │   │   ├── _testimonios.scss             # Testimonios
│   │   │   ├── _estadisticas.scss            # Contadores
│   │   │   └── _cta.scss                     # CTAs
│   │   │
│   │   ├── 📂 layouts/
│   │   │   ├── _header.scss                  # Header
│   │   │   ├── _footer.scss                  # Footer
│   │   │   ├── _sidebar.scss                 # Sidebar
│   │   │   ├── _pages.scss                   # Páginas
│   │   │   └── _archive.scss                 # Archivos
│   │   │
│   │   └── 📂 utilities/
│   │       ├── _animations.scss              # Animaciones
│   │       ├── _responsive.scss              # Media queries
│   │       └── _utilities.scss               # Clases utilitarias
│   │
│   ├── 📂 js/                                # JavaScript
│   │   ├── main.js                           # Entry point
│   │   ├── navigation.js                     # Menú móvil
│   │   ├── forms.js                          # Validación forms
│   │   ├── animations.js                     # Animaciones scroll
│   │   └── language-switcher.js              # Selector idioma
│   │
│   └── 📂 build/                             # Configuración build
│       ├── webpack.config.js                 # Webpack config
│       ├── package.json                      # Dependencies
│       ├── postcss.config.js                 # PostCSS config
│       └── .babelrc                          # Babel config
│
├── 📂 config/                                # Configuración servidor
│   ├── wp-config-optimizado.php              # wp-config optimizado
│   ├── .htaccess-seguro                      # Apache hardening
│   ├── nginx.conf                            # Nginx config
│   └── php.ini                               # PHP optimizado
│
├── 📂 backups/                               # Backups
│   ├── backup-daily.sh                       # Script backup diario
│   ├── backup-weekly.sh                      # Script backup semanal
│   └── restore.sh                            # Script restauración
│
├── 📂 docs/                                  # Documentación
│   ├── README.md                             # Documentación general
│   ├── DISCOVERY.md                          # Fase 1
│   ├── REQUIREMENTS.md                       # Fase 2
│   ├── ARCHITECTURE.md                       # Fase 3 (este archivo)
│   ├── UX_UI.md                              # Fase 4
│   ├── BACKEND.md                            # Fase 5
│   ├── FRONTEND.md                           # Fase 6
│   ├── INTEGRATION.md                        # Fase 7
│   ├── DEVOPS.md                             # Fase 8
│   ├── SECURITY.md                           # Fase 9
│   ├── TESTING.md                            # Fase 10
│   ├── CHECKLIST_PRELAUNCH.md                # Checklist
│   └── INSTRUCCIONES_DEPLOYMENT.md           # Deployment
│
├── 📄 .gitignore                             # Git ignore
├── 📄 composer.json                          # PHP dependencies
├── 📄 README.md                              # Proyecto readme
└── 📄 CHANGELOG.md                           # Cambios
```

---

## ⚙️ CONFIGURACIÓN WEBPACK

### webpack.config.js
```javascript
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';

    return {
        entry: {
            main: './src/js/main.js',
            styles: './src/scss/main.scss'
        },

        output: {
            path: path.resolve(__dirname, '../wp-content/themes/translatio/assets'),
            filename: 'js/[name].min.js',
            clean: true
        },

        module: {
            rules: [
                // JavaScript
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },

                // SCSS
                {
                    test: /\.scss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        {
                            loader: 'postcss-loader',
                            options: {
                                postcssOptions: {
                                    plugins: [
                                        require('autoprefixer'),
                                        require('cssnano')({
                                            preset: 'default'
                                        })
                                    ]
                                }
                            }
                        },
                        'sass-loader'
                    ]
                }
            ]
        },

        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/[name].min.css'
            })
        ],

        optimization: {
            minimize: isProduction,
            minimizer: [
                new TerserPlugin(),
                new CssMinimizerPlugin()
            ]
        },

        devtool: isProduction ? false : 'source-map',

        watch: !isProduction
    };
};
```

### package.json
```json
{
  "name": "translatio-global",
  "version": "1.0.0",
  "description": "Translatio Global - Sitio multilenguaje de subrogación",
  "scripts": {
    "dev": "webpack --mode development --watch",
    "build": "webpack --mode production",
    "build:prod": "NODE_ENV=production npm run build",
    "lint:scss": "stylelint 'src/scss/**/*.scss'",
    "lint:js": "eslint src/js/**/*.js"
  },
  "devDependencies": {
    "@babel/core": "^7.23.0",
    "@babel/preset-env": "^7.23.0",
    "autoprefixer": "^10.4.16",
    "babel-loader": "^9.1.3",
    "css-loader": "^6.8.1",
    "cssnano": "^6.0.1",
    "mini-css-extract-plugin": "^2.7.6",
    "postcss": "^8.4.31",
    "postcss-loader": "^7.3.3",
    "sass": "^1.69.5",
    "sass-loader": "^13.3.2",
    "terser-webpack-plugin": "^5.3.9",
    "webpack": "^5.89.0",
    "webpack-cli": "^5.1.4"
  },
  "browserslist": [
    "> 1%",
    "last 2 versions",
    "not dead"
  ]
}
```

---

## 🎨 ESTRUCTURA SCSS

### _variables.scss
```scss
// ============================================
// TRANSLATIO GLOBAL - Variables SCSS
// ============================================

// ----- COLORES -----

// Paleta Europa/América
$colors-europe: (
    'primary': #1e3a5f,      // Azul oscuro (confianza)
    'secondary': #c9a227,    // Dorado (premium)
    'accent': #2e7d32,       // Verde (éxito)
    'text': #2c3e50,         // Gris oscuro
    'light': #f8f9fa,        // Fondo claro
    'white': #ffffff,
    'black': #000000,
    'gray-100': #f8f9fa,
    'gray-200': #e9ecef,
    'gray-300': #dee2e6,
    'gray-400': #ced4da,
    'gray-500': #adb5bd,
    'gray-600': #6c757d,
    'gray-700': #495057,
    'gray-800': #343a40,
    'gray-900': #212529
);

// Paleta China (para :lang(zh))
$colors-china: (
    'primary': #8b0000,      // Rojo (suerte)
    'secondary': #ffd700,    // Dorado (prosperidad)
    'accent': #1e3a5f        // Azul (confianza)
);

// Variables de color
$primary: map-get($colors-europe, 'primary');
$secondary: map-get($colors-europe, 'secondary');
$accent: map-get($colors-europe, 'accent');
$text-color: map-get($colors-europe, 'text');
$light-bg: map-get($colors-europe, 'light');

// ----- TIPOGRAFÍA -----

// Familias
$font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
$font-chinese: 'Noto Sans SC', 'Microsoft YaHei', 'SimHei', sans-serif;

// Tamaños
$font-sizes: (
    'xs': 0.75rem,     // 12px
    'sm': 0.875rem,    // 14px
    'base': 1rem,      // 16px
    'md': 1.125rem,    // 18px
    'lg': 1.25rem,     // 20px
    'xl': 1.5rem,      // 24px
    '2xl': 1.875rem,   // 30px
    '3xl': 2.25rem,    // 36px
    '4xl': 3rem,       // 48px
    '5xl': 3.75rem     // 60px
);

// Weights
$font-weights: (
    'light': 300,
    'normal': 400,
    'medium': 500,
    'semibold': 600,
    'bold': 700
);

// Line heights
$line-heights: (
    'tight': 1.25,
    'normal': 1.5,
    'relaxed': 1.75
);

// ----- ESPACIADO -----

$spacing: (
    '0': 0,
    '1': 0.25rem,    // 4px
    '2': 0.5rem,     // 8px
    '3': 0.75rem,    // 12px
    '4': 1rem,       // 16px
    '5': 1.25rem,    // 20px
    '6': 1.5rem,     // 24px
    '8': 2rem,       // 32px
    '10': 2.5rem,    // 40px
    '12': 3rem,      // 48px
    '16': 4rem,      // 64px
    '20': 5rem,      // 80px
    '24': 6rem       // 96px
);

// ----- BREAKPOINTS -----

$breakpoints: (
    'sm': 576px,     // Móviles landscape
    'md': 768px,     // Tablets
    'lg': 992px,     // Desktop
    'xl': 1200px,    // Desktop grande
    'xxl': 1400px    // Desktop extra grande
);

// ----- SOMBRAS -----

$shadows: (
    'sm': 0 1px 2px rgba(0, 0, 0, 0.05),
    'md': 0 4px 6px rgba(0, 0, 0, 0.1),
    'lg': 0 10px 15px rgba(0, 0, 0, 0.1),
    'xl': 0 20px 25px rgba(0, 0, 0, 0.15)
);

// ----- BORDES -----

$border-radius: (
    'none': 0,
    'sm': 0.25rem,
    'md': 0.5rem,
    'lg': 1rem,
    'xl': 1.5rem,
    'full': 9999px
);

// ----- TRANSICIONES -----

$transitions: (
    'fast': 150ms ease,
    'normal': 300ms ease,
    'slow': 500ms ease
);

// ----- Z-INDEX -----

$z-index: (
    'dropdown': 1000,
    'sticky': 1020,
    'fixed': 1030,
    'modal-backdrop': 1040,
    'modal': 1050,
    'popover': 1060,
    'tooltip': 1070
);
```

### _mixins.scss
```scss
// ============================================
// TRANSLATIO GLOBAL - Mixins SCSS
// ============================================

// ----- MEDIA QUERIES -----

@mixin respond-to($breakpoint) {
    @if map-has-key($breakpoints, $breakpoint) {
        @media (min-width: map-get($breakpoints, $breakpoint)) {
            @content;
        }
    }
}

@mixin respond-below($breakpoint) {
    @if map-has-key($breakpoints, $breakpoint) {
        @media (max-width: map-get($breakpoints, $breakpoint) - 1) {
            @content;
        }
    }
}

// ----- FLEXBOX -----

@mixin flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}

@mixin flex-between {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

// ----- TIPOGRAFÍA -----

@mixin heading($size: '2xl') {
    font-family: $font-primary;
    font-size: map-get($font-sizes, $size);
    font-weight: map-get($font-weights, 'bold');
    line-height: map-get($line-heights, 'tight');
    color: $text-color;
}

@mixin body-text {
    font-family: $font-primary;
    font-size: map-get($font-sizes, 'base');
    font-weight: map-get($font-weights, 'normal');
    line-height: map-get($line-heights, 'normal');
}

// Para chino
@mixin chinese-font {
    :lang(zh) & {
        font-family: $font-chinese;
    }
}

// ----- BOTONES -----

@mixin btn-base {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: map-get($spacing, '3') map-get($spacing, '6');
    font-size: map-get($font-sizes, 'base');
    font-weight: map-get($font-weights, 'medium');
    text-decoration: none;
    border: 2px solid transparent;
    border-radius: map-get($border-radius, 'md');
    cursor: pointer;
    transition: all map-get($transitions, 'normal');

    &:hover,
    &:focus {
        text-decoration: none;
        outline: none;
    }
}

@mixin btn-primary {
    @include btn-base;
    background-color: $primary;
    color: white;
    border-color: $primary;

    &:hover {
        background-color: darken($primary, 10%);
        border-color: darken($primary, 10%);
    }
}

@mixin btn-secondary {
    @include btn-base;
    background-color: $secondary;
    color: $primary;
    border-color: $secondary;

    &:hover {
        background-color: darken($secondary, 10%);
        border-color: darken($secondary, 10%);
    }
}

@mixin btn-outline {
    @include btn-base;
    background-color: transparent;
    color: $primary;
    border-color: $primary;

    &:hover {
        background-color: $primary;
        color: white;
    }
}

// ----- TARJETAS -----

@mixin card-base {
    background: white;
    border-radius: map-get($border-radius, 'lg');
    box-shadow: map-get($shadows, 'md');
    overflow: hidden;
    transition: all map-get($transitions, 'normal');

    &:hover {
        box-shadow: map-get($shadows, 'lg');
        transform: translateY(-4px);
    }
}

// ----- ANIMACIONES -----

@mixin fade-in {
    animation: fadeIn map-get($transitions, 'normal');
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@mixin slide-up {
    animation: slideUp map-get($transitions, 'normal');
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

// ----- UTILIDADES -----

@mixin visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

@mixin truncate($lines: 1) {
    @if $lines == 1 {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    } @else {
        display: -webkit-box;
        -webkit-line-clamp: $lines;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
}

// ----- CONTENEDOR -----

@mixin container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding-left: map-get($spacing, '4');
    padding-right: map-get($spacing, '4');

    @include respond-to('md') {
        padding-left: map-get($spacing, '6');
        padding-right: map-get($spacing, '6');
    }
}

@mixin section-padding {
    padding-top: map-get($spacing, '12');
    padding-bottom: map-get($spacing, '12');

    @include respond-to('lg') {
        padding-top: map-get($spacing, '20');
        padding-bottom: map-get($spacing, '20');
    }
}
```

---

## 🔌 INTEGRACIÓN CMB2 + POLYLANG

### Flujo de traducción de campos personalizados:

```php
// inc/polylang.php

/**
 * Registrar campos CMB2 para traducción con Polylang
 */
function translatio_pll_register_cmb2_fields() {
    if (!function_exists('pll_register_string')) {
        return;
    }

    // Registrar grupo de traducciones
    $group = 'Translatio CMB2';

    // Strings de campos repetibles
    $cmb2_boxes = CMB2_Boxes::get_all();

    foreach ($cmb2_boxes as $cmb_id => $cmb) {
        foreach ($cmb->prop('fields') as $field) {
            // Registrar campos de tipo text y textarea
            if (in_array($field['type'], ['text', 'text_small', 'textarea', 'textarea_small'])) {
                $field_id = $field['id'];

                // Obtener valores de todos los posts
                $posts = get_posts([
                    'post_type' => $cmb->prop('object_types'),
                    'posts_per_page' => -1,
                    'post_status' => 'any'
                ]);

                foreach ($posts as $post) {
                    $value = get_post_meta($post->ID, $field_id, true);
                    if ($value && is_string($value)) {
                        pll_register_string($field_id, $value, $group);
                    }
                }
            }
        }
    }
}
add_action('cmb2_init', 'translatio_pll_register_cmb2_fields', 20);

/**
 * Obtener campo traducido
 */
function translatio_get_translated_field($post_id, $field_id, $default = '') {
    $value = get_post_meta($post_id, $field_id, true);

    if (function_exists('pll__')) {
        return pll__($value) ?: $default;
    }

    return $value ?: $default;
}
```

---

## 🔗 REST API ENDPOINTS

### Endpoints personalizados:

```php
// inc/api.php

/**
 * Registrar endpoints REST API
 */
function translatio_register_api_routes() {
    register_rest_route('translatio/v1', '/casos', [
        'methods' => 'GET',
        'callback' => 'translatio_api_get_casos',
        'permission_callback' => '__return_true',
        'args' => [
            'tipo' => [
                'validate_callback' => function($param) {
                    return in_array($param, ['arrendamiento', 'comercial', 'inmobiliario']);
                }
            ],
            'per_page' => [
                'default' => 6,
                'validate_callback' => 'is_numeric'
            ]
        ]
    ]);

    register_rest_route('translatio/v1', '/testimonios', [
        'methods' => 'GET',
        'callback' => 'translatio_api_get_testimonios',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('translatio/v1', '/contact', [
        'methods' => 'POST',
        'callback' => 'translatio_api_contact',
        'permission_callback' => '__return_true'
    ]);
}
add_action('rest_api_init', 'translatio_register_api_routes');

/**
 * GET /wp-json/translatio/v1/casos
 */
function translatio_api_get_casos($request) {
    $tipo = $request->get_param('tipo');
    $per_page = $request->get_param('per_page');
    $page = $request->get_param('page') ?: 1;

    $args = [
        'post_type' => 'casos_exito',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'post_status' => 'publish'
    ];

    if ($tipo) {
        $args['meta_query'] = [
            [
                'key' => 'caso_tipo',
                'value' => $tipo
            ]
        ];
    }

    $query = new WP_Query($args);

    $casos = [];
    foreach ($query->posts as $post) {
        $casos[] = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt,
            'tipo' => get_post_meta($post->ID, 'caso_tipo', true),
            'cliente' => get_post_meta($post->ID, 'caso_cliente', true),
            'duracion' => get_post_meta($post->ID, 'caso_duracion', true),
            'destacado' => get_post_meta($post->ID, 'caso_destacado', true),
            'link' => get_permalink($post->ID)
        ];
    }

    return new WP_REST_Response([
        'data' => $casos,
        'total' => $query->found_posts,
        'pages' => $query->max_num_pages,
        'page' => $page
    ], 200);
}
```

---

## ✅ ENTREGABLES ARCHITECTURE

- [x] Estructura de archivos WordPress diseñada
- [x] Webpack configurado para SCSS + JS
- [x] Estructura de templates definida
- [x] Integración CMB2 + Polylang planificada
- [x] REST API endpoints definidos
- [x] Variables SCSS y mixins documentados

---

**Estado:** ✅ FASE 3 COMPLETADA
**Siguiente:** FASE 4 - UX/UI

---
*Architect Agent - Molino Translatio Global*
