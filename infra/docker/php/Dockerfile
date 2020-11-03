FROM php:7.4-fpm-buster
LABEL maintainer="ucan-lab <yes@u-can.pro>"
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# timezone environment
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer \
  # Laravel environment
  APP_SERVICES_CACHE=/tmp/cache/services.php \
  APP_PACKAGES_CACHE=/tmp/cache/packages.php \
  APP_CONFIG_CACHE=/tmp/cache/config.php \
  APP_ROUTES_CACHE=/tmp/cache/routes.php \
  APP_EVENTS_CACHE=/tmp/cache/events.php \
  VIEW_COMPILED_PATH=/tmp/cache/views \
  SESSION_DRIVER=cookie \
  LOG_CHANNEL=stderr \
  DB_CONNECTION=mysql \
  DB_HOST=db \
  DB_PORT=3306 \
  DB_DATABASE=laravel_local \
  DB_USERNAME=phper \
  DB_PASSWORD=secret

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
  apt-get -y install curl wget gnupg git libicu-dev libonig-dev libzip-dev unzip locales && \
  curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
  apt-get -y install nodejs && \
  npm install npm@latest -g && \
  npm install -g yarn && \
  npm install --global --unsafe-perm puppeteer && \
  wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - && \
  sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' && \
  apt-get update && \
  apt-get install -y google-chrome-stable fonts-ipafont-gothic fonts-ipafont-mincho fonts-wqy-zenhei fonts-thai-tlwg fonts-kacst fonts-freefont-ttf libxss1 \
      --no-install-recommends && \
  rm -rf /var/lib/apt/lists/* && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  locale-gen en_US.UTF-8 && \
  localedef -f UTF-8 -i en_US en_US.UTF-8 && \
  mkdir /var/run/php-fpm && \
  mkdir -p /tmp/cache/views && \
  chmod 777 -R /tmp/cache && \
  docker-php-ext-install intl pdo_mysql zip bcmath sockets && \
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD true
ENV CHROMIUM_PATH google-chrome-stable

COPY ./php-fpm.d/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./php.ini /usr/local/etc/php/php.ini

WORKDIR /work/backend
