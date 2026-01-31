FROM httpd:2.4

# Activar módulos SSL y rewrite
RUN sed -i \
    -e 's/#LoadModule ssl_module/LoadModule ssl_module/' \
    -e 's/#LoadModule socache_shmcb_module/LoadModule socache_shmcb_module/' \
    -e 's/#LoadModule rewrite_module/LoadModule rewrite_module/' \
    conf/httpd.conf

# Incluir configuración SSL
RUN echo "Include conf/extra/httpd-ssl.conf" >> conf/httpd.conf

# Copiar configuración personalizada
COPY apache/default-ssl.conf /usr/local/apache2/conf/extra/httpd-ssl.conf

# Copiar certificados
COPY ssl/apache.crt /usr/local/apache2/conf/server.crt
COPY ssl/apache.key /usr/local/apache2/conf/server.key

# Copiar contenido web
COPY www/ /usr/local/apache2/htdocs/

# Exponer puertos
EXPOSE 80 443

# Arrancar Apache en foreground
CMD ["httpd-foreground"]

