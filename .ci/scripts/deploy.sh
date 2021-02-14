#!/usr/bin/env bash

# Login to server via ssh.
which ssh-agent || ( apk --no-cache add openssh-client )
eval "$(ssh-agent -s)"
echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null

# Add known_hosts.
SSH_HOST="lupus-code.it"
mkdir -p ~/.ssh
chmod 700 ~/.ssh
ssh-keyscan -H "${SSH_HOST}" >> ~/.ssh/known_hosts
chmod 644 ~/.ssh/known_hosts

# Install composer and deployer.
curl -sS https:/getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
composer global require -q --dev deployer/deployer:~6.4 deployphp/recipes:~6.2
"$HOME"/.composer/vendor/bin/dep -f./.ci/deploy.php deploy production
