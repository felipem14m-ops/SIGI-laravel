# Gestión de Usuarios - Documentación de Lógica y QA

## 1. Registrar Usuario

La lógica de esta funcionalidad se maneja principalmente desde el controlador `UserController.php` y la vista `Admin/Usuarios/ListasdeUsuarios.blade.php`.

Para realizar esta acción, el administrador autenticado interactúa con el botón "Nuevo Usuario" que se encuentra en la parte superior derecha de la vista dentro del panel de administración, en la sección "Gestión de Usuarios". Este botón está configurado para activar un modal interactivo (`#create-modal`) mediante JavaScript. El formulario dentro de este modal envía los datos a la ruta con nombre `usuarios.store`, que corresponde a la URL `/usuarios` mediante una petición `POST`. Esta ruta está protegida por el middleware `auth`, lo que garantiza que únicamente los usuarios con sesión activa puedan acceder a ella.

Al momento de recibir la petición `GET` en el módulo de usuarios, el controlador ejecuta el método `index` de la clase `UserController`, que consulta los roles existentes en la tabla `roles` a través del modelo `Role` y los envía empaquetados en la variable `$roles` hacia la vista `ListasdeUsuarios.blade.php`. Esto permite que el select de roles en el modal se construya dinámicamente con las opciones reales de la base de datos, de manera que si en el futuro se agregan nuevos roles a la base de datos, el formulario los mostrará automáticamente sin necesidad de modificar el código de la vista.

Cuando el administrador completa los campos del formulario modal (Nombre, Correo Electrónico, Identificación, Rol y Contraseña) y hace clic en el botón "Guardar Usuario", la petición `POST` es recibida por el método `store` del controlador `UserController`, que es el encargado de procesar, validar e insertar el nuevo usuario en la base de datos.

Lo primero que ejecuta el método `store` es una validación de los datos recibidos mediante el método `validate` del objeto `$request`. El sistema verifica que el nombre no esté vacío y no supere 255 caracteres, que el correo sea válido, no supere 255 caracteres y no exista previamente en la tabla `users` (`unique:users,email`), que la identificación sea única en la tabla (`unique:users,numeroIdentificacion`), que el rol corresponda a un entero existente en la tabla `roles` (`exists:roles,id_rol`) y que la contraseña posea al menos 8 caracteres. Si alguna de estas condiciones no se cumple, Laravel interrumpe inmediatamente la ejecución del método y redirige al administrador de vuelta al formulario, mostrando los mensajes de error correspondientes en la alerta superior de la vista. El error más frecuente que puede ocurrir en este punto es el de correo electrónico o número de identificación duplicado, que impide que el proceso llegue a la base de datos y genera un mensaje amigable en la vista.

Una vez que todos los datos superan la validación, el controlador instancia un nuevo objeto vacío del modelo `User` usando la expresión `new User()`. En este momento el registro todavía no existe en la base de datos, únicamente se crea una representación en memoria del futuro usuario. A continuación, el controlador asigna campo por campo los datos recibidos del formulario al objeto usando el método `post` del `$request` para leer cada valor. La contraseña no se almacena tal como fue escrita por el administrador sino que pasa por el método `make` de la clase `Hash`, que aplica el algoritmo de cifrado Bcrypt y genera un hash seguro antes de asignarla al objeto. El campo `activo` se fija automáticamente con el valor `1` (activo por defecto) sin requerir intervención del administrador.

Con todos los campos asignados, el controlador llama al método `save` del objeto usuario. En este momento Eloquent construye y ejecuta internamente la sentencia `INSERT INTO users` con todos los campos asignados, incluyendo los campos `created_at` y `updated_at` que son gestionados automáticamente con la fecha y hora actual del servidor. El modelo `User`, definido en `app/Models/User.php`, declara los campos permitidos para recibir datos mediante el atributo `$fillable` e indica que la contraseña debe ser tratada adecuadamente en sus casts, añadiendo una capa adicional de protección sobre los datos almacenados.

Al finalizar el proceso con éxito, el controlador redirige automáticamente al administrador de vuelta a la ruta `usuarios.index`, y envía un mensaje de sesión con el texto "¡Registro exitoso!". La vista detecta este mensaje usando la directiva `@if(session('success'))` y muestra una alerta verde de confirmación en la parte superior, confirmando visualmente que el nuevo usuario fue creado correctamente en la base de datos. Si el proceso falla por cualquier razón de validación, el administrador verá los errores indicados directamente en la alerta para que pueda corregirlos y volver a intentarlo.

