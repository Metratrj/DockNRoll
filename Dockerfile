# Stage 1: Composer dependencies
FROM composer:2.8 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
COPY app/External ./app/External
RUN find && sleep 5
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

# Stage 2: Node dependencies & build
FROM node:20-alpine as frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY public/css/style.css ./public/css/style.css
RUN npx @tailwindcss/cli -i ./public/css/style.css -o ./public/css/out.css

# Stage 3: Final application image
FROM php:8.2-fpm-alpine

# Install nginx
RUN apk add --no-cache nginx

# Copy nginx configuration
COPY default.conf /etc/nginx/http.d/default.conf

WORKDIR /var/www/html

# Copy composer dependencies
COPY --from=vendor /app/vendor ./vendor

# Copy frontend assets
COPY --from=frontend /app/public/css/out.css ./public/css/out.css

# Copy application source
COPY . .

# Adjust permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Expose port 80 for nginx
EXPOSE 80

# Start php-fpm and nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
