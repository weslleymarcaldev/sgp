#!/usr/bin/env bash
echo "PHP:"     && php -v
echo "CI4 CLI:" && php spark --version
echo
echo "Composer deps:" 
composer show --no-ansi
echo
echo "Node/NPM:" && node -v && npm -v
echo "Front-end deps:" 
npm list --depth=0 --no-ansi
echo; echo "Tailwind CLI:"
npx tailwindcss --version
echo
echo "Docker images:"
docker-compose images
echo
echo "MySQL version:"
docker exec -i banco mysql --version

echo; echo "CI4 Routes:"
docker-compose exec app php spark routes

echo; echo "Migrations status:"
docker-compose exec app php spark migrate:status