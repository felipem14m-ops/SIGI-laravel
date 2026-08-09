# Lógica del Controlador de Alertas (AlertasController.php)

La lógica de esta funcionalidad se maneja principalmente desde el controlador `AlertasController.php` y la vista `Admin/Alertas/ListasdeAlerta.blade.php`.

Para realizar esta acción, el usuario autenticado interactúa con la opción "Alertas de Stock" ubicada en el menú lateral izquierdo del panel de administración, dentro de la sección "Inventario". Este enlace está configurado para dirigir al controlador `AlertasController.php` a través de la ruta con nombre `alertas.index`, que corresponde a la URL `/alertas` mediante una petición de tipo `GET`. Esta ruta se encuentra resguardada por el middleware de autenticación `auth`.

Al recibir la petición `GET`, el controlador ejecuta el método `index` de la clase `AlertasController`. Este método es el encargado de procesar la lógica de consulta, filtrado y cálculo estadístico del estado del inventario.

## 1. Construcción de la Consulta Base
El método `index` inicia preparando una consulta dinámica de Eloquent sobre el modelo `Producto`. Para optimizar la comunicación con la base de datos y evitar el problema de rendimiento N+1, realiza la carga impaciente (*eager loading*) de los modelos relacionados mediante `Producto::with(['categoria', 'proveedor'])`. La condición base indispensable de la consulta evalúa que la columna de inventario actual sea menor o igual al umbral mínimo configurado para el producto, mediante la instrucción `whereColumn('stock_actual', '<=', 'stock_minimo')`.

## 2. Procesamiento de Filtros Dinámicos
A continuación, el controlador evalúa condicionalmente los parámetros enviados en el objeto de la solicitud `$request`:
- **Búsqueda por Texto (`search`)**: Si el usuario digita un valor en el campo de búsqueda, el método agrega un subgrupo de condiciones `where` utilizando funciones anónimas (`closures`) para buscar coincidencias parciales con el operador `LIKE` tanto sobre el atributo `nombre` como sobre el `codigoUnico` del producto.
- **Filtro por Nivel Crítico (`tipo_alerta`)**:
  - Si el usuario selecciona el valor `'agotado'`, la consulta restringe los resultados a productos cuyo stock sea menor o igual a cero (`where('stock_actual', '<=', 0)`).
  - Si selecciona el valor `'bajo'`, la consulta filtra únicamente productos cuyo stock sea mayor a cero pero permanezca menor o igual al stock mínimo (`where('stock_actual', '>', 0)`).
- **Filtro por Estado Operativo (`estado`)**: Si se recibe un parámetro de estado, aplica la condición directa `where('estado', $request->estado)`.

## 3. Paginación de Resultados
Una vez construidas las cláusulas de filtrado, el controlador invoca el método `paginate(10)` para limitar la respuesta a 10 ítems por página, encadenando `withQueryString()` para garantizar que los parámetros de filtro seleccionados por el usuario se mantengan persistentes al navegar entre las distintas páginas del listado.

## 4. Cálculo Estadístico de Métricas
Para alimentar los contadores numéricos de las tarjetas resumen en la interfaz, el controlador ejecuta tres consultas adicionales independientes sobre el modelo `Producto`:
- `$totalAgotados`: Cuenta la cantidad total de productos cuyo `stock_actual` sea menor o igual a cero.
- `$totalBajoStock`: Cuenta los productos cuyo `stock_actual` sea mayor a cero pero menor o igual a `stock_minimo`.
- `$totalAlertas`: Calcula la suma combinada de ambos contadores (`$totalAgotados + $totalBajoStock`).

## 5. Renderizado y Respuesta a la Vista
Finalmente, el controlador empaqueta la colección paginada `$alertas` junto con los tres contadores métricos (`$totalAgotados`, `$totalBajoStock`, `$totalAlertas`) utilizando la función `compact` de PHP, y los transmite a la vista `Admin/Alertas/ListasdeAlerta.blade.php`. La vista recibe estas variables para construir dinámicamente tanto los paneles estadísticos superiores como la tabla detallada de alertas.
