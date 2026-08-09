# Gestión de Métodos de Pago y Configuraciones

La lógica de esta funcionalidad se maneja principalmente desde el controlador `ConfiguracionesController.php` y la vista `Admin/Configuraciones/ListasdeConfiguraciones.blade.php`.

## 1. Consulta de Métodos de Pago

Para acceder al panel de configuraciones del sistema, el usuario autenticado interactúa con la opción "Configuración" ubicada en el menú lateral izquierdo del panel de administración, dentro de la sección "Sistema". Este enlace está configurado para dirigir al controlador `ConfiguracionesController.php` a través de la ruta con nombre `configuraciones.index`, que corresponde a la URL `/configuraciones` mediante una petición de tipo `GET`. Esta ruta se encuentra resguardada por el middleware de autenticación `auth`.

Al recibir la petición `GET`, el controlador ejecuta el método `index` de la clase `ConfiguracionesController`. Este método realiza una consulta completa a la tabla `metodos_pago` mediante Eloquent utilizando la instrucción `MetodoPago::all()`. Los registros obtenidos se empaquetan con la función `compact('metodos')` y se transmiten a la vista `Admin/Configuraciones/ListasdeConfiguraciones.blade.php`.

En la vista, la interfaz organiza las configuraciones en pestañas interactivas, desplegando en la sección de métodos de pago la lista de modalidades activas e inactivas (como Efectivo, Tarjeta, Nequi, Transferencia), sus estados actuales y sus opciones de edición y eliminación.

---

## 2. Registrar Nuevo Método de Pago

Para agregar una nueva modalidad de cobro al sistema, el usuario abre el modal de registro en la vista de configuraciones y completa el nombre del nuevo método de pago. Al hacer clic en el botón de guardar, el formulario envía los datos mediante una petición de tipo `POST` a la ruta con nombre `configuraciones.store` (URL `/configuraciones/metodos`).

El método `store` del controlador realiza la validación del parámetro recibido mediante `$request->validate`:
- Verifica que el campo `nombre` no esté vacío, sea una cadena de texto, no supere los 50 caracteres y sea estrictamente único en la tabla `metodos_pago` sobre la columna `nombre`.

Una vez validada la información, el controlador invoca `MetodoPago::create()`, insertando el nuevo método de pago con el campo `activo` fijado automáticamente en `1` (Habilitado). Al finalizar, redirige a `configuraciones.index` adjuntando un mensaje de confirmación en la sesión.

---

## 3. Actualizar y Alternar Estado del Método de Pago

La actualización se gestiona a través del método `update` de la clase `ConfiguracionesController`, el cual atiende peticiones de tipo `PUT` enviadas a la ruta `configuraciones.update` (URL `/configuraciones/metodos/{id}`).

El método `update` evalúa dos casos de uso:
1. **Alternar Estado (Toggle Activo)**: Si la petición incluye el parámetro `toggle_activo`, el controlador invierte el valor entero de la columna `activo` (`$metodo->activo = $metodo->activo ? 0 : 1`) y guarda el cambio con `$metodo->save()`. Esto permite activar o desactivar métodos de pago al instante para que aparezcan o se oculten automáticamente en la consola del Punto de Venta POS.
2. **Edición del Nombre**: Si la petición envía un nuevo nombre, se ejecuta la validación de unicidad excluyendo el propio ID del registro (`"unique:metodos_pago,nombre,{$id},id_metodo"`), y se actualiza mediante `$metodo->update(['nombre' => $request->nombre])`.

---

## 4. Eliminar Método de Pago

Para eliminar un método de pago, el formulario envía una petición de tipo `DELETE` a la ruta `configuraciones.destroy` (URL `/configuraciones/metodos/{id}`).

El método `destroy` localiza el registro mediante `MetodoPago::findOrFail($id)` y ejecuta la instrucción `$metodo->delete()`, retirando el registro de la tabla `metodos_pago`. Finalmente, redirige a la pantalla de configuraciones notificando la eliminación exitosa.
