# Actualizar y Eliminar Producto

La lógica de estas funcionalidades se maneja principalmente desde el controlador `ProductosController.php` y la vista `Admin/Productos/ListasProducto.blade.php`.

## 1. Actualización de Producto

Para editar la información de un producto o alternar su estado, el usuario autenticado interactúa con los botones de acción ubicados en las tarjetas o filas de la vista `productos.index`.

### a) Alternar Estado Rápido (Toggle Status)
Si el usuario hace clic en el botón para activar o desactivar un producto, el formulario envía una petición `PUT` a la ruta `productos.update` (URL `/productos/{id}`) enviando el parámetro `toggle_status`. El método `update` del controlador busca el registro mediante `Producto::findOrFail($id)` y evalúa la presencia del parámetro. Si `toggle_status` está presente, invierte el valor de la columna `estado` (si era `'activo'` pasa a `'inactivo'`, y viceversa), guarda los cambios con `$producto->save()` y redirige de vuelta a `productos.index` mostrando un mensaje informativo de sesión.

### b) Edición Completa de Formulario
Si el usuario hace clic en el botón de edición (ícono de lápiz ✏️), la función JavaScript `openEditModal()` carga los datos del producto seleccionado en los campos del modal de edición `#edit-modal`. Al guardar los cambios, el formulario envía los datos mediante la HTTP de tipo `PUT` a la URL `/productos/{id}`.

El método `update` del controlador ejecuta la validación con `$request->validate`. Para garantizar que el código y el nombre del producto puedan conservarse sin generar falsos positivos de duplicidad sobre el mismo registro, se aplican reglas de exclusión en la unicidad: `"unique:productos,codigoUnico,{$producto->id_producto},id_producto"`.

Una vez validados los datos, se actualizan los atributos del objeto `Producto`:
- Actualización de precios (`precio_venta`, `precio_costo`), stock (`stock_minimo`, `stock_actual`), estado, categoría, proveedor y fecha de vencimiento.
- **Gestión de Archivos de Imagen**: Si la petición incluye un nuevo archivo de imagen (`$request->hasFile('imagen')`), el controlador verifica si el producto ya contaba con una imagen anterior almacenada en el disco mediante `Storage::disk('public')->exists($producto->imagen)`. De existir, elimina la imagen antigua del servidor con `Storage::disk('public')->delete()` para evitar acumulación de archivos huérfanos, y luego guarda la nueva imagen.

Al llamar a `$producto->save()`, se actualiza el registro en la base de datos y se redirige a la lista con la notificación de éxito.

---

## 2. Eliminación de Producto

Para eliminar un producto del catálogo, el usuario presiona el botón con el ícono de papelera (🗑️), lo que ejecuta la función JavaScript `confirmDelete()`. Esta función despliega una alerta de confirmación con SweetAlert2 para evitar eliminaciones accidentales.

Al confirmar la acción, el formulario envía una petición de tipo `DELETE` a la ruta con nombre `productos.destroy` (URL `/productos/{id}`), la cual está protegida por la directiva `@method('DELETE')` y la protección CSRF.

La petición es procesada por el método `destroy` de la clase `ProductosController`:
1. El controlador localiza el producto con `Producto::findOrFail($id)`.
2. Revisa si el producto posee una imagen asociada guardada en el almacenamiento local. De encontrarse el archivo en el disco público, ejecuta `Storage::disk('public')->delete($producto->imagen)` para liberar espacio en el servidor.
3. Llama al método `$producto->delete()`, eliminando físicamente el registro de la tabla `productos` en la base de datos.
4. Redirige a la vista `productos.index` adjuntando un mensaje de éxito en la sesión (`'Producto eliminado exitosamente.'`).
