#!/usr/bin/env bash
echo "[INFO] Clear application cache"
docker exec app /bin/bash -c "bin/console cache:clear"
docker exec app /bin/bash -c "chown -R www-data:www-data ."