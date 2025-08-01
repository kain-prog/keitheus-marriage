FROM ubuntu:20.04

# Define variáveis para evitar perguntas interativas no APT
ENV DEBIAN_FRONTEND=noninteractive

# Atualiza o sistema e instala dependências básicas
RUN apt-get update \
    && apt-get install -y software-properties-common curl gnupg

RUN add-apt-repository ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y \
        apache2 \
        php8.3 \
        libapache2-mod-php8.3 \
        php8.3-cli \
        php8.3-fpm \
        php8.3-common \
        php8.3-mysql \
        php8.3-zip \
        php8.3-gd \
        php8.3-mbstring \
        php8.3-curl \
        php8.3-xml \
        php8.3-bcmath \
        php8.3-intl \
        php8.3-gmp \
        php8.3-xdebug \
    && apt-get clean

# Instala o Node.js (versão 18) e npm via NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Habilita os módulos do Apache
RUN a2enmod rewrite headers

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configura permissões
RUN chown -R www-data:www-data var

# Define o comando padrão para iniciar o Apache
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
