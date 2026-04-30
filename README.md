# RestauranteCMS

**RestauranteCMS** es un sistema web completo para restaurantes, construido con **Laravel 12**, **Filament 3**, **Livewire 3** y **Tailwind CSS v4**. Permite gestionar desde un panel de administración el sitio público del restaurante: menú, páginas, reseñas, contacto, promociones y configuración general — sin necesidad de código.

El sistema fue diseñado para ser **reutilizable entre distintos restaurantes**: todo el contenido es dinámico y configurable desde el CMS. No hay información de ningún negocio específico hardcodeada en el código.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.2 · Laravel 12 |
| Panel Admin | Filament 3 (tema Amber) |
| Frontend reactivo | Livewire 3 · Alpine.js |
| Estilos | Tailwind CSS v4 · Vite |
| Base de datos | MySQL / MariaDB |
| Colas | Laravel Queues (`database` driver) |
| Permisos | Spatie Laravel Permission v6 |
| Fuentes | Anton (display) + Inter (body) |

---

## Características principales

### Panel de administración (Filament)
- **Configuración General** (`SiteInfo`) — nombre del sitio, logo, favicon, dirección, teléfono, WhatsApp, horarios, redes sociales, tagline, tipo de cocina, rango de precios, aviso de privacidad y datos SEO completos (título, descripción, keywords, OG image, Twitter Card, JSON-LD Schema.org)
- **Constructor de páginas** — editor visual por bloques (`builder_content`) para crear y editar páginas sin código. Cada bloque tiene campos propios configurables
- **Menú digital** — categorías, productos (nombre, descripción, precio, imagen, destacado), menús agrupados por secciones y ordenables
- **Reseñas** — campañas con código de regalo único, formulario de reseña por QR, visualización de últimas reseñas (4 y 5 estrellas) en el sitio
- **Contacto** — formulario configurable (campos dinámicos), bandeja de mensajes con gestión (atendido / pendiente), notas de admin
- **Usuarios y roles** — control de acceso con Spatie Permissions
- **Páginas vistas** — registro automático de vistas por página

### Sitio público
- Navegación 100% dinámica desde el CMS (páginas con `show_in_nav`)
- SEO completo: meta tags, Open Graph, Twitter Card, JSON-LD Schema.org Restaurant
- Botón flotante de WhatsApp (solo aparece si hay número configurado)
- Banner de cookies con localStorage key dinámico por restaurante
- Vista previa de páginas no publicadas (URL firmada con expiración)
- Aviso de privacidad editable desde el admin
- Redirección QR permanente al menú activo

---

## Bloques del constructor de páginas

| Bloque | Descripción |
|---|---|
| `hero` | Sección hero con video/imagen de fondo, heading, badge y imagen lateral |
| `featured_products` | Grilla de productos destacados del menú |
| `promotions_carousel` | Carrusel de imágenes con título y enlace opcional |
| `about_section` | Sección "Nosotros" con texto e imagen |
| `ahumado_section` | Sección de proceso/especialidad con video YouTube y mosaico de imágenes |
| `reviews_section` | Últimas 5 reseñas de 4 y 5 estrellas |
| `contact_map_section` | Mapa + datos de contacto (se alimenta de Configuración General) |
| `contact_form` | Formulario de contacto configurable con campos dinámicos |
| `taqueria_section` | Sección de horario nocturno / segundo concepto con fondo personalizable |
| `social_feed` | Embeds de posts de redes sociales (TikTok, Instagram, etc.) |

---

## Estructura del proyecto

