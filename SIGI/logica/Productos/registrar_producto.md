# Registrar Producto

La lógica de esta funcionalidad se maneja principalmente desde el controlador `ProductosController.php` y la vista `Admin/Productos/ListasProducto.blade.php`.

Para realizar esta acción, el administrador o usuario autenticado interactúa con la opción "Productos" que se encuentra ubicada en el menú lateral izquierdo del panel de administración, dentro de la sección "Gestión Comercial". En la vista principal del catálogo, el usuario hace clic en el botón "Nuevo Producto", el cual despliega un formulario emergente (modal `#create-modal`). El enlace del formulario está configurado para enviar la información al controlador `ProductosController.php` a través de la ruta con nombre `productos.store`, correspondiente a la URL `/productos` mediante una petición de tipo `POST` con el atributo `enctype="multipart/form-data"` para permitir la carga de imágenes. Esta ruta se encuentra protegida por el middleware `auth`.

Al recibir la petición `POST`, el controlador ejecuta el método `store` de la clase `ProductosController`. Lo primero que realiza este método es una validación rigurosa de los datos de entrada a través de `$request->validate`. El sistema comprueba que el código único (`codigo`) no esté vacío, no exceda los 50 caracteres y sea único en la tabla `productos` sobre la columna `codigoUnico`; que el `nombre` sea obligatorio y único; que el identificador de la categoría (`id_categoria`) exista en la tabla `categorias`; que el identificador del proveedor (`id_proveedor`) si se especifica exista en la tabla `proveedores`; que los precios de venta y costo sean numéricos no negativos; que el stock inicial y mínimo sean valores enteros mayores o iguales a cero; que la fecha de vencimiento sea una fecha válida; y que si se adjunta un archivo de imagen, cumpla con los formatos permitidos (`jpeg, png, jpg, gif, svg, webp`) y un tamaño máximo de 2048 KB (2 MB).

Si alguna de estas condiciones no se cumple, Laravel interrumpe el proceso y redirige automáticamente de vuelta con los mensajes de error en la sesión, los cuales son mostrados en la interfaz para permitir su corrección.

Una vez validados todos los campos, el controlador instancia un nuevo objeto del modelo `Producto` (`$producto = new Producto()`). A continuación, asigna campo por campo los valores recibidos:
- `$producto->codigoUnico = $validated['codigo']`
- `$producto->nombre = $validated['nombre']`
- `$producto->descripcion = $validated['descripcion'] ?? null`
- `$producto->id_categoria = $validated['id_categoria']`
- `$producto->id_proveedor = $validated['id_proveedor'] ?? null`
- `$producto->precio_venta = $validated['precio_venta']`
- `$producto->precio_costo = $validated['precio_costo'] ?? 0`
- `$producto->stock_minimo = $validated['alerta_minima'] ?? 0`
- `$producto->stock_actual = $validated['stock_inicial'] ?? 0`
- `$producto->fechaCreacion = now()`
- `$producto->fechaVencimiento = $validated['fechaVencimiento'] ?? null`
- `$producto->estado = $validated['estado'] ?? 'activo'`

Si el formulario incluye un archivo de imagen (`$request->hasFile('imagen')`), el controlador procesa el archivo y lo almacena en el disco de almacenamiento público de Laravel mediante `$request->file('imagen')->store('productos', 'public')`, guardando en el atributo `$producto->imagen` la ruta relativa del archivo guardado.

Finalmente, se ejecuta el método `$producto->save()`, construyendo e insertando el registro en la tabla `productos` de la base de datos. Al concluir exitosamente, el controlador redirige al usuario a la ruta `productos.index` mediante `redirect()->route('productos.index')` con un mensaje de éxito en la sesión (`'¡Producto registrado exitosamente!'`). La vista detecta esta variable de sesión y despliega una notificación de confirmación en la pantalla.
