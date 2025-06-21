# Utilise une image officielle PHP avec Apache
FROM php:8.3-apache

# Mise à jour des paquets pour réduire les vulnérabilités
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    libxml2-dev libicu-dev libxslt-dev libzip-dev pkg-config libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring intl gd opcache exif zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Active mod_rewrite pour les URL
RUN a2enmod rewrite

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copie tous les fichiers du projet dans le dossier Apache
COPY . /var/www/html/

# Donne les droits nécessaires
RUN chown -R www-data:www-data /var/www/html

# Expose le port 80 (Apache)
EXPOSE 80

CMD ["apache2-foreground"]