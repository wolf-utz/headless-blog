#!/usr/bin/env bash

echo "Login to server via ssh."
which ssh-agent || ( apk --no-cache add openssh-client )
eval "$(ssh-agent -s)"

SSH_PRIVATE_KEY=$(echo "$SSH_PRIVATE_KEY" | base64 -d)
echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null
#
#echo "Add known_hosts."
#SSH_HOST="lupus-code.it"
#mkdir -p "$HOME"/.ssh
#chmod 700 "$HOME"/.ssh
#ssh-keyscan -H "${SSH_HOST}" >> "$HOME"/.ssh/known_hosts
#chmod 644 "$HOME"/.ssh/known_hosts

# Install composer and deployer.
echo "Install composer and deployer and deploy."
composer global require -q --dev deployer/deployer:~6.4 deployphp/recipes:~6.2
"$HOME"/.composer/vendor/bin/dep -f./.ci/deploy.php deploy production
