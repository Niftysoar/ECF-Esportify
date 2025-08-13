FROM php:8.3-apache

WORKDIR /var/www/html

# Installation des extensions nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Active mod_rewrite (optionnel)
RUN a2enmod rewrite

# Copie des fichiers
COPY . /var/www/html/

# Apache écoute sur le port 80
EXPOSE 80

CMD ["apache2-foreground"]