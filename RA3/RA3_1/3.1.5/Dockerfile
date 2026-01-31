FROM debian:12

ENV DEBIAN_FRONTEND=noninteractive

# Instalar nginx, php y modsecurity
RUN apt update && apt install -y \
    nginx \
    php8.2-fpm \
    openssl \
    git \
    apache2-utils \
    libnginx-mod-http-modsecurity \
    && rm -rf /var/lib/apt/lists/*

# Crear carpeta modsecurity
RUN mkdir -p /etc/nginx/modsec

# Clonar OWASP CRS oficial (el que te piden)
RUN git clone https://github.com/SpiderLabs/owasp-modsecurity-crs.git /opt/owasp-crs

# Copiar TODO el contenido de rules (incluye .data)
RUN mkdir -p /etc/nginx/modsec/rules && \
    cp /opt/owasp-crs/crs-setup.conf.example /etc/nginx/modsec/crs-setup.conf && \
    cp -r /opt/owasp-crs/rules/* /etc/nginx/modsec/rules/

# Archivo principal modsecurity (activar motor)
RUN cat <<EOF > /etc/nginx/modsec/main.conf
SecRuleEngine On
Include /etc/nginx/modsec/crs-setup.conf
Include /etc/nginx/modsec/rules/*.conf
EOF

# Configurar PHP-FPM para usar TCP (evita 502)
RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /etc/php/8.2/fpm/pool.d/www.conf

# Generar certificado SSL autofirmado
RUN mkdir -p /etc/nginx/ssl && \
    openssl req -x509 -nodes -days 365 \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=Practica/CN=localhost" \
    -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/nginx.key \
    -out /etc/nginx/ssl/nginx.crt

# Crear usuario autenticación básica
RUN htpasswd -bc /etc/nginx/.htpasswd admin admin

# Copiar index.php
COPY index.php /var/www/html/index.php

# Configuración nginx completa
RUN cat <<EOF > /etc/nginx/nginx.conf
load_module modules/ngx_http_modsecurity_module.so;

events {}

http {
    include       mime.types;
    default_type  application/octet-stream;

    modsecurity on;
    modsecurity_rules_file /etc/nginx/modsec/main.conf;

    server {
        listen 80;
        return 301 https://\$host\$request_uri;
    }

    server {
        listen 443 ssl;
        server_name localhost;

        ssl_certificate /etc/nginx/ssl/nginx.crt;
        ssl_certificate_key /etc/nginx/ssl/nginx.key;

        add_header Strict-Transport-Security "max-age=63072000; includeSubDomains" always;
        add_header Content-Security-Policy "default-src 'self'" always;

        root /var/www/html;
        index index.php;

        location /protected {
            auth_basic "Zona protegida";
            auth_basic_user_file /etc/nginx/.htpasswd;
        }

        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        }
    }
}
EOF

EXPOSE 80 443

CMD service php8.2-fpm start && nginx -g "daemon off;"

