# Étape 1 : Utiliser PHP 8.3 pour la construction
FROM php:8.3-fpm AS build

# Installer les dépendances requises
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Supprimer les anciens fichiers de dépendances pour éviter les conflits
RUN rm -rf vendor composer.lock

# Installer les dépendances PHP via Composer
RUN composer install --optimize-autoloader --no-dev


# Assurer les permissions pour le dossier de stockage et de cache de Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exposer le port (si vous utilisez un serveur comme Nginx ou Apache)
# Exposer le port pour php artisan serve
EXPOSE 8000

# Commande par défaut pour démarrer l'application Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
