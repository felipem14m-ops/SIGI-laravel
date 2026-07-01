# Inicio y Cierre de Sesión (Login / Logout)

La lógica de esta funcionalidad se gestiona principalmente desde el controlador `AuthenticatedSessionController.php`, la clase de solicitud de validación `LoginRequest.php` y la vista `auth/login.blade.php`.

## Inicio de Sesión (Login)

Para ingresar al sistema, el usuario accede al formulario de acceso mediante una petición GET a la ruta con nombre `login` (URL `/login`). Este formulario solicita las credenciales del usuario: correo electrónico (`email`) y contraseña (`password`), además de ofrecer una casilla para recordar la sesión (`remember`).

Al presionar el botón "Entrar al Sistema", los datos se envían a través de una petición POST a la misma URL `/login`. El flujo se procesa de la siguiente manera:

1. **Validación y Autenticación**:
   La petición es interceptada por el método `store` en `AuthenticatedSessionController`, el cual recibe un objeto `LoginRequest`.
   * El controlador ejecuta `$request->authenticate()`, llamando a la lógica interna del request.
   * `LoginRequest` valida que los campos estén presentes, correctos y no superen límites de tasa de intentos fallidos (`RateLimiter`).
   * Para realizar la autenticación se llama a `Auth::attempt()`. Dado que la base de datos utiliza la columna `contrasena` en lugar de la predeterminada de Laravel (`password`), el modelo `User` tiene configurado el método `getAuthPassword()` que devuelve la propiedad `contrasena`. Esto permite realizar la comparación del hash de forma transparente.
   * Si las credenciales coinciden, se limpia el registro de intentos fallidos. Si fallan, se incrementa el contador de reintentos y se arroja una excepción de validación (`ValidationException`) devolviendo el mensaje correspondiente a la vista.

2. **Regeneración de Sesión**:
   Tras una autenticación exitosa, el controlador ejecuta `$request->session()->regenerate()` para prevenir ataques de fijación de sesión.

3. **Redirección**:
   Finalmente, se ejecuta `redirect()->intended(route('dashboard'))`, redirigiendo al usuario a la vista del panel correspondiente a su rol o a la URL previa que haya intentado visitar.

---

## Cierre de Sesión (Logout)

El cierre de sesión está configurado a través de una petición POST a la ruta con nombre `logout` (URL `/logout`).

Al ejecutarse, el método `destroy` del controlador `AuthenticatedSessionController` realiza los siguientes pasos:
1. Cierra la sesión activa del guard web usando `Auth::guard('web')->logout()`.
2. Invalida la sesión actual en el servidor mediante `$request->session()->invalidate()`.
3. Regenera el token de seguridad CSRF con `$request->session()->regenerateToken()`.
4. Redirige al usuario de vuelta a la página principal de bienvenida (`/`).