---

## 2. Listar y Filtrar Usuarios

La lógica de visualización y búsqueda de usuarios se gestiona desde el método `index` del controlador `UserController.php` y la vista `Admin/Usuarios/ListasdeUsuarios.blade.php`.

El administrador accede mediante la ruta `usuarios.index` (URL `/usuarios` en petición `GET`). El método `index` recibe los parámetros opcionales `search` y `role_id` a través del objeto `$request`. El controlador inicia construyendo una consulta sobre la relación Eloquent `User::with('role')` para precargar la información del rol asociado a cada usuario y evitar problemas de rendimiento por consultas repetitivas (N+1).

Si el parámetro `search` contiene texto, se aplica un grupo de condiciones `where` con operador `LIKE %search%` sobre el nombre, el correo electrónico o el número de identificación del usuario. Si se selecciona un filtro por `role_id`, se añade una condición de igualdad estricta sobre la columna `id_rol`. La colección resultante se obtiene mediante `$query->get()` y se pasa a la vista junto con los valores de búsqueda persistidos.

La vista renderiza una tabla con la lista de usuarios. Para cada fila, se extrae la inicial del nombre mediante `mb_substr` y `mb_strtoupper` (garantizando compatibilidad multibyte para nombres con tildes o caracteres especiales), el correo electrónico, la identificación, un badge que identifica el rol (Administrador o Empleado), un badge de estado activo/inactivo y la columna de acciones rápidas.

---

## 3. Editar Usuario

La actualización de la información del usuario se maneja desde el método `update` de `UserController.php`.

En la tabla de usuarios, cada fila incluye un botón "Editar" que invoca la función JavaScript `openEditModal(...)`. Esta función transfiere los datos del usuario seleccionado a los campos del modal `#edit-modal` y modifica la propiedad `action` del formulario dinámicamente hacia la URL `/usuarios/{id}`. Al enviar el formulario, la petición viaja mediante `POST` simulando `PUT` mediante la directiva `@method('PUT')`.

El método `update` del controlador recibe la petición y obtiene el registro correspondiente con `User::findOrFail($id)`. Valida los datos recibidos asegurando que el correo electrónico y el número de identificación sigan siendo únicos en la tabla `users`, excluyendo al usuario actual del chequeo mediante la regla `unique:users,email,' . $id . ',id_usuario`. El campo contraseña es opcional; solo si el administrador ingresa un valor nuevo, este se cifra con `Hash::make()`. De lo contrario, se conserva la contraseña existente. Tras asignar los valores modificados, se invoca `$usuario->save()` y se redirige a la lista con el mensaje "¡Usuario actualizado con éxito!".

---

## 4. Activar / Desactivar Usuario

El cambio de estado de un usuario se realiza dentro del mismo método `update` de `UserController.php`.

En la tabla de usuarios, la columna de acciones incluye un botón que ejecuta la función JavaScript `confirmToggle(id, nombre, activo)`. Esta función despliega una ventana modal interactiva mediante la librería `SweetAlert2` solicitando confirmación. Al presionar el botón de confirmación, se envía un formulario secundario de manera automática con un campo oculto `<input type="hidden" name="toggle_status" value="1">` a la ruta `usuarios.update` vía `PUT`.

El controlador evalúa la presencia de dicho campo mediante `$request->has('toggle_status')`. Cuando este parámetro está presente, omite la validación completa del formulario de edición e invierte inmediatamente el valor entero del campo `$usuario->activo` (si es 1 cambia a 0, y viceversa). Se guarda el registro con `$usuario->save()` y se redirige con el mensaje informando que el usuario fue "activado con éxito" o "desactivado con éxito".

---

## 5. Eliminar Usuario

La eliminación de un registro se ejecuta desde el método `destroy` de `UserController.php`.

Cada fila de la tabla contiene un botón de eliminación que dispara la función JavaScript `confirmDelete(id, nombre)`. Al confirmar la acción en la alerta `SweetAlert2`, se envía un formulario secundario con la directiva `@method('DELETE')` hacia la ruta `usuarios.destroy` (`/usuarios/{id}`).

El método `destroy` localiza el registro con `User::findOrFail($id)` y ejecuta `$usuario->delete()`, lo cual genera la sentencia SQL `DELETE FROM users WHERE id_usuario = ?`. Al finalizar la supresión del registro en la base de datos, el controlador ejecuta `return back()` adjuntando un mensaje de sesión que confirma la eliminación del usuario.
