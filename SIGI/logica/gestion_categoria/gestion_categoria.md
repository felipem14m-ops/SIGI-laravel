# Gestión de Categorías - Documentación de Lógica y QA

## 1. Registrar Categoría

La lógica de creación de categorías se maneja desde el controlador `CategoriasController.php` y la vista `Admin/Categorias/ListasdeCategorias.blade.php`.

El usuario administrador hace clic en el botón "Nueva Categoría", desplegando el modal `#create-modal` mediante JavaScript. Este modal incluye campos para el nombre, la descripción y un área especial de carga de imágenes con previsualización en tiempo real gestionada por la función JavaScript `mostrarImagen(event)`. El formulario se envía mediante petición `POST` a la ruta `categorias.store` (URL `/categorias`) utilizando el atributo `enctype="multipart/form-data"`.

El método `store` del controlador recibe la petición y ejecuta la validación sobre los datos:
- `nombre`: Requerido, texto de máximo 80 caracteres, único en la tabla `categorias` (`unique:categorias,nombre`).
- `descripcion`: Opcional, de tipo texto.
- `imagen`: Opcional, de tipo archivo de imagen (jpeg, png, jpg, gif, svg, webp) con un peso máximo de 2MB (`max:2048`).

Una vez superada la validación, el controlador instancia una nueva clase `new Categoria()`. Se asignan el nombre y la descripción. El campo `activa` se establece automáticamente en `1` (activo por defecto). Si la petición incluye un archivo de imagen en `$request->hasFile('imagen')`, este se procesa mediante `$request->file('imagen')->store('categorias', 'public')`. Esta instrucción almacena la imagen en el directorio `storage/app/public/categorias` y guarda la ruta relativa devuelta en la propiedad `$categoria->imagen`.

Finalmente, se llama a `$categoria->save()` para persistir el nuevo registro en la base de datos y se redirige a `categorias.index` enviando el mensaje de sesión "¡Categoría registrada exitosamente!".

---

## 2. Listar y Alternar Vistas (Tarjetas / Tabla)

La visualización de categorías se procesa en el método `index` de `CategoriasController.php` y la vista `ListasdeCategorias.blade.php`.

Al acceder a la ruta `categorias.index` (URL `/categorias` mediante `GET`), el método `index` del controlador realiza una consulta mediante el modelo Eloquent `Categoria::all()` para obtener la colección completa de categorías y enviarla a la vista.

La vista `ListasdeCategorias.blade.php` implementa dos modos de visualización sin recarga de página:
1. **Vista Tarjetas (`#vista-cards`)**: Muestra cada categoría como una tarjeta minimalista que incluye su imagen (o inicial en degradado si no posee imagen), badges de estado, contador de productos asociados y opciones de edición.
2. **Vista Tabla (`#vista-tabla`)**: Muestra los datos en una tabla estructurada que incluye la imagen de portada/avatar circular, nombre, descripción recortada, badge de estado (`ACTIVO` / `INACTIVO`), fecha de registro, acciones rápidas e interfaz de paginación inferior, alineada con el diseño global del sistema.

La conmutación entre ambas vistas se realiza en tiempo real mediante las funciones JavaScript `mostrarVistaCards()` y `mostrarVistaTabla()`, las cuales modifican la propiedad `display` de los contenedores y actualizan el estado visual de los botones superiores (`#btn-vista-cards` y `#btn-vista-tabla`). Además, el estado de la vista activa se guarda en `LocalStorage` bajo la clave `sgc_categorias_vista`, permitiendo que al refrescar la página o volver a ella se mantenga la última vista elegida por el usuario.

---

## 3. Editar Categoría

La modificación de una categoría existente la procesa el método `update` de `CategoriasController.php`.

En ambas vistas (Tarjetas y Tabla), cada registro incluye un botón "Editar" que invoca la función JavaScript `openEditModal(...)`. Esta función puebla los datos en el modal `#edit-modal` y configura el atributo `action` del formulario hacia `/categorias/{id}` enviando los datos mediante `POST` con la directiva `@method('PUT')`.

El método `update` recupera la instancia con `Categoria::findOrFail($id)` y ejecuta las validaciones correspondientes. Para el nombre de la categoría, se asegura de que siga siendo único excluyendo al registro actual mediante la regla `unique:categorias,nombre,{$id},id_categoria`.

Si el usuario selecciona una imagen nueva en `$request->hasFile('imagen')`, el controlador hace uso de la facade `Storage::disk('public')->delete($categoria->imagen)` para eliminar físicamente del disco la imagen anterior (evitando acumulación de archivos innecesarios), y almacena la nueva imagen en la carpeta public de categorías. Posterior a la asignación de valores, se ejecuta `$categoria->save()` y se redirige con el mensaje "¡Categoría actualizada con éxito!".

---

## 4. Activar / Desactivar Categoría

El cambio de estado se realiza desde el método `update` de `CategoriasController.php`.

El botón de conmutación de estado en las tarjetas o tabla dispara la función JavaScript `confirmToggle(id, nombre, activa)`, la cual solicita confirmación con `SweetAlert2`. Al aceptar, se envía un formulario secundario vía `PUT` con el campo oculto `<input type="hidden" name="toggle_status" value="1">`.

El controlador evalúa la condición `$request->has('toggle_status')`. De cumplirse, invierte el valor booleano del campo `$categoria->activa` (`1` a `0` o `0` a `1`), almacena la modificación con `$categoria->save()` y redirige de vuelta informando si la categoría fue activada o desactivada exitosamente.

---

## 5. Eliminar Categoría

La eliminación de una categoría la ejecuta el método `destroy` de `CategoriasController.php`.

Al presionar el botón de eliminación en la tabla o tarjeta, la función JavaScript `confirmDelete(id, nombre)` solicita confirmación mediante `SweetAlert2`. Al confirmar, se envía un formulario secundario con la directiva `@method('DELETE')` hacia la ruta `categorias.destroy` (`/categorias/{id}`).

El método `destroy` busca la categoría con `Categoria::findOrFail($id)`. Si la categoría tiene una imagen asociada en el servidor, ejecuta `Storage::disk('public')->delete($categoria->imagen)` para remover el archivo de imagen del disco de almacenamiento. Luego llama a `$categoria->delete()` para eliminar el registro de la base de datos y redirige a `categorias.index` enviando la notificación "Categoría eliminada exitosamente."
