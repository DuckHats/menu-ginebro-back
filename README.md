# 🍽️ Menu Ginebro - Backend

> **API REST Laravel para la gestión de menús escolares**

Una API robusta desarrollada con Laravel 10 que proporciona todos los endpoints necesarios para la gestión completa de menús escolares, usuarios, pedidos y administración. Incluye autenticación con Sanctum, sistema de roles, importación/exportación de datos y funcionalidades avanzadas de administración.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Prerrequisitos](#-prerrequisitos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Desarrollo](#-desarrollo)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Testing](#-testing)
- [Despliegue](#-despliegue)
- [Contribución](#-contribución)

## ✨ Características

### 🔐 Autenticación y Seguridad
- **Laravel Sanctum** para autenticación API
- **Tokens de acceso** con expiración configurable
- **Verificación de email** con códigos OTP
- **Recuperación de contraseña** por email
- **Rate limiting** para prevenir abuso
- **Logging** de accesos y errores

### 👥 Gestión de Usuarios
- **Sistema de roles**: Admin, Cocineros, Estudiantes
- **CRUD completo** de usuarios
- **Importación/Exportación** masiva (Excel)
- **Activación/Desactivación** de cuentas
- **Gestión de sesiones** múltiples
- **Políticas de autorización** granulares

### 🍽️ Gestión de Menús
- **CRUD completo** de menús y platos
- **Tipos de platos** categorizados
- **Importación/Exportación** de menús
- **Estados** de menús (activo/inactivo)

### 📊 Gestión de Pedidos
- **Sistema completo** de pedidos
- **Estados de pedidos** configurables
- **Tipos de pedidos** diferenciados
- **Historial** por usuario y fecha
- **Dashboard** con métricas

### 📧 Sistema de Emails
- **Mails transaccionales** automáticos
- **Plantillas** personalizables
- **Verificación** de cuentas
- **Notificaciones** de seguridad
- **Códigos** de recuperación

### 🔍 Monitoreo y Debugging
- **Laravel Telescope** para debugging
- **Logging** estructurado
- **Manejo de errores** centralizado
- **Pulse** para monitoreo

## 🛠️ Tecnologías

### Core Framework
- **Laravel 10** - Framework PHP principal
- **PHP 8.1+** - Lenguaje de programación
- **Composer** - Gestor de dependencias

### Base de Datos
- **MySQL/PostgreSQL** - Base de datos principal
- **Eloquent ORM** - Mapeo objeto-relacional
- **Migrations** - Control de versiones de BD
- **Seeders** - Datos de prueba

### Autenticación y Seguridad
- **Laravel Sanctum** - Autenticación API
- **Laravel Policies** - Autorización
- **Rate Limiting** - Control de velocidad
- **CORS** - Cross-Origin Resource Sharing

### Utilidades
- **Laravel Telescope** - Debugging y profiling
- **Maatwebsite Excel** - Importación/Exportación
- **Guzzle HTTP** - Cliente HTTP
- **Carbon** - Manejo de fechas

### Testing
- **PHPUnit** - Framework de testing
- **Laravel Testing** - Helpers de testing
- **Faker** - Generación de datos falsos

## 📋 Prerrequisitos

Antes de comenzar, asegúrate de tener instalado:

- **PHP 8.1** o superior
- **Composer** (versión 2 o superior)
- **MySQL 8.0** o **PostgreSQL 13** o superior
- **Node.js** (para assets frontend)
- **Git**

### Extensiones PHP Requeridas
```bash
# Verificar extensiones PHP
php -m | grep -E "(pdo|mbstring|openssl|tokenizer|xml|ctype|json|bcmath)"
```

## 🚀 Instalación

1. **Clona el repositorio**
```bash
git clone <repository-url>
cd menu-ginebro-back
```

2. **Instala las dependencias PHP**
```bash
composer install
```

3. **Instala las dependencias Node.js**
```bash
npm install
```

4. **Configura el entorno**
```bash
# Copia el archivo de configuración
cp .env.example .env

# Genera la clave de aplicación
php artisan key:generate
```

5. **Configura la base de datos**
```bash
# Edita el archivo .env con tus credenciales
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=menu_ginebro
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

6. **Ejecuta las migraciones**
```bash
php artisan migrate
```

7. **Pobla la base de datos**
```bash
php artisan db:seed
```

8. **Configura el almacenamiento**
```bash
php artisan storage:link
```

## ⚙️ Configuración

### Variables de Entorno Principales

#### `.env`
```env
# Aplicación
APP_NAME="Menu Ginebro"
APP_ENV=local
APP_KEY=base64:tu_clave_generada
APP_DEBUG=true
APP_URL=http://localhost:8001

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=menu_ginebro
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@menuginebro.com
MAIL_FROM_NAME="Menu Ginebro"

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:4200,127.0.0.1:4200
SESSION_DRIVER=database
SESSION_LIFETIME=120

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:4200,http://127.0.0.1:4200
```

### Configuración de Sanctum

#### `config/sanctum.php`
```php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
    'guard' => ['web'],
    'expiration' => env('SANCTUM_EXPIRATION', 60), // minutos
    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],
];
```

### Configuración de CORS

#### `config/cors.php`
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '')),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

## 🏃‍♂️ Desarrollo

### Servidor de Desarrollo

```bash
# Inicia el servidor de desarrollo
php artisan serve

# Con puerto específico
php artisan serve --port=8001

# Con host específico
php artisan serve --host=0.0.0.0 --port=8001
```

La API estará disponible en `http://localhost:8001`

### Comandos Útiles

```bash
# Generar un nuevo controlador
php artisan make:controller NombreController --resource

# Generar un nuevo modelo con migración
php artisan make:model NombreModel -m

# Generar un nuevo middleware
php artisan make:middleware NombreMiddleware

# Generar un nuevo job
php artisan make:job NombreJob

# Generar un nuevo mail
php artisan make:mail NombreMail

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Laravel Telescope (Debugging)

```bash
# Instalar Telescope
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Acceder a Telescope
# http://localhost:8001/telescope
```

## 📁 Estructura del Proyecto

```
app/
├── Console/                  # Comandos Artisan
├── Constants/                # Constantes de la aplicación
├── Contracts/                # Interfaces y contratos
├── Exceptions/                # Manejo de excepciones
├── Helpers/                   # Funciones auxiliares
├── Http/
│   ├── Controllers/           # Controladores de la API
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── MenuController.php
│   │   ├── DishController.php
│   │   ├── OrderController.php
│   │   └── ...
│   ├── Middleware/            # Middleware personalizado
│   │   ├── CorsMiddleware.php
│   │   ├── RateLimitMiddleware.php
│   │   └── ...
│   └── Resources/             # Resources para API
│       ├── UserResource.php
│       ├── MenuResource.php
│       └── ...
├── Jobs/                      # Jobs para colas
├── Mail/                      # Clases de email
├── Models/                    # Modelos Eloquent
│   ├── User.php
│   ├── Menu.php
│   ├── Dish.php
│   ├── Order.php
│   └── ...
├── Policies/                  # Políticas de autorización
├── Providers/                 # Service Providers
├── Repositories/              # Repositorios
├── Services/                  # Servicios de negocio
└── Traits/                    # Traits reutilizables

config/                        # Archivos de configuración
database/
├── factories/                 # Factories para testing
├── migrations/                # Migraciones de BD
└── seeders/                   # Seeders de datos

routes/
├── api.php                    # Rutas de la API
├── web.php                    # Rutas web
└── channels.php               # Rutas de broadcasting

tests/                         # Tests automatizados
├── Feature/                   # Tests de funcionalidad
└── Unit/                      # Tests unitarios
```

## 🧪 Testing

### Tests de Funcionalidad
```php
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => ['id', 'name', 'email'],
                    'token'
                ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                ->assertJson(['error' => 'Credenciales inválidas']);
    }
}
```

### Tests Unitarios
```php
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin()
    {
        $admin = User::factory()->create(['user_type_id' => User::ROLE_ADMIN]);
        
        $this->assertTrue($admin->isAdmin());
    }

    public function test_user_has_orders()
    {
        $user = User::factory()->create();
        Order::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->orders);
    }
}
```

### Ejecutar Tests
```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter AuthTest

