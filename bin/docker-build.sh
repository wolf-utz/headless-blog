#!/usr/bin/env bash
echo "[INFO] Running a composer install"
docker exec app /bin/bash -c "composer install"
docker exec app /bin/bash -c "chown -R www-data:www-data ."