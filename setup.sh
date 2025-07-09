#!/usr/bin/env bash
set -e

case "$1" in
  install)
    echo "⚙️  Instalando dependências (Composer)..."
    docker-compose run --rm app composer install
    ;;

  build)
    echo "🔨  Buildando containers..."
    docker-compose build
    ;;

  up)
    echo "🚀  Subindo containers em background..."
    docker-compose up -d
    ;;

  down)
    echo "🛑  Derrubando containers..."
    docker-compose down
    ;;

  restart)
    echo "🔄  Reiniciando containers..."
    docker-compose restart
    ;;

  migrate)
    echo "📦  Rodando migrations..."
    docker-compose exec app php spark migrate
    ;;

  seed)
    echo "🌱  Carregando dados de teste..."
    docker-compose exec app php spark db:seed DatabaseSeeder
    ;;

  refresh)
    echo "🔄  Atualizando migrations..."
    docker-compose exec app php spark migrate:refresh
    echo "🌱  Carregando dados de teste..."
    docker-compose exec app php spark db:seed DatabaseSeeder
    ;;

  clear)
    echo "🧹  Limpando todos os caches..."
    echo "🧹  cache de páginas (page cache)"
    echo "🧹  cache de config"
    echo "🧹  cache de views"
    echo "🧹  cache de rotas ( tudo que estiver em writable/cache/ )"
    docker-compose exec app php spark cache:clear
    ;;

  logs)
    echo "📑  Exibindo logs..."
    docker-compose logs --tail 100 -f
    ;;

  tabelas)
    echo "📦  Listando tabelas..."
    docker exec -i banco mysql -u root -proot -e "SHOW TABLES;" sgp
    ;;

  varrer)
    echo "🔍  Verificando se todos os namespaces e paths batem"
    docker-compose exec app php spark namespaces

    echo "🔍  Validando suas configurações em app/Config"
    docker-compose exec app php spark config:check
    
    echo "🔍  Garantindo que suas route-filters existam"
    docker-compose exec app php spark filter:check

    echo "🔍  Listando todas as rotas registradas (útil pra debug)"
    docker-compose exec app php spark routes
    ;;
    
  phpstan)
    echo "🔍  Executando análise estática de erros..."
    vendor/bin/phpstan analyse --level=max app
    ;;
  
  formatar)
    echo "🔍  Formatando/corrigindo estilo de código..."
    vendor/bin/php-cs-fixer fix
    ;;
  
  css)
    echo "🔍  Buildando CSS..."
    npm run build:css
    ;;
  
  js)
    echo "🔍  Buildando JS..."
    npm run build:js
    ;;
  
  help | --help | -h | !*)
    echo ""
    echo "⚙️  Script de gerenciamento do projeto:"
    echo "----------------------------------------"
    echo "  ./setup.sh install    → Instalar dependências (Composer)"
    echo "  ./setup.sh build      → Buildar containers"
    echo "  ./setup.sh up         → Subir containers (em background)"
    echo "  ./setup.sh down       → Derrubar containers"  
    echo "  ./setup.sh restart    → Reiniciar containers"
    echo "  ./setup.sh migrate    → Rodar migrations (spark)"
    echo "  ./setup.sh seed       → Carregar dados de testes"
    echo "  ./setup.sh refresh    → Atualizar migrations e recarregar dados de testes"
    echo "  ./setup.sh logs       → Ver logs dos serviços"
    echo "  ./setup.sh clear      → Limpar todos os caches"
    echo "  ./setup.sh tabelas    → Listar tabelas do banco"
    echo "  ./setup.sh varrer     → “varre” o projeto em busca de problemas"
    echo "  ./setup.sh phpstan    → Executar análise estática de erros"
    echo "  ./setup.sh formatar   → Formatar/corrigir estilo de código"
    echo "  ./setup.sh css        → Buildar CSS"
    echo "  ./setup.sh js         → Buildar JS"
    echo "  ./setup.sh help       → Exibir ajuda"
    echo ""
    ;;
esac