# Generar Factura POS (Ticket Térmico 80mm)

La lógica de esta funcionalidad se maneja principalmente desde el controlador `HistorialVentasController.php` y la vista `Admin/Venta/facturaPOS.blade.php`.

Para realizar esta acción, el sistema permite acceder a la representación física o imprimible de la factura POS mediante la ruta con nombre `ventas.factura`, que corresponde a la URL `/ventas/factura/{id}` a través de una petición de tipo `GET`. Esta ruta se encuentra protegida por el middleware `auth` y se invoca desde la consola POS o desde el botón de impresión del historial de ventas.

Al recibir la petición `GET` con el parámetro `{id}`, el controlador ejecuta el método `factura` de la clase `HistorialVentasController`. Este método realiza una consulta a la base de datos a través del modelo `Venta` con la instrucción `Venta::with(['usuario', 'metodo', 'detalles.producto'])->findOrFail($id)`. Si la venta existe, el método recupera la cabecera del comprobante, el usuario que procesó la venta, el método de pago registrado y todas las líneas de detalle con los datos de cada producto. Si la venta no se encuentra en la base de datos, Eloquent interrumpe la ejecución y retorna automáticamente un error HTTP 404 (Not Found).

Los datos de la venta obtenidos se empaquetan mediante la función `compact('venta')` y se transmiten a la vista `Admin/Venta/facturaPOS.blade.php`.

La vista `facturaPOS.blade.php` está diseñada bajo las especificaciones estándares de impresión de recibos en papel térmico de 80mm de ancho. Su estructura interna organiza la información en las siguientes secciones bien definidas:

1. **Encabezado Comercial**: Muestra el nombre comercial de la tienda ("SIGI POS"), el número de NIT, la dirección física de la sucursal, el teléfono de contacto y la clasificación tributaria ("Factura de Venta / Régimen Simplificado").
2. **Metadatos del Comprobante**: Despliega la fecha y hora exacta de emisión formateada, el nombre del cliente ("Cliente General"), el número consecutivo de la factura en formato ordinal (`#0000XX`) y el nombre completo del cajero o vendedor.
3. **Detalle de Artículos**: Construye una tabla compacta con bordes punteados que lista cada artículo vendido, especificando el nombre del producto, el precio unitario formateado en moneda local (`$`), la cantidad de unidades y el subtotal de la línea.
4. **Resumen de Totales y Pago**: Muestra el subtotal, el cálculo del IVA (0%), el monto **TOTAL** en resaltado negrita de mayor tamaño, la modalidad o tipo de pago empleado (Efectivo, Tarjeta, Nequi, Transferencia) y el saldo entregado como **Cambio**.
5. **Pie de Página**: Incluye un mensaje institucional de agradecimiento ("¡GRACIAS POR SU COMPRA!"), la marca del sistema ("SIGI POS") y la dirección web de soporte.

En el ámbito técnico, la vista incorpora una regla CSS de impresión `@media print` que oculta automáticamente los botones navegacionales ("Volver" e "Imprimir"), elimina márgenes y encabezados predeterminados del navegador, y ajusta la anchura exacta a `80mm` para garantizar un entallado perfecto al ser enviado a impresoras térmicas de tickets. Adicionalmente, cuenta con la función JavaScript `window.print()` lista para ser ejecutada al momento de la carga de la vista.