# Con coverage
php artisan test --coverage
```

## 🚀 Despliegue

### Preparación para Producción

1. **Configurar variables de entorno**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.menuginebro.com

DB_CONNECTION=mysql
DB_HOST=tu_host_db
DB_DATABASE=menu_ginebro_prod
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña_segura

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

2. **Optimizar la aplicación**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

3. **Configurar permisos**
```bash
sudo chown -R www-data:www-data /var/www/menu-ginebro-back
sudo chmod -R 755 /var/www/menu-ginebro-back
sudo chmod -R 775 /var/www/menu-ginebro-back/storage
sudo chmod -R 775 /var/www/menu-ginebro-back/bootstrap/cache
```

### Docker

#### Dockerfile
```dockerfile
FROM php:8.1-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos
COPY . .

# Instalar dependencias
RUN composer install --optimize-autoloader --no-dev

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

#### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: menu-ginebro-back
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    networks:
      - menu-ginebro

  nginx:
    image: nginx:alpine
    container_name: menu-ginebro-nginx
    restart: unless-stopped
    ports:
      - "8001:80"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
      - menu-ginebro

  mysql:
    image: mysql:8.0
    container_name: menu-ginebro-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: menu_ginebro
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: laravel
      MYSQL_PASSWORD: laravel_password
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - menu-ginebro

  redis:
    image: redis:alpine
    container_name: menu-ginebro-redis
    restart: unless-stopped
    networks:
      - menu-ginebro

volumes:
  mysql_data:

networks:
  menu-ginebro:
    driver: bridge
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name api.menuginebro.com;
    root /var/www/menu-ginebro-back/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Supervisor para Colas
```ini
[program:menu-ginebro-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/menu-ginebro-back/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/menu-ginebro-back/storage/logs/worker.log
stopwaitsecs=3600
```

## 🤝 Contribución

### Flujo de Trabajo

1. **Fork** el repositorio
2. **Crea** una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. **Commit** tus cambios (`git commit -m 'feat: añadir nueva funcionalidad'`)
4. **Push** a la rama (`git push origin feature/nueva-funcionalidad`)
5. **Abre** un Pull Request

### Estándares de Código

- **PSR-12**: Estándar de codificación PHP
- **Laravel Pint**: Formateo automático
- **PHPStan**: Análisis estático
- **Conventional Commits**: Formato de commits

### Commits

Usar el formato Conventional Commits:
```
feat: añadir endpoint de exportación de usuarios
fix: corregir validación en creación de menús
docs: actualizar documentación de API
style: aplicar PSR-12 a controladores
refactor: refactorizar servicio de autenticación
test: añadir tests para modelo Order
```

## 📞 Soporte

Para soporte técnico o preguntas:

- **Email**: duck4hats@gmail.com
- **Issues**: [GitHub Issues](https://github.com/DuckHats/menu-ginebro-back/issues)

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE.md` para más detalles.

---

**Desarrollado con ❤️ por el equipo de Duckhats**
