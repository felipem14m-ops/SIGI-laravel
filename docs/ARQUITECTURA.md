# Arquitectura de SIGI

Este documento describe cómo está organizado el código del backend Laravel de SIGI. También explica qué principios de Programación Orientada a Objetos aplica y dónde agregar funcionalidad nueva.

---

## 1. Vista general por capas

```
 Petición HTTP (Blade / fetch / Flutter)
        │
        ▼
 ┌──────────────────────┐   routes/web.php · routes/api.php
 │  Rutas + Middleware  │   auth · activo · role:admin · role:empleado · throttle
 └──────────┬───────────┘
            ▼
 ┌──────────────────────┐   app/Http/Requests/*
 │    Form Requests     │   Validación, normalización de campos y conversión a DTO
 └──────────┬───────────┘
            ▼
 ┌──────────────────────┐   app/Http/Controllers/{Admin,Empleado,Ventas,Api}
 │   Controladores      │   Delgados: reciben, delegan y responden (vista, redirect o JSON)
 └──────────┬───────────┘
            ▼
 ┌──────────────────────┐   app/Services/*
 │     Servicios        │   Casos de uso y reglas de negocio, transacciones ACID
 └──────────┬───────────┘
            ▼
 ┌──────────────────────┐   app/Models/* · app/Enums/* · app/DTOs/*
 │  Dominio / Modelos   │   Entidades con comportamiento, scopes y relaciones
 └──────────┬───────────┘
            ▼
        Base de datos (MySQL / SQLite)
```

**Regla de oro:** los controladores no contienen lógica de negocio y las vistas no hacen consultas. Toda regla del negocio vive en un servicio o en un modelo.

---

## 2. Estructura de carpetas

```
app/
├── Contracts/                 Interfaces (abstracciones)
│   └── AlmacenImagenes.php
├── DTOs/                      Objetos de transferencia inmutables (readonly)
│   ├── ItemVentaData.php
│   ├── RegistrarVentaData.php
│   └── RegistrarMovimientoData.php
├── Enums/                     Valores de dominio tipados
│   ├── Rol.php                    1 = Administrador, 2 = Empleado
│   ├── EstadoProducto.php         activo · inactivo · agotado
│   ├── EstadoVenta.php            completada · anulada
│   └── TipoMovimientoCodigo.php   ENTRADA · SALIDA · AJUSTE (+ cálculo del stock)
├── Exceptions/                Excepciones de reglas de negocio (se renderizan solas)
│   ├── ReglaNegocioException.php      (abstracta, base)
│   ├── StockInsuficienteException.php
│   ├── MontoInsuficienteException.php
│   ├── ProductoNoDisponibleException.php
│   ├── EliminacionNoPermitidaException.php
│   └── OperacionNoPermitidaException.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/             Módulos de gestión (solo rol Administrador)
│   │   ├── Empleado/          POS, consultas y "mis ventas" del empleado
│   │   ├── Ventas/            Acciones compartidas: registrar venta, detalle, factura
│   │   ├── Api/               API REST para Flutter (un controlador por recurso)
│   │   ├── Auth/              Laravel Breeze (login, registro, contraseñas)
│   │   ├── Concerns/          Traits reutilizables (RespondeJson)
│   │   └── DashboardController.php
│   ├── Middleware/
│   │   ├── AsegurarRol.php            alias `role`
│   │   └── AsegurarUsuarioActivo.php  alias `activo`
│   ├── Requests/              Un Form Request por caso de uso
│   └── Resources/             Transformadores JSON de la API
├── Models/
│   ├── Concerns/TieneImagen.php   Trait: atributo `imagen_url`
│   └── *.php                      Entidades Eloquent
├── Policies/
│   └── VentaPolicy.php        El empleado solo ve sus propias ventas
├── Providers/AppServiceProvider.php   Enlace de interfaces → implementaciones
└── Services/
    ├── Almacenamiento/AlmacenImagenesLocal.php
    ├── CategoriaService.php
    ├── DashboardService.php
    ├── InventarioService.php  Única puerta para modificar stock (Kardex)
    ├── ProductoService.php
    └── VentaService.php       Caso de uso "Registrar venta"
```

---

## 3. Principios POO aplicados

| Principio | Dónde se ve |
|---|---|
| **Responsabilidad Única (S)** | El `ApiController` de 800 líneas se dividió en 8 controladores por recurso. La validación pasó a Form Requests, la lógica a Servicios y el formato JSON a Resources. |
| **Abierto/Cerrado (O)** | Agregar un tipo de movimiento solo exige un nuevo `case` en `TipoMovimientoCodigo` con su regla en `aplicar()`; no hay que tocar `if/elseif` repartidos en controladores. |
| **Sustitución de Liskov (L)** | Todas las excepciones de negocio extienden `ReglaNegocioException` y el framework las trata de forma uniforme (JSON 422 o redirect con mensaje). |
| **Segregación de Interfaces (I)** | `AlmacenImagenes` expone solo tres operaciones (`guardar`, `eliminar`, `reemplazar`). |
| **Inversión de Dependencias (D)** | `ProductoService` y `CategoriaService` dependen de la interfaz `AlmacenImagenes`, no de `Storage::disk('public')`. Cambiar a S3 es modificar una línea en `AppServiceProvider`. |
| **Encapsulamiento** | El stock solo cambia mediante `Producto::establecerStock()`, que además sincroniza el estado `agotado`/`activo`. Ningún controlador asigna `stock_actual` directamente. |
| **Polimorfismo** | Los enums encapsulan comportamiento: `TipoMovimientoCodigo::SALIDA->aplicar(10, 3)` devuelve `7`, `AJUSTE->aplicar(10, 3)` devuelve `3`. |
| **Herencia** | `ActualizarProductoRequest extends GuardarProductoRequest`. Reutiliza las reglas y solo cambia a validación parcial (`sometimes`). |
| **Composición** | `VentaService` *usa* `InventarioService`, inyectado por constructor, en vez de duplicar la lógica del Kardex. |
| **DRY** | La lógica de registrar una venta existía 3 veces (Admin, Empleado y API). Ahora existe una sola vez en `VentaService::registrar()`. |

