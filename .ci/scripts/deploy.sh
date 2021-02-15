#!/usr/bin/env bash

echo "Install deployer and deploy to destination:"
vendor/bin/dep -f./.ci/deploy.php deploy production