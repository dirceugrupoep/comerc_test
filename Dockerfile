# Usar a imagem oficial do PHP 8 com FPM
FROM php:8.0-fpm

# Instalar dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    nano && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath opcache

# Baixar e instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Definir diretório de trabalho dentro do container
WORKDIR /var/www

# Copiar arquivos da aplicação para o container
COPY . /var/www

# Rodar o PHP-FPM
CMD ["php-fpm"]

EXPOSE 9000
