
# Partimos de la imagen base que ya subiste
FROM pps10711828/csp-apache:latest

# Instalamos ModSecurity y dependencias
RUN apt update && apt install -y libapache2-mod-security2 \
php \
libapache2-mod-php

# Copiar archivo de configuración recomendado
RUN cp /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf

# Habilitar el módulo ModSecurity en Apache
RUN a2enmod security2

# Copiar página de prueba y formulario
COPY index.html /var/www/html/
COPY post.php /var/www/html/

# Reinicio de Apache al iniciar contenedor
CMD ["apachectl", "-D", "FOREGROUND"]

