# ETAPA 1: El "Builder" 
FROM node:18 as gulp-builder

WORKDIR /app

COPY . .

# 1. Instalar dependencias
RUN npm install

# 2. Ejecutar Gulp
RUN npx gulp build

# ETAPA 2: El Servidor Web 
FROM php:8.2-apache

# Instalar extensiones de BD
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

# Copiar el código fuente y carpeta build generada por gulp de la app
COPY . /var/www/html/

COPY --from=gulp-builder /app/build /var/www/html/build

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/