---

## 4. Flujo de un caso de uso: Registrar venta

```
POST /ventas  (POS web)        POST /api/ventas  (Flutter)
        │                               │
        ▼                               ▼
 RegistrarVentaRequest  ── valida items, método de pago activo y monto
        │  toData()
        ▼
 RegistrarVentaData (DTO inmutable)
        │
        ▼
 VentaService::registrar()          ── DB::transaction
   ├─ bloquea productos (lockForUpdate, orden por id → sin deadlocks)
   ├─ valida producto activo         → ProductoNoDisponibleException
   ├─ crea Venta + DetalleVenta con el precio vigente
   ├─ InventarioService::aplicar(SALIDA)
   │     ├─ valida stock             → StockInsuficienteException
   │     ├─ Producto::establecerStock() (marca "agotado" si llega a 0)
   │     └─ crea Movimiento en el Kardex (origen = automatico)
   └─ calcula total y cambio         → MontoInsuficienteException
        │
        ▼
 JSON { success, venta_id, cambio, message }
```

Si cualquier paso lanza una excepción, la transacción se revierte completa y el usuario recibe un mensaje claro (HTTP 422).

---

## 5. Seguridad y autorización

| Capa | Qué protege |
|---|---|
| `auth` / `auth:sanctum` | Sesión web o token Bearer. |
| `activo` | Un usuario desactivado pierde el acceso de inmediato, aunque tenga sesión abierta. |
| `role:admin` / `role:empleado` | Acceso por módulo. Antes **no existía** y un empleado podía abrir `/usuarios` escribiendo la URL. |
| `VentaPolicy` | Un empleado solo ve el detalle o la factura de **sus** ventas. |
| `RegistroRequest` (API) | El auto-registro siempre crea un Empleado. Antes se podía enviar `id_rol: 1` y registrarse como Administrador. |
| `LoginRequest` (web) | Solo inician sesión usuarios con `activo = 1`. |
| `throttle:10,1` | Limita los intentos de login y registro en la API. |

### Matriz de permisos

| Módulo | Administrador | Empleado |
|---|:---:|:---:|
| Dashboard | ✔ (global) | ✔ (propio) |
| Usuarios, Proveedores, Categorías, Productos | ✔ | ✘ |
| POS (registrar venta) | ✔ | ✔ |
| Historial de ventas | ✔ (todas) | Solo las suyas |
| Kardex / Movimientos | ✔ | ✘ |
| Alertas, Reportes, Configuraciones | ✔ | ✘ |
| API: leer productos, categorías y alertas | ✔ | ✔ |
| API: crear o editar catálogo y movimientos | ✔ | ✘ |

---

## 6. Integridad del inventario (Kardex)

Toda variación de stock genera un registro en `movimiento_inventario` con `stock_anterior` y `stock_resultante`:

| Acción | Tipo de movimiento | Origen |
|---|---|---|
| Venta en el POS | SALIDA | automatico |
| Registro manual en el Kardex | ENTRADA / SALIDA / AJUSTE | manual |
| Producto creado con stock inicial | ENTRADA | manual |
| Edición del stock desde el formulario de producto | AJUSTE | manual |

Los productos, categorías, proveedores, métodos de pago y usuarios **con historial** no se borran físicamente. Se desactivan para no romper la trazabilidad contable.

---

## 7. Cómo agregar un módulo nuevo (receta)

Ejemplo: **Compras a proveedores**.

1. **Migración y modelo:** `php artisan make:model Compra -mf`. En el modelo agrega relaciones, scopes y métodos de dominio.
2. **Enum** si hay estados: `app/Enums/EstadoCompra.php`.
3. **DTO:** `app/DTOs/RegistrarCompraData.php` (clase `final readonly`).
4. **Servicio:** `app/Services/CompraService.php`. Usa `InventarioService::aplicar(ENTRADA, …)` para sumar el stock y nunca modifiques `stock_actual` a mano.
5. **Form Request:** `app/Http/Requests/Compra/RegistrarCompraRequest.php` con un método `toData()`.
6. **Controlador delgado:** `app/Http/Controllers/Admin/CompraController.php` (y `Api/` si aplica).
7. **Resource** para la API: `app/Http/Resources/CompraResource.php`.
8. **Rutas** dentro del grupo `role:admin`.
9. **Pruebas** en `tests/Feature/Compras/`.

---

## 8. Pruebas automatizadas

```bash
php artisan test
```

| Suite | Cubre |
|---|---|
| `Feature/Ventas/RegistrarVentaTest` | Descuento de stock, Kardex, rollback por stock insuficiente, monto insuficiente, producto inactivo, estado agotado |
| `Feature/Inventario/InventarioServiceTest` | ENTRADA, SALIDA y AJUSTE; reactivación de agotados; stock inicial en el Kardex |
| `Feature/Seguridad/AutorizacionPorRolTest` | Matriz de permisos por rol, política de ventas, usuario inactivo |
| `Feature/Api/AuthApiTest` | Registro seguro, login con `password`/`contrasena`, permisos de la API, 404 uniforme |
| `Feature/SmokeVistasTest` | Todas las pantallas renderizan con datos reales y las acciones CRUD principales funcionan |
| `Unit/TipoMovimientoCodigoTest` | Reglas de cálculo del enum |
