
clear

printf "Running Tests\n"

./vendor/bin/behat --colors -f progress --colors -vv

printf "\n\n\nRunning php cs fixer\n"

vendor/bin/php-cs-fixer fix
