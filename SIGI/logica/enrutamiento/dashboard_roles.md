# Enrutamiento de Dashboard según el Rol

La lógica que gestiona el redireccionamiento y la visualización del panel de control o dashboard basado en el rol del usuario se maneja a través del archivo de rutas `routes/web.php`, apoyándose en las relaciones de base de datos declaradas en los modelos `Role.php` y `User.php`, y en el controlador de sesión `AuthenticatedSessionController.php`. El objetivo de este flujo es asegurar que cada usuario, al iniciar sesión, sea dirigido automáticamente a la interfaz correspondiente a sus permisos y funciones en el sistema.

Para estructurar esta lógica, el modelo `Role` define la representación de los distintos perfiles de usuario y establece una relación de uno a muchos llamada `users` hacia el modelo `User`, indicando que un rol puede tener múltiples usuarios asociados. Por su parte, el modelo `User` declara la relación de pertenencia llamada `role` que apunta hacia el modelo `Role` mediante la clave foránea `id_rol`, lo que permite consultar fácilmente a qué perfil pertenece un usuario específico en cualquier momento del ciclo de vida de la petición.

Cuando un usuario inicia sesión correctamente ingresando sus datos en el formulario de acceso, la petición POST es recibida por el método `store` del controlador `AuthenticatedSessionController.php`. Este método se encarga de autenticar las credenciales, regenerar la sesión para evitar problemas de seguridad y ejecutar una redirección mediante el método `intended`, el cual envía al usuario a la ruta con nombre `dashboard`. Este método redirige al usuario a la URL que intentaba acceder originalmente o, en su defecto, a la ruta por defecto del panel de control.

La ruta `/dashboard` está definida en el archivo `routes/web.php` y está protegida por el middleware `auth`, garantizando que únicamente los usuarios que han iniciado sesión puedan ingresar. Al ejecutarse la ruta, una función captura el usuario autenticado actualmente a través del helper `Auth` y obtiene su identificador de rol mapeado a `role_id` (a través de un accessor en el modelo `User`).

El sistema utiliza una estructura condicional (`if`, `elseif`) basada en el valor de `role_id`:
* Si el identificador de rol es igual a **1** (Administrador), el sistema devuelve la vista del panel de administración ubicada en `resources/views/admin/dashboard.blade.php`.
* Si el identificador de rol es igual a **2** (Empleado), se retorna la vista correspondiente ubicada en `resources/views/empleado/dashboard.blade.php`.

Esto logra una segmentación dinámica y segura de la interfaz de usuario basada en roles en el núcleo del enrutamiento de Laravel.


