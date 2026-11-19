# SIGI · Aplicación Laravel

Código fuente del backend y la web de SIGI.

- Guía general e instalación: [../README.md](../README.md)
- Arquitectura y buenas prácticas: [../docs/ARQUITECTURA.md](../docs/ARQUITECTURA.md)

```bash
composer install && cp .env.example .env && php artisan key:generate
php artisan migrate --seed && php artisan serve
php artisan test
```
