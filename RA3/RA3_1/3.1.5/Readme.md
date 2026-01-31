# Nginx con PHP, mod_security y OWASP CRS en Docker

## Descripción
Este proyecto configura un servidor web **Nginx** seguro dentro de un contenedor Docker, integrando:

- **PHP-FPM** para servir contenido dinámico.  
- **mod_security** junto con las **reglas oficiales OWASP CRS** para proteger frente a ataques web comunes.  
- **SSL/TLS** mediante certificado autofirmado.  
- **Cabeceras de seguridad**: HSTS y Content Security Policy (CSP).  
- **Autenticación básica** en directorios protegidos.  

Se aplican medidas de seguridad que permiten proteger la aplicación web frente a:

- Cross-Site Scripting (XSS)  
- Inyección SQL  
- Path Traversal  
- Remote Command Execution  
- Acceso no autorizado a directorios críticos  

Esta imagen sirve como base segura para desplegar aplicaciones web en Nginx siguiendo buenas prácticas de seguridad.

---

## Estructura del proyecto

- `Dockerfile` → Construcción de la imagen con Nginx, PHP-FPM y OWASP CRS  
- `index.php` → Página de prueba con `phpinfo()`  
- `ssl/` → Carpeta para certificados (generada en build)  
- `images/` → Capturas de pantalla de funcionamiento  

---

## Requisitos

- Docker instalado en el sistema  
- Imagen base Debian 12 (se descarga automáticamente en el build)  

---

## Instalación de la imagen

Para descargar la imagen desde Docker Hub:

docker pull pps10711828/3.1:pr3.1.5

Después haremos un run del contenedor
docker run -d -p 8091:80 -p 8092:443 --name nombre_del_contenedor pps10711828/3.1:pr3.1.5

### Comprobación

Para mirar que funcione vamos al navegador y escribimos https://localhost:8092

![php info nginx](/images/1.png)
