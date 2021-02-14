#!/usr/bin/env bash

which ssh-agent || ( apk --no-cache add openssh-client )
eval $(ssh-agent -s)
echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null
#mkdir -p ~/.ssh
#chmod 700 ~/.ssh
#echo "$SSH_KNOWN_HOSTS" > ~/.ssh/known_hosts
#chmod 644 ~/.ssh/known_hosts
curl -sS https:/getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
composer global require -q --dev deployer/deployer:~6.4 deployphp/recipes:~6.2
/home/php/.composer/vendor/bin/dep deploy production
