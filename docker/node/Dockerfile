# 746792098073.dkr.ecr.eu-west-1.amazonaws.com/pandago_saas_echo
FROM c15k02k18/node:18

LABEL 'author'='Fran Lopez <fran.lopez@pandago.eco>'
LABEL 'version'='1.0.0'

RUN npm install -g laravel-echo-server

# Set working directory
WORKDIR /var/www

EXPOSE 6001

CMD ["laravel-echo-server", "start", "--force"]
