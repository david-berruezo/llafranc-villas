# Llafranc Villas — Alquiler Vacacional Costa Brava

Child-theme de **WP Rentals** para [llvillas.com](https://www.llvillas.com/), plataforma de alquiler vacacional de apartamentos y villas en la **Costa Brava** (Girona). El proyecto gestiona más de 300 propiedades distribuidas en múltiples destinos, con sistema de reservas online, búsqueda avanzada por área/tipo/huéspedes y soporte multiidioma (Español, Català, Français, English).

[![WordPress](https://img.shields.io/badge/WordPress-5.x%20|%206.x-21759B?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![WP Rentals](https://img.shields.io/badge/WP%20Rentals-Theme-FF6600)](https://wprentals.org/)
[![PHP](https://img.shields.io/badge/PHP-7.x%20|%208.x-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL--3.0-blue.svg)](LICENSE)

🌐 **Sitio en producción:** [www.llvillas.com](https://www.llvillas.com/)

---

## Tabla de Contenidos

- [Sobre el Proyecto](#sobre-el-proyecto)
- [Características](#características)
- [Destinos Costa Brava](#destinos-costa-brava)
- [Arquitectura](#arquitectura)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Tema Base: WP Rentals](#tema-base-wp-rentals)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Personalización del Child Theme](#personalización-del-child-theme)
- [Tecnologías](#tecnologías)
- [Recursos](#recursos)
- [Autor](#autor)

---

## Sobre el Proyecto

**Llafranc Villas** es una agencia de alquiler vacacional con más de 50 años de experiencia (desde 1970), pionera en alquileres turísticos en la Costa Brava. La web gestiona un catálogo de más de 300 propiedades (apartamentos, villas y villas de lujo) distribuidas en localidades como Llafranc, Calella de Palafrugell, Lloret de Mar, Pals, Tamariu, Blanes y más.

La plataforma está construida sobre WordPress con el tema premium **WP Rentals**, especializado en gestión de propiedades inmobiliarias y alquiler vacacional. Este repositorio contiene el **child-theme** (`wp-rentals-child`) con todas las personalizaciones específicas del proyecto.

### Oficinas

| Oficina | Dirección | Teléfono | Email |
|---|---|---|---|
| **Llafranc** | C/ Xaloc, nº 5 – 17211 Llafranc (Girona) | +34 972 30 54 12 | llafranc@llvillas.com |
| **Lloret de Mar** | Av. Vila de Tossa, nº 80 – 17310 Lloret de Mar (Girona) | +34 972 37 28 82 | lloret@llvillas.com |

---

## Características

### Gestión de Propiedades
- Catálogo de **+300 propiedades**: apartamentos, villas y villas de lujo
- Fichas detalladas con galería de imágenes, ubicación en mapa, capacidad de huéspedes, habitaciones y baños
- Sistema de **ofertas y descuentos** con fechas de vigencia
- Categorización por tipo de propiedad (Apartamento, Villa, Villa de Lujo)

### Búsqueda y Filtros
- Búsqueda avanzada por **área/destino**, **tipo de propiedad** y **número de huéspedes**
- Filtrado por fechas de entrada y salida (disponibilidad)
- Navegación por destinos con listado de propiedades por zona

### Reservas Online
- Sistema de reservas integrado con calendario de disponibilidad
- Registro de usuarios (inquilinos y propietarios)
- Login social con **Facebook** y **Google**
- Formulario de contacto con selección de propiedad y fechas

### Multiidioma
- **Español** (idioma principal)
- **Català**
- **Français**
- **English**
- Implementado con **Polylang** para traducción completa de contenidos y propiedades

### Contenido Adicional
- **Blog** con noticias y artículos sobre la Costa Brava
- **Testimonios** de clientes
- Páginas de destinos con información turística
- Sección "Sobre nosotros" con historia de la empresa

---

## Destinos Costa Brava

La plataforma cubre los siguientes destinos y sub-áreas:

| Destino | Sub-áreas | Propiedades aprox. |
|---|---|---|
| **Llafranc** | Centro Urbano, Faro, Platja de Llafranc | ~90 |
| **Lloret de Mar** | Canyelles, Fenals, Lloret Blau, Lloret Residencial, Serra Brava, Aiguaviva Park, Lloret Verd | ~58 |
| **Calella de Palafrugell** | — | ~38 |
| **Vidreres** | — | ~13 |
| **Blanes** | — | ~9 |
| **Pals** | Platja de Pals | ~6 |
| **Palafrugell** | Esclanyà | ~1 |
| **Tamariu** | — | ~1 |
| **Torroella de Montgrí** | — | ~1 |
| **Begur** | — | ~1 |

---

## Arquitectura

El proyecto sigue la arquitectura estándar de WordPress con child-theme:

```
┌─────────────────────────────────────────────┐
│                  WordPress                   │
├─────────────────────────────────────────────┤
│  Tema padre: WP Rentals                     │
│  ├── Sistema de propiedades (CPT)           │
│  ├── Reservas y calendario                  │
│  ├── Búsqueda avanzada                      │
│  └── Gestión de usuarios                    │
├─────────────────────────────────────────────┤
│  Child-theme: wp-rentals-child              │
│  ├── Personalizaciones de diseño            │
│  ├── Templates override                     │
│  ├── Funcionalidades custom                 │
│  └── Estilos y scripts propios              │
├─────────────────────────────────────────────┤
│  Plugins                                     │
│  ├── Polylang (multiidioma)                 │
│  ├── Elementor / WPBakery (page builder)    │
│  ├── Contact Form 7                         │
│  ├── Social Login (Facebook, Google)        │
│  └── SEO, caché, cookies, etc.             │
└─────────────────────────────────────────────┘
```

---

## Estructura del Proyecto

El repositorio contiene el directorio `wp-content/` con el child-theme y las personalizaciones:

```
llafranc-villas/
├── .idea/                       # Configuración del IDE (PhpStorm)
├── wp-content/
│   └── themes/
│       └── wp-rentals-child/    # Child-theme de WP Rentals
│           ├── style.css        # Estilos del child-theme (hereda del padre)
│           ├── functions.php    # Funciones personalizadas
│           ├── templates/       # Templates PHP override
│           ├── css/             # Estilos personalizados
│           ├── js/              # Scripts personalizados
│           └── ...
├── .gitignore
├── LICENSE                      # GPL-3.0
└── README.md
```

> **Nota:** El repositorio solo incluye el contenido de `wp-content/` con las personalizaciones. El core de WordPress y el tema padre WP Rentals no se versionan (se instalan por separado).

---

## Tema Base: WP Rentals

[WP Rentals](https://wprentals.org/) es un tema premium de WordPress especializado en alquiler de propiedades. Proporciona:

- **Custom Post Type `estate_property`**: gestión completa de propiedades con campos personalizados (precio, ubicación, capacidad, amenities, galería, etc.)
- **Sistema de reservas**: calendario de disponibilidad, solicitudes de reserva, pagos online
- **Taxonomías personalizadas**: `property_area` (destinos), `property_category` (tipos), `property_action` (acciones)
- **Búsqueda avanzada**: filtros por área, tipo, precio, huéspedes, fechas, amenities
- **Panel de usuario**: dashboard para propietarios e inquilinos
- **Mapas integrados**: Google Maps con ubicación de propiedades
- **Responsive design**: adaptado a móvil, tablet y desktop

---

## Requisitos

- **WordPress** 5.x / 6.x
- **PHP** >= 7.4
- **MySQL** >= 5.7 o MariaDB >= 10.3
- **Tema WP Rentals** (licencia premium requerida) — [wprentals.org](https://wprentals.org/)
- **Plugins recomendados:**
  - Polylang (multiidioma)
  - Elementor o WPBakery Page Builder
  - Contact Form 7
  - Complianz (cookies GDPR)

---

## Instalación

### Paso 1: Instalar WordPress y WP Rentals

```bash
# Descargar WordPress
wp core download --locale=es_ES

# Crear base de datos y configurar wp-config.php
wp config create --dbname=llvillas --dbuser=root --dbpass=password

# Instalar WordPress
wp core install --url=llvillas.local --title="Llafranc Villas" \
  --admin_user=admin --admin_password=password --admin_email=admin@llvillas.com
```

Instalar el tema padre **WP Rentals** desde el panel de administración: **Apariencia > Temas > Añadir nuevo > Subir tema**.

### Paso 2: Instalar el Child Theme

```bash
# Clonar el repositorio
git clone https://github.com/david-berruezo/llafranc-villas.git

# Copiar el child-theme al directorio de WordPress
cp -r llafranc-villas/wp-content/themes/wp-rentals-child /var/www/html/wordpress/wp-content/themes/

# Establecer permisos
chmod -R 755 /var/www/html/wordpress/wp-content/themes/wp-rentals-child
chown -R www-data:www-data /var/www/html/wordpress/wp-content/themes/wp-rentals-child
```

### Paso 3: Activar el Child Theme

1. Ir a **Apariencia > Temas** en el panel de administración
2. Activar **WP Rentals Child**
3. Configurar el tema desde **Apariencia > Personalizar**

### Paso 4: Instalar Plugins Necesarios

```bash
# Instalar plugins con WP-CLI
wp plugin install polylang --activate
wp plugin install contact-form-7 --activate
```

### Paso 5: Configurar Multiidioma

1. Ir a **Idiomas > Ajustes** en el panel de administración
2. Añadir los idiomas: Español, Català, Français, English
3. Configurar las traducciones de propiedades y páginas

---

## Personalización del Child Theme

### Modificar Estilos

Los estilos personalizados se añaden en el archivo `style.css` del child-theme. El child-theme hereda todos los estilos del tema padre WP Rentals y permite sobreescribirlos:

```css
/*
Theme Name: WP Rentals Child
Template: wprentals
*/

/* Personalizaciones de Llafranc Villas */
.property-listing .listing-title {
    font-family: 'Montserrat', sans-serif;
}
```

### Modificar Funcionalidades

Las funcionalidades personalizadas se añaden en `functions.php`:

```php
<?php
// Enqueue estilos del child-theme
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
});
```

### Override de Templates

Para personalizar templates del tema padre, copiar el archivo al child-theme manteniendo la misma estructura de directorios. WordPress cargará automáticamente la versión del child-theme.

---

## Tecnologías

| Tecnología | Uso |
|---|---|
| **WordPress** | CMS y gestión de contenidos |
| **WP Rentals** | Tema base para alquiler de propiedades |
| **PHP** | Lógica del servidor y templates |
| **JavaScript** | Interactividad front-end (mapas, calendarios, filtros) |
| **CSS / SCSS / Less** | Estilos y diseño responsive |
| **MySQL** | Base de datos de propiedades, reservas y usuarios |
| **Polylang** | Gestión multiidioma |
| **Google Maps API** | Mapas de ubicación de propiedades |
| **Facebook / Google OAuth** | Login social |

---

## Recursos

### WP Rentals

- [WP Rentals — Sitio oficial](https://wprentals.org/)
- [WP Rentals — Documentación](https://developer.developer.developer/)
- [WP Rentals — ThemeForest](https://themeforest.net/item/wp-rentals-booking-rental-wordpress-theme/12921802)

### WordPress Development

- [WordPress Developer Resources](https://developer.wordpress.org/)
- [Child Themes — WordPress Handbook](https://developer.wordpress.org/themes/advanced-topics/child-themes/)
- [Custom Post Types](https://developer.wordpress.org/plugins/post-types/)
- [Polylang — Documentación](https://polylang.pro/doc/)

### Proyecto en Producción

- [www.llvillas.com](https://www.llvillas.com/) — Sitio web en producción
- [Propiedades](https://www.llvillas.com/propiedades/) — Catálogo completo de propiedades
- [Destinos](https://www.llvillas.com/destinos/) — Destinos en la Costa Brava

---

## Autor

**David Berruezo** — Software Engineer | Fullstack Developer

- GitHub: [@david-berruezo](https://github.com/david-berruezo)
- Website: [davidberruezo.com](https://www.davidberruezo.com)

---

## Licencia

Este proyecto está licenciado bajo **GPL-3.0**. Consulta el archivo [LICENSE](LICENSE) para más detalles.
