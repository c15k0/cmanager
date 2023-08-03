# 746792098073.dkr.ecr.eu-west-1.amazonaws.com/php_update
FROM c15k02k18/php:8.2

LABEL 'author'='Fran Lopez <fran.lopez@pandago.eco>'
LABEL 'version'='1.0.0'

RUN apt-get -y update && apt-get -y upgrade
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

RUN npm install -g npm yarn

RUN mkdir -p storage/app storage/framework/cache storage/framework/sessions /var/www/node_modules \
    storage/framework/views storage/logs /home/${DOCKER_USER:-ubuntu}/.composer /home/${DOCKER_USER:-ubuntu}/.npm /home/${DOCKER_USER:-ubuntu}/.cache

RUN chmod -R 777 /home/${DOCKER_USER:-ubuntu}/.composer /home/${DOCKER_USER:-ubuntu}/.npm /home/${DOCKER_USER:-ubuntu}/.cache

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer
RUN rm composer-setup.php

RUN useradd -u 1000 ${DOCKER_USER:-ubuntu}
USER ${DOCKER_USER:-ubuntu}

ENV REPO_BRANCH ${REPO_BRANCH:-main}
ENV RUN_COMMAND ${RUN_COMMAND:-watch}
CMD ["chmod", "+x", "./docker/update/update.sh"]
CMD ["./docker/update/update.sh"]
