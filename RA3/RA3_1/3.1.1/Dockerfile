FROM debian:12

# Instalamos Apache y SSL
RUN apt update && apt install -y \
    apache2 \
    openssl \
    && apt clean

# Habilitar módulos necesarios
RUN a2enmod ssl headers

# Crear certificado autofirmado
RUN openssl req -x509 -nodes -days 365 \
    -subj "/C=ES/ST=Practica/L=Docker/O=Apache/CN=localhost" \
    -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache.key \
    -out /etc/ssl/certs/apache.crt

# Copiar configuraciones
COPY apache.conf /etc/apache2/conf-available/security.conf
RUN a2enconf security

COPY site.conf /etc/apache2/sites-available/secure.conf
RUN a2ensite secure && a2dissite 000-default

# Copiar web
COPY index.html /var/www/html/

# Exponer puertos
EXPOSE 80 443

# Arrancar Apache
CMD ["apachectl", "-D", "FOREGROUND"]
