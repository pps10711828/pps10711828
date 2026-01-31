FROM pps10711828/practica_hardening:waf

ENV DEBIAN_FRONTEND=noninteractive

# Instalar mod_security y herramientas necesarias
RUN apt update && apt install -y \
    libapache2-mod-security2 \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Activar módulo security2
RUN a2enmod security2

# Descargar OWASP CRS
WORKDIR /opt
RUN git clone https://github.com/coreruleset/coreruleset.git

# Crear directorio modsecurity si no existe
RUN mkdir -p /etc/modsecurity

# Copiar archivo de configuración base
RUN cp /opt/coreruleset/crs-setup.conf.example /etc/modsecurity/crs-setup.conf

# Copiar reglas OWASP
RUN cp -r /opt/coreruleset/rules /etc/modsecurity/

# Activar el motor de reglas (si está en modo DetectionOnly)
RUN if [ -f /etc/modsecurity/modsecurity.conf-recommended ]; then \
        sed -i 's/SecRuleEngine DetectionOnly/SecRuleEngine On/' /etc/modsecurity/modsecurity.conf-recommended && \
        cp /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf ; \
    fi

# Asegurar que Apache carga las reglas
RUN echo '<IfModule security2_module>\n\
    SecDataDir /var/cache/modsecurity\n\
    IncludeOptional /etc/modsecurity/*.conf\n\
    Include /etc/modsecurity/rules/*.conf\n\
</IfModule>' > /etc/apache2/mods-enabled/security2.conf

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]

