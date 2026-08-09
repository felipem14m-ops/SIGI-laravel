# Gestión de Proveedores - Documentación de Lógica y QA

## 1. Registrar Proveedor

La lógica de creación de proveedores se maneja principalmente desde el controlador `ProveedorController.php` y la vista `Admin/Proveedores/ListasdeProvedores.blade.php`.

Para realizar esta acción, el administrador interactúa con el botón "Nuevo Proveedor" que se encuentra en la parte superior de la vista de gestión de proveedores. Este botón activa un modal interactivo (`#create-modal`) mediante JavaScript. El formulario interno envía los datos mediante una petición `POST` hacia la ruta `proveedores.store` (URL `/proveedores`), protegida por el middleware `auth`.

Al enviar el formulario, el método `store` del controlador `ProveedorController` valida los datos recibidos mediante el método `validate`:
- `nombre`: Requerido, de tipo texto, máximo 255 caracteres.
- `email`: Requerido, formato válido de correo electrónico, máximo 255 caracteres y único en la tabla `proveedores` (`unique:proveedores,email`).
- `telefono`: Requerido, máximo 50 caracteres.
- `empresa`: Requerido, de tipo texto, máximo 255 caracteres.

Si la validación falla, Laravel redirige automáticamente mostrando los mensajes de error correspondientes. Si la validación es exitosa, se crea una nueva instancia `$proveedor = new Proveedor()`. Se le asignan las propiedades `nombre`, `email`, `telefono` y `empresa` leídas desde `$request->post()`, y el campo `activo` se establece automáticamente en `1` (activo por defecto).

Posteriormente, se invoca `$proveedor->save()`, ejecutando la instrucción `INSERT INTO proveedores` y rellenando los campos de auditoría `created_at` y `updated_at`. El controlador redirige a `proveedores.index` con el mensaje de éxito "¡Proveedor registrado exitosamente!", desplegando una alerta en la vista.

---

## 2. Listar Proveedores

El listado de proveedores se procesa en el método `index` de `ProveedorController.php`.

Al acceder a la ruta `proveedores.index` (`/proveedores` en petición `GET`), el controlador consulta los registros de la base de datos usando el modelo Eloquent `Proveedor::all()`. La colección resultante se envía a la vista `Admin.Proveedores.ListasdeProvedores` mediante la función `compact('proveedores')`.

La vista renderiza una tabla con la información detallada de cada proveedor: Nombre, Correo Electrónico, Teléfono, Empresa, Fecha de Registro/Creación, Badge estilizado de Estado (`ACTIVO` / `INACTIVO`) y la columna de acciones rápidas (Editar, Activar/Desactivar, Eliminar) alineadas con la línea gráfica del sistema.

---

## 3. Editar Proveedor

La modificación de datos de un proveedor existente la realiza el método `update` del controlador `ProveedorController.php`.

En la tabla de proveedores, cada fila cuenta con un botón "Editar" que ejecuta la función JavaScript `openEditModal(...)`. Esta función carga los datos actuales en el modal `#edit-modal` y configura el atributo `action` del formulario para enviar la petición a `/proveedores/{id}` mediante `POST` con la directiva `@method('PUT')`.

Al procesar la petición, el método `update` recupera el modelo con `Proveedor::findOrFail($id)` y ejecuta las validaciones requeridas. Para el correo electrónico, se utiliza la regla `unique:proveedores,email,' . $id . ',id_proveedor`, lo que permite mantener el mismo correo sin generar un error de duplicado con su propio registro. Además, se valida que el campo `activo` contenga un entero válido (`0` o `1`).

Una vez validados, se actualizan las propiedades del objeto y se ejecuta `$proveedor->save()`. El proceso concluye redirigiendo a la vista principal con el mensaje "¡Proveedor actualizado con éxito!".

---

## 4. Activar / Desactivar Proveedor

El cambio rápido de estado de un proveedor se gestiona dentro del método `update` de `ProveedorController.php`.

En la columna de acciones de la tabla, se incluye un botón de conmutación de estado que ejecuta la función JavaScript `confirmToggle(id, nombre, activo)`. Esta función muestra una alerta interactiva con `SweetAlert2`. Al confirmar la acción, se envía un formulario secundario con un campo oculto `<input type="hidden" name="toggle_status" value="1">` a través de una petición `PUT` hacia la ruta `proveedores.update`.

El controlador detecta la condición `$request->has('toggle_status')`, omitiendo las validaciones generales. Invierte el estado booleano de la propiedad `$proveedor->activo` (si es 1 pasa a 0, y si es 0 pasa a 1) y guarda el cambio mediante `$proveedor->save()`. Finalmente, redirige a la lista notificando que el proveedor fue "activado con éxito" o "desactivado con éxito".

---

## 5. Eliminar Proveedor

La eliminación de un proveedor la realiza el método `destroy` de `ProveedorController.php`.

Al pulsar el botón de eliminación en la tabla, la función JavaScript `confirmDelete(id, nombre)` despliega una confirmación mediante `SweetAlert2`. Al aceptar, se envía un formulario dinámico con la directiva `@method('DELETE')` hacia la ruta `proveedores.destroy` (`/proveedores/{id}`).

El método `destroy` del controlador localiza el registro con `Proveedor::findOrFail($id)` y llama al método `$proveedor->delete()`, ejecutando la instrucción SQL `DELETE FROM proveedores WHERE id_proveedor = ?`. Al completar la supresión, redirige de vuelta a `proveedores.index` con el mensaje "Proveedor eliminado exitosamente."
