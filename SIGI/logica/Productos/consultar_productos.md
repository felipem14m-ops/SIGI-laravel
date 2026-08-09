# Consultar Catálogo de Productos

La lógica de esta funcionalidad se maneja principalmente desde el controlador `ProductosController.php` y la vista `Admin/Productos/ListasProducto.blade.php`.

Para realizar esta acción, el usuario autenticado navega a la opción "Productos" en el menú lateral del panel de administración. Este enlace redirige al controlador `ProductosController.php` mediante la ruta con nombre `productos.index`, correspondiente a la URL `/productos` a través de una petición de tipo `GET`. Esta ruta se encuentra resguardada por el middleware de autenticación `auth`.

Al recibir la petición `GET`, el controlador ejecuta el método `index` de la clase `ProductosController`. Este método realiza consultas eficientes a la base de datos utilizando el ORM Eloquent:
1. **Consulta de Productos**: Recupera todos los productos registrados cargando de forma impaciente sus relaciones con categoría y proveedor mediante `Producto::with(['categoria', 'proveedor'])->get()`.
2. **Consulta de Categorías**: Obtiene las categorías que se encuentran activas en el sistema ordenadas alfabéticamente mediante `Categoria::where('activa', true)->orderBy('nombre')->get()`.
3. **Consulta de Proveedores**: Obtiene la lista de proveedores activos ordenados por nombre mediante `Proveedor::where('activo', true)->orderBy('nombre')->get()`.

El controlador empaqueta estas tres variables (`productos`, `categorias`, `proveedores`) utilizando la función `compact` y las envía a la vista `Admin/Productos/ListasProducto.blade.php`.

En la vista `ListasProducto.blade.php`, la información se presenta ofreciendo dos modalidades de visualización alternables dinámicamente mediante JavaScript:
- **Vista de Tarjetas / Grid (`#vista-grid`)**: Muestra cada producto como una tarjeta visual con su imagen (o avatar con la inicial si no posee imagen), código único, badge con estado (activo, inactivo, agotado), precio de venta, precio de costo, categoría, proveedor, indicador numérico de stock con alerta visual de color, y los botones de acción rápida para editar, alternar estado o eliminar.
- **Vista de Tabla (`#vista-tabla`)**: Organiza los productos en una tabla estructurada desplegando detalladamente todas las columnas técnicas y sus respectivas acciones.

Si el catálogo no contiene ningún producto registrado, las directivas `@empty` de Blade se encargan de renderizar automáticamente una interfaz limpia informando *"No se encontraron productos registrados."*.
