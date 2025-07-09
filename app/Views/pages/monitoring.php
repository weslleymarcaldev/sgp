<div class="space-y-6">

  <!-- Header -->
  <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Monitoramento</h2>
    <button
      id="check-all-monitoring"
      class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
      title="Verificar Todos"
    >
      <i data-lucide="refresh-cw" class="h-4 w-4"></i>
      <span>Verificar Todos</span>
    </button>
  </div>

  <!-- Stats Overview -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Projetos Monitorados</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="monitored-projects">0</p>
        </div>
        <i data-lucide="globe" class="h-8 w-8 text-blue-600"></i>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Saudáveis</p>
          <p class="text-2xl font-bold text-green-600" id="healthy-monitored">0</p>
        </div>
        <i data-lucide="check-circle-2" class="h-8 w-8 text-green-600"></i>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Com Problemas</p>
          <p class="text-2xl font-bold text-red-600" id="problem-monitored">0</p>
        </div>
        <i data-lucide="alert-circle" class="h-8 w-8 text-red-600"></i>
      </div>
    </div>
  </div>

  <!-- Project Health Cards -->
  <div id="monitoring-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Monitoring cards will be loaded here -->
  </div>

  <div
    id="no-monitored-projects"
    class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center hidden transition-colors"
  >
    <i data-lucide="activity" class="h-12 w-12 text-gray-400 dark:text-gray-500 mx-auto mb-4"></i>
    <p class="text-gray-500 dark:text-gray-300 mb-2">Nenhum projeto com URL encontrado</p>
    <p class="text-sm text-gray-400 dark:text-gray-400">
      Adicione URLs aos seus projetos para monitorar sua disponibilidade
    </p>
  </div>

</div>

<script>
$(document).ready(function() {
    loadMonitoringData();
    $('#check-all-monitoring').click(checkAllHealthMonitoring);
});

function loadMonitoringData() {
    $.get('<?= base_url('api/projects') ?>')
        .done(function(projects) {
            const monitored = projects.filter(p => p.url);
            $('#monitored-projects').text(monitored.length);
            $('#healthy-monitored' ).text(monitored.filter(p => p.health_status==='healthy').length);
            $('#problem-monitored').text(monitored.filter(p => ['warning','error'].includes(p.health_status)).length);

            if (!monitored.length) {
                $('#no-monitored-projects').removeClass('hidden');
                $('#monitoring-grid').empty();
                return;
            }
            $('#no-monitored-projects').addClass('hidden');
            displayMonitoringCards(monitored);
        })
        .fail(() => console.error('Error loading monitoring data'));
}

function displayMonitoringCards(projects) {
    const $grid = $('#monitoring-grid').empty();
    projects.forEach(p => {
        $.get('<?= base_url('api/healthcheck/history/') ?>' + p.id)
         .done(hist => {
            $grid.append(createMonitoringCard(p, hist));
            lucide.createIcons();
         });
    });
}

function createMonitoringCard(proj, history) {
    const healthIcon = getHealthIcon(proj.health_status);
    const healthColor = getHealthColor(proj.health_status);
    const respColor = getResponseTimeColor(proj.response_time);

    let histHtml = '';
    if (history?.length) {
        histHtml = `
          <div>
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Histórico Recente</h4>
            <div class="space-y-2">
              ${history.slice(0,3).map(c => `
                <div class="flex items-center justify-between text-sm">
                  <div class="flex items-center gap-2">
                    ${getHealthIcon(c.status)}
                    <span class="text-gray-600 dark:text-gray-300">${formatTimeAgo(c.checked_at)}</span>
                  </div>
                  <div class="text-right">
                    <span class="${getResponseTimeColor(c.response_time)}">
                      ${c.response_time? c.response_time+'ms' : 'Erro'}
                    </span>
                    ${c.error_message? `<p class="text-xs text-red-600 dark:text-red-400 mt-1">${c.error_message}</p>` : ''}
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
    }

    return `
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">${proj.name}</h3>
            <div class="flex items-center gap-2 mb-2">
              ${healthIcon}
              <span class="px-2 py-1 rounded-full text-xs font-medium border ${healthColor}">
                ${proj.health_status}
              </span>
            </div>
            <a
              href="${proj.url}"
              target="_blank"
              rel="noopener"
              class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 transition-colors"
              title="Verificar URL"
            >
              ${proj.url}
            </a>
          </div>
          <button
            onclick="checkProjectHealth('${proj.id}')"
            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-colors"
            title="Verificar"
          >
            <i data-lucide="refresh-cw" class="h-4 w-4"></i>
          </button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
          <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg transition-colors">
            <p class="text-xs text-gray-600 dark:text-gray-300 mb-1">Tempo de Resposta</p>
            <p class="font-bold ${respColor}">${proj.response_time? proj.response_time+'ms':'N/A'}</p>
          </div>
          <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg transition-colors">
            <p class="text-xs text-gray-600 dark:text-gray-300 mb-1">Última Verificação</p>
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
              ${proj.last_check? formatTimeAgo(proj.last_check):'Nunca'}
            </p>
          </div>
        </div>

        ${histHtml}
      </div>
    `;
}

function checkProjectHealth(id) {
    $.post('<?= base_url('api/healthcheck/') ?>'+id)
     .done(loadMonitoringData)
     .fail(() => console.error('Error checking project health'));
}

function checkAllHealthMonitoring() {
    const btn = $('#check-all-monitoring');
    const ico = btn.find('i').addClass('animate-spin');
    btn.prop('disabled', true).find('span').text('Verificando...');
    $.post('<?= base_url('api/healthcheck/all') ?>')
      .always(() => {
        ico.removeClass('animate-spin');
        btn.prop('disabled', false).find('span').text('Verificar Todos');
        loadMonitoringData();
      });
}
</script>
