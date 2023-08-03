#!/bin/sh

# shellcheck disable=SC2039
#if [ -z "$REPO_BRANCH" ]; then
#    git checkout "$REPO_BRANCH"
#fi
#git pull
composer update -o
php artisan migrate --force
php artisan optimize
php artisan cache:clear
php artisan config:cache
php artisan config:clear
php artisan route:cache
php artisan vendor:publish --ansi --all
if [ "$RUN_COMMAND" != "watch" ]; then
    echo "Cleaning up cache directories"
    #rm -rf .composer .npm .cache node_modules
fi
npm install
npm run $RUN_COMMAND

