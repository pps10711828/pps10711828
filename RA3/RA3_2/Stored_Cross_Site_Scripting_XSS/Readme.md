# Reporte de Explotación: Stored Cross Site Scripting (XSS) - DVWA

Este documento detalla la explotación de una vulnerabilidad de **XSS persistente** en el nivel de seguridad **Medium**, demostrando cómo evadir restricciones de longitud en el lado del cliente y filtros de etiquetas en el servidor.

---

## 🔍 Análisis de la Vulnerabilidad

En el nivel de seguridad **Medium**, la aplicación implementa defensas básicas que pueden ser eludidas con técnicas de manipulación de peticiones y variación de caracteres.

* **Límite de Caracteres (Lado del Cliente):** El campo `Name` posee un atributo HTML `maxlength` que restringe la cantidad de caracteres permitidos para el nombre, impidiendo la inserción de scripts largos directamente.
* **Filtro de Etiquetas (Lado del Servidor):** El servidor aplica un filtro que elimina la cadena exacta `<script>`, pero de forma sensible a mayúsculas y minúsculas (case-sensitive).
* **Punto de Inyección:** El campo `Name` es vulnerable debido a una sanitización insuficiente antes de almacenar el dato en la base de datos.

---

## 🚀 Proceso de Explotación

### 1. Evasión del límite de caracteres
Para introducir el payload en el campo `Name`, se utilizaron las herramientas de desarrollador del navegador (F12/Inspect Element):
* Se localizó el elemento de entrada del nombre.
* Se modificó manualmente el atributo `maxlength` de su valor original a uno superior (ej. `100`), permitiendo escribir el payload completo.

### 2. Bypass del filtro mediante Case Variation
Para evitar que el filtro del servidor detectara y eliminara la etiqueta, se alternaron mayúsculas y minúsculas. El navegador interpreta estas etiquetas como válidas para ejecutar JavaScript.

**Payload utilizado:**
```html
<sCrIpT>alert(document.cookie);</ScRiPt>
```

### 3. Ejecución y Persistencia
Una vez enviado el formulario, el script se guarda permanentemente en el libro de visitas (Guestbook). Al cargar la página, el navegador ejecuta el código inyectado automáticamente.

Captura de la ejecución exitosa:

* Impacto: Se ha logrado extraer la cookie de sesión del usuario de forma automática y persistente al cargar el módulo.

* Evidencia: La ventana de alerta muestra los datos de sesión: security=medium; PHPSESSID=a967a138f573261c0dae850c8f944b49.

## 🛡️ Medidas de Mitigación
Para prevenir ataques de XSS almacenado, se recomienda:

* Sanitización Completa: Utilizar funciones de codificación como htmlspecialchars() en PHP para que el navegador trate cualquier entrada como texto plano y no como código ejecutable.

* Validación en el Servidor: No depender de restricciones en el navegador (como maxlength); siempre validar la longitud y el tipo de contenido en el lado del servidor.

* Content Security Policy (CSP): Implementar una política estricta que bloquee la ejecución de scripts en línea (inline scripts).

* Atributo HttpOnly: Configurar las cookies de sesión con el atributo HttpOnly para evitar que JavaScript pueda acceder a ellas.

[!WARNING]
Aviso de Seguridad: Este reporte tiene fines exclusivamente educativos. El acceso no autorizado a sistemas informáticos es una actividad ilegal.
