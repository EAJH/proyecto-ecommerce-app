FROM php:8.2-apache
# Instala las extensiones para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli
 
# Habilitar mod_rewrite para URLs amigables (.htaccess)
RUN a2enmod rewrite

# Copiar todo el código de la carpeta actual a la carpeta pública de Apache
COPY . /var/www/html/

# Ajustar permisos para que Apache pueda leerlos 
RUN chown -R www-data:www-data /var/www/html/