```
app/
├── Filament/Resources/         # Panel admin
│   ├── CategoryResource/
│   ├── ContactSubmissionResource/
│   ├── MenuResource/
│   ├── PageResource/           # Constructor de bloques
│   ├── PageViewResource/
│   ├── ProductResource/
│   ├── ReviewCampaignResource/
│   ├── ReviewSubmissionResource/
│   ├── SiteInfoResource/       # Configuración General
│   └── UserResource/
├── Livewire/                   # Componentes del sitio público
│   ├── BbqPage.php
│   ├── ContactForm.php
│   ├── GenericPage.php         # Catch-all para páginas CMS
│   ├── HomePage.php
│   ├── MenuPage.php
│   ├── PrivacyPage.php
│   ├── ReviewForm.php          # Formulario con token (mesero)
│   ├── ReviewFormPublic.php    # Formulario de campaña activa
│   └── TaqueriaPage.php
├── Models/
│   ├── Category.php
│   ├── ContactSubmission.php
│   ├── Menu.php
│   ├── MenuSection.php
│   ├── Page.php
│   ├── PageView.php
│   ├── Product.php
│   ├── ReviewCampaign.php
│   ├── ReviewSubmission.php
│   ├── SiteInfo.php
│   └── User.php
├── helpers.php                 # siteInfo() y siteName() helpers globales
└── Providers/Filament/
    └── AdminPanelProvider.php

resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php       # Layout principal del sitio
│   │   └── review.blade.php    # Layout para formularios de reseña
│   └── social-icon.blade.php
├── emails/
│   ├── contact-form.blade.php
│   └── review-gift.blade.php
├── errors/
│   └── 404.blade.php
└── livewire/
    ├── home-page.blade.php
    ├── generic-page.blade.php
    ├── menu-page.blade.php
    ├── review-form.blade.php
    ├── review-form-public.blade.php
    ├── privacy-page.blade.php
    └── ...

routes/web.php                  # Rutas públicas + catch-all CMS
database/seeders/DatabaseSeeder.php  # Datos demo genéricos
```

---

## Instalación

### Requisitos
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL / MariaDB

### Pasos

```bash
# 1. Clonar el repositorio
git clone <repo-url> mi-restaurante
cd mi-restaurante

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env
# DB_DATABASE=mi_restaurante
# DB_USERNAME=root
# DB_PASSWORD=...

# 6. Ejecutar migraciones y seeder
php artisan migrate --seed

# 7. Crear enlace de storage
php artisan storage:link

# 8. Compilar assets
npm run build
# o en desarrollo:
npm run dev
```

### Acceso al panel admin

```
URL:      /admin
Email:    admin@restaurante.test
Password: password
```

> **Importante:** cambiar las credenciales después del primer acceso.

---

## Configuración inicial para un nuevo restaurante

Una vez instalado, acceder al panel admin y completar:

1. **Configuración General** (`/admin/site-infos`) — nombre del restaurante, logo, favicon, dirección, teléfono, WhatsApp, horarios, tagline, tipo de cocina, aviso de privacidad y datos SEO
2. **Páginas** — editar o crear las páginas del sitio con el constructor de bloques
3. **Menú** — crear categorías, productos y estructurar el menú
4. **Campaña de reseñas** — activar o crear una campaña para recolectar reseñas de clientes

---

## Variables de entorno relevantes

```env
APP_NAME="Mi Restaurante"   # Fallback si SiteInfo aún no tiene site_name
APP_URL=https://mirestaurante.com

DB_CONNECTION=mysql
DB_DATABASE=mi_restaurante
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database   # Requerido para envío de correos en cola

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@mirestaurante.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Helpers globales

```php
// Obtiene el registro SiteInfo (con caché estático — 1 query por request)
siteInfo(): ?SiteInfo

// Devuelve el nombre del restaurante desde SiteInfo->site_name
// con fallback a config('app.name')
siteName(): string
```

---

## Diseño visual

El sistema usa una paleta oscura configurable:

| Token | Valor | Uso |
|---|---|---|
| Fondo base | `#1c1c1c` | Body |
| Rojo acento | `#E52B2B` | CTAs, badges, activos |
| Verde acento | `#006847` | Botón menú, highlights |
| Naranja | `#f97316` | Acentos secundarios |
| Admin panel | Amber (Filament) | Panel de administración |

Tipografía: **Anton** (headings) + **Inter** (body).

---

## Notas técnicas

- Las clases dinámicas de Tailwind en Alpine `:class` no funcionan con JIT — usar `x-bind:style` con strings CSS completos
- Los bloques del Builder usan `->icon('heroicon-o-*')` — no emojis en labels
- `@tailwindcss/typography` no está instalado — usar clase custom `.privacy-content` para contenido de texto enriquecido
- `@@view-transition` (doble @) en Blade dentro de `<style>` para escapar correctamente
- El modelo `Review` fue eliminado — solo existe `ReviewSubmission`
- `ContactSubmission` usa el campo `is_attended` (no `is_read`)
- En `SiteInfoResource`, la navegación va directo a edición (sin tabla ni botón crear)

---

## Licencia

MIT
