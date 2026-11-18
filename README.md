# SIGI - Sistema de Información y Gestión Integrado de Inventario y Ventas

Bienvenido al repositorio oficial del proyecto **SIGI** (*Sistema de Información y Gestión Integrado de Inventario y Ventas*).

Esta plataforma centraliza el control de existencias comerciales, auditoría mediante Kardex perpetuo de almacén, terminal de ventas POS y una capa de servicios API RESTful lista para integrarse con clientes móviles en **Flutter**.

---

## 📚 Documentación Técnica Completa

Para consultar la especificación detallada de modelos, controladores, base de datos relacional, endpoints API y el plan de desarrollo de la app móvil en Flutter, consulta:

👉 **[DOCUMENTACION_ARQUITECTURA_Y_PLAN_FLUTTER.md](DOCUMENTACION_ARQUITECTURA_Y_PLAN_FLUTTER.md)**

---

## 🚀 Tecnologías Principales

* **Backend Framework**: Laravel 11/12 (PHP 8.3+)
* **Autenticación API**: Laravel Sanctum v4 (Bearer Tokens para Apps Móviles y POS)
* **Base de Datos**: MySQL / MariaDB (Transaccional ACID con bloqueos pesimistas `lockForUpdate`)
* **Frontend Web**: Laravel Blade + Tailwind CSS + Vite
* **Documentación & Pruebas API**: Colección de Postman incluida en [`SIGI/postman_collection_SIGI.json`](SIGI/postman_collection_SIGI.json)
* **App Móvil (Objetivo)**: Flutter (Dart) con Clean Architecture

---

## 📌 Módulos del Sistema

1. **Autenticación y Seguridad**: Control de acceso por roles (Administrador `1` y Empleado `2`), gestión de tokens Sanctum y prevención de accesos concurrentes.
2. **Control de Inventario**: Catálogo con imágenes, códigos únicos para escaneo de código de barras, estados (`activo`, `inactivo`, `agotado`).
3. **Punto de Venta Transaccional (POS)**: Registro atómico de ventas con cálculo de cambio, descuento de stock y prevención de quiebres de inventario.
4. **Kardex Perpetuo**: Trazabilidad completa de movimientos de almacén (`ENTRADA`, `SALIDA`, `AJUSTE`).
5. **Alertas Preventivas**: Notificación en tiempo real de productos por debajo del stock mínimo o agotados.
6. **Módulo de Configuraciones**: Mantenimiento de métodos de pago y variables operativas.

---

## 📱 Conexión con Flutter

La API expone los endpoints bajo el prefijo `/api`:
* `POST /api/login` & `POST /api/register`
* `GET /api/dashboard` (Métricas en tiempo real)
* `GET /api/productos/barcode/{codigo}` (Búsqueda inmediata por escáner de cámara)
* `POST /api/ventas` (Terminal POS móvil)
* `GET /api/alertas` & `GET /api/movimientos`

Para más detalles sobre los modelos de datos y código Dart de integración, revisa la [Documentación Técnica](DOCUMENTACION_ARQUITECTURA_Y_PLAN_FLUTTER.md).

