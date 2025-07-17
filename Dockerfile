FROM php:8.2-apache

# 1. Instalar dependencias esenciales
RUN apt-get update
RUN apt-get install -y \
    gnupg \
    apt-transport-https \
    curl \
    g++ \
    make \
    unixodbc-dev \
    libkrb5-dev
RUN rm -rf /var/lib/apt/lists/*

# 2. Configurar repositorio de Microsoft
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list

# 3. Instalar ODBC Driver
RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y \
    msodbcsql17 \
    unixodbc
RUN rm -rf /var/lib/apt/lists/*

# 4. Instalar extensiones PHP
RUN pecl install sqlsrv-5.10.1 pdo_sqlsrv-5.10.1
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

# 5. Dependencias adicionales
RUN apt-get update
RUN apt-get install -y \
    libgssapi-krb5-2
RUN rm -rf /var/lib/apt/lists/*

# 6. Copiar aplicación
COPY app/ /var/www/html/