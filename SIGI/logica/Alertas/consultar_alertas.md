# Consultar Alertas de Stock

La lógica de esta funcionalidad se maneja principalmente desde el controlador `AlertasController.php` y la vista `Admin/Alertas/ListasdeAlerta.blade.php`.

Para realizar esta acción, el usuario autenticado interactúa con la opción "Alertas de Stock" que se ubica en el menú lateral izquierdo del panel de administración, dentro de la sección "Inventario". Este enlace redirige al controlador `AlertasController.php` a través de la ruta con nombre `alertas.index`, que corresponde a la URL `/alertas` mediante una petición de tipo `GET`. Esta ruta se encuentra protegida por el middleware `auth`, restringiendo el acceso exclusivamente a usuarios con sesión activa.

Al recibir la petición `GET`, el método `index` de la clase `AlertasController` inicia la construcción de una consulta de Eloquent sobre el modelo `Producto`. La consulta establece como condición base filtrar únicamente aquellos productos en los que la columna de existencias actuales sea menor o igual al umbral del stock mínimo definido para dicho ítem, utilizando la instrucción `Producto::with(['categoria', 'proveedor'])->whereColumn('stock_actual', '<=', 'stock_minimo')`. La consulta incluye la carga impaciente (*eager loading*) de los modelos relacionados `categoria` y `proveedor` para optimizar las peticiones a la base de datos.

A continuación, el controlador evalúa los filtros opcionales recibidos en la solicitud `$request`:
1. **Filtro por Búsqueda (`search`)**: Si el usuario ingresa un texto en la barra de búsqueda, se añade una cláusula que busca coincidencias parciales con el operador `LIKE` tanto en el atributo `nombre` como en la columna `codigoUnico` del producto.
2. **Filtro por Tipo de Alerta (`tipo_alerta`)**: Si el usuario filtra por nivel crítico, el controlador evalúa el valor:
   - Si se selecciona `'agotado'`, agrega la condición `where('stock_actual', '<=', 0)` para mostrar solo aquellos ítems cuyas existencias están completamente en cero.
   - Si se selecciona `'bajo'`, agrega la condición `where('stock_actual', '>', 0)` para filtrar los productos que poseen unidades en inventario pero han sobrepasado su nivel mínimo recomendado.
3. **Filtro por Estado (`estado`)**: Si se especifica un estado del producto (ej. `activo` o `inactivo`), el controlador aplica una condición directa sobre la columna `estado`.

Posteriormente, el controlador ejecuta la paginación con un límite de 10 registros por página aplicando `$query->paginate(10)->withQueryString()`.

Para proporcionar un resumen estadístico en la parte superior de la vista, el controlador calcula dinámicamente tres métricas fundamentales mediante consultas directas a la base de datos:
- `$totalAgotados`: Cuenta la cantidad total de productos activos con existencias menores o iguales a cero (`stock_actual <= 0`).
- `$totalBajoStock`: Cuenta los productos cuyas existencias son mayores a cero pero menores o iguales a su stock mínimo (`stock_actual <= stock_minimo`).
- `$totalAlertas`: Calcula la suma de `$totalAgotados` más `$totalBajoStock`.

Todas estas variables (`alertas`, `totalAgotados`, `totalBajoStock`, `totalAlertas`) se empaquetan con la función `compact` y se envían a la vista `Admin/Alertas/ListasdeAlerta.blade.php`.

En la vista `ListasdeAlerta.blade.php`, el sistema presenta cuatro tarjetas superiores de resumen con sus respectivos contadores e indicadores visuales de color. En la parte inferior, renderiza la tabla de alertas donde se detalla el código único del producto, su nombre y categoría, el proveedor asociado, el stock actual formateado en rojo (si está agotado) o en naranja (si está bajo), el stock mínimo requerido, y el botón interactivo "✓ Atender". Si no existen alertas de inventario registradas en el sistema, la directiva `@empty` de Blade despliega una tarjeta de confirmación en tono verde indicando *"¡Excelente! No hay alertas de stock pendientes."*.
