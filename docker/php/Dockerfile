# ==========================================
# ETAPA 1: El "Builder" 
# ==========================================
FROM node:18 as gulp-builder

WORKDIR /app

COPY . .

# 1. Instalar dependencias
RUN npm install

# 2. Ejecutar Gulp
# OJO: En docker-compose usabas "npx gulp". 
# Si tu tarea por defecto se queda "observando" (watch), el build se congelará.
# Lo ideal es ejecutar la tarea de construcción única, usualmente 'build'.
# Si no tienes tarea 'build', intenta: RUN npx gulp build
# Por seguridad, probaremos ejecutar la tarea por defecto asumiendo que compila:
RUN npx gulp build

# ==========================================
# ETAPA 2: El Servidor Web (Tu servicio 'php')
# ==========================================
FROM php:8.2-apache

# Instalar extensiones de BD
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

# Copiar el código fuente de la app
COPY . /var/www/html/

# --- PASO CRÍTICO ---
# Copiamos la carpeta "build" que generó Node en la etapa 1
# Asegúrate de que Gulp genera los archivos en /app/build
COPY --from=gulp-builder /app/build /var/www/html/build
# --------------------

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/

