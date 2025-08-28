# FROM php:8.3-apache

# WORKDIR /var/www/html

# # Installation des extensions nécessaires
# RUN apt-get update && apt-get install -y --no-install-recommends \
#     libpq-dev \
#     libpng-dev libjpeg-dev libfreetype6-dev \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install pdo pdo_pgsql gd \
#     && apt-get clean && rm -rf /var/lib/apt/lists/*

# # Active mod_rewrite (optionnel)
# RUN a2enmod rewrite

# # Copie des fichiers
# COPY . /var/www/html/
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# # Apache écoute sur le port 80
# EXPOSE 80

# CMD ["apache2-foreground"]

FROM php:8.3-apache

# Répertoire de travail
WORKDIR /var/www/html

# Installation des extensions nécessaires (gd, pdo_pgsql) + PECL mongodb
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libssl-dev pkg-config \
    libpq-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd \
    # --- Driver MongoDB via PECL ---
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    # Nettoyage
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite (si routing)
RUN a2enmod rewrite

# Installer Composer (copié depuis l'image officielle)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les sources
COPY . /var/www/html/

# Exposer le port 80
EXPOSE 80

CMD ["apache2-foreground"]