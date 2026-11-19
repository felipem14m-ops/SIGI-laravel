# SIGI: Sistema de Información y Gestión Integrado de Inventario y Ventas

SIGI centraliza el control de existencias, el Kardex perpetuo de almacén, la terminal de ventas POS y una API REST para la app móvil en **Flutter**.

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Sanctum](https://img.shields.io/badge/Auth-Sanctum-38BDF8)
![Tests](https://img.shields.io/badge/tests-PHPUnit-success)

---

## 📚 Documentación

| Documento | Contenido |
|---|---|
| [docs/ARQUITECTURA.md](docs/ARQUITECTURA.md) | Capas, estructura de carpetas, principios POO/SOLID, seguridad, Kardex y receta para nuevos módulos |
| [SIGI/logica/](SIGI/logica) | Lógica funcional por módulo (casos de uso) |
| [SIGI/postman_collection_SIGI.json](SIGI/postman_collection_SIGI.json) | Colección de Postman de la API |

---

## 🚀 Tecnologías

* **Backend:** Laravel 13 (PHP 8.3+), arquitectura en capas (Controladores → Servicios → Dominio)
* **Autenticación API:** Laravel Sanctum (Bearer Tokens para Flutter y POS)
* **Base de datos:** MySQL / MariaDB, transacciones ACID con bloqueo pesimista (`lockForUpdate`)
* **Frontend web:** Blade, Tailwind CSS y Vite
* **Calidad:** PHPUnit (pruebas de feature y unitarias) y Laravel Pint (estilo PSR-12)

---

## 🏗️ Arquitectura en una mirada

```
Rutas + Middleware (auth · activo · role)
   → Form Requests (validación → DTO)
      → Controladores delgados (Admin / Empleado / Ventas / Api)
         → Servicios (VentaService, InventarioService, ProductoService…)
            → Modelos con comportamiento + Enums + Excepciones de dominio
```

* La lógica de **registrar venta** existe en un solo lugar (`VentaService`) y la usan el POS web y la API.
* Toda variación de stock pasa por `InventarioService` y queda registrada en el **Kardex**.
* Hay **control de acceso por rol** (`role:admin`, `role:empleado`) y la política `VentaPolicy`.
* Las **excepciones de negocio** (`StockInsuficienteException`, etc.) se convierten solas en JSON 422 o en un mensaje flash.

Consulta el detalle en [docs/ARQUITECTURA.md](docs/ARQUITECTURA.md).

---

## 📌 Módulos

1. **Autenticación y seguridad:** roles Administrador (`1`) y Empleado (`2`), tokens Sanctum y bloqueo de usuarios inactivos.
2. **Inventario:** catálogo con imágenes, código único para escáner y estados (`activo`, `inactivo`, `agotado`) sincronizados con el stock.
3. **POS transaccional:** venta atómica con cálculo de cambio, descuento de stock y validación de monto recibido.
4. **Kardex perpetuo:** trazabilidad de `ENTRADA`, `SALIDA` y `AJUSTE` con stock anterior y resultante.
5. **Alertas:** productos agotados o por debajo del stock mínimo.
6. **Configuraciones:** métodos de pago.

---

## ⚙️ Instalación

```bash
cd SIGI
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
# Configura DB_* en .env (MySQL) o deja SQLite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

El seeder crea los roles, los tipos de movimiento, los métodos de pago y estos usuarios de prueba (contraseña `password`; **cámbiala en producción**):

| Rol | Email |
|---|---|
| Administrador | `felipem14m@gmail.com` |
| Empleado | `empleado@sigi.test` |

### Pruebas y estilo

```bash
php artisan test        # suite completa
vendor/bin/pint         # formatea el código (PSR-12 / Laravel)
```

---

## 📱 API REST (prefijo `/api`)

| Método | Endpoint | Rol |
|---|---|---|
| POST | `/login`, `/register` | Público (el registro siempre crea un Empleado) |
| GET | `/me`, `/dashboard` | Autenticado |
| GET | `/productos`, `/productos/{id}`, `/productos/barcode/{codigo}` | Autenticado |
| GET | `/categorias`, `/categorias/{id}`, `/metodos-pago`, `/alertas` | Autenticado |
| GET / POST | `/ventas` | Autenticado (el empleado solo ve sus ventas) |
| POST / PUT / DELETE | `/productos/{id}`, `/categorias/{id}` | Administrador |
| GET / POST | `/movimientos`, GET `/proveedores` | Administrador |

Todas las respuestas siguen el formato `{ "success": bool, "message"?: string, "data"?: … }`.
