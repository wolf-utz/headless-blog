#!/usr/bin/env bash

echo "Install deployer and deploy to destination:"
composer global require -q --dev deployer/deployer:~6.4 deployphp/recipes:~6.2
~/.composer/vendor/bin/dep -f./.ci/deploy.php deploy production