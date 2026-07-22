FROM php:8.2-apache

# Copie le projet dans le dossier du serveur web
COPY . /var/www/html/

# Crée le dossier data si besoin et donne les permissions d'écriture à PHP (www-data)
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html/data && \
    chmod -R 775 /var/www/html/data

EXPOSE 80
