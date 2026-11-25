# Proyecto Web Híbrido - Seguridad y Contramedidas

## Seguridad en producción

Antes de desplegar la aplicación híbrida en un entorno productivo, se deben aplicar obligatoriamente las siguientes configuraciones:

- **Entorno de aplicación (`api-tienda/.env`):** Establecer `APP_ENV=production` para desactivar comportamientos de desarrollo.
- **Modo depuración (`api-tienda/.env`):** Establecer `APP_DEBUG=false` para evitar exponer trazas de errores, rutas de archivos o consultas SQL ante excepciones.
- **Orígenes permitidos en CORS (`api-tienda/config/cors.php`):** Reemplazar cualquier comodín (`*`) en `allowed_origins` por el dominio real del cliente en producción (por ejemplo, `https://tienda.midominio.pe`).
- **URL de la API con HTTPS (`cliente-pwa/.env`):** Configurar `VITE_API_URL` apuntando exclusivamente a un endpoint cifrado con TLS (`https://api.midominio.pe/api`).
- **Configuración de Capacitor (`cliente-pwa/capacitor.config.json`):** Eliminar por completo el bloque `server` (incluida la opción `cleartext: true`) para que la app Android cargue solo los activos locales empaquetados y no acepte tráfico HTTP sin cifrar.
- **Caducidad de tokens (`api-tienda/config/sanctum.php`):** Mantener definida la expiración de tokens de Sanctum (`'expiration' => 60 * 8`, equivalente a 8 horas) para limitar la ventana de exposición ante un eventual robo de sesión.