FROM pps10711828/practica_hardening:owasp

USER root

# Instalar mod_evasive
RUN apt update && apt install -y libapache2-mod-evasive

# Crear directorio de logs de mod_evasive
RUN mkdir -p /var/log/mod_evasive

# Configurar mod_evasive
RUN printf '%s\n' \
'<IfModule mod_evasive20.c>' \
'    DOSHashTableSize    3097' \
'    DOSPageCount        1' \
'    DOSSiteCount        1' \
'    DOSPageInterval     1' \
'    DOSSiteInterval     1' \
'    DOSBlockingPeriod   10' \
'    DOSEmailNotify      you@example.com' \
'    DOSSystemCommand    "sudo -u nobody /sbin/iptables -A INPUT -s %s -j DROP"' \
'    DOSLogDir           "/var/log/mod_evasive"' \
'</IfModule>' \
> /etc/apache2/mods-available/evasive.conf

# Activar mod_evasive
RUN a2enmod evasive

# Configuración adicional Apache: desactivar .htaccess y limitar IP
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride All/AllowOverride None/' /etc/apache2/sites-available/000-default.conf && \
    echo '<Location />\n    Require ip 192.168.1.24\n</Location>' >> /etc/apache2/sites-available/000-default.conf

# Copiar index
COPY index.html /var/www/html/index.html

EXPOSE 80 443

CMD ["apachectl", "-D", "FOREGROUND"]

