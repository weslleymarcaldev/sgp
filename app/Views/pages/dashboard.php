<div class="space-y-6">

  <!-- Stats Cards -->
  <div id="stats-cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total de Projetos -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total de Projetos</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="total-projects">0</p>
        </div>
        <div class="h-12 w-12 bg-blue-100 dark:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
          <i data-lucide="folder" class="h-6 w-6 text-blue-600"></i>
        </div>
      </div>
    </div>

    <!-- Projetos Ativos -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Projetos Ativos</p>
          <p class="text-2xl font-bold text-green-600" id="active-projects">0</p>
        </div>
        <div class="h-12 w-12 bg-green-100 dark:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
          <i data-lucide="trending-up" class="h-6 w-6 text-green-600"></i>
        </div>
      </div>
    </div>

    <!-- Saudáveis -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Saudáveis</p>
          <p class="text-2xl font-bold text-green-600" id="healthy-projects">0</p>
        </div>
        <div class="h-12 w-12 bg-green-100 dark:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
          <i data-lucide="check-circle-2" class="h-6 w-6 text-green-600"></i>
        </div>
      </div>
    </div>

    <!-- Com Problemas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Com Problemas</p>
          <p class="text-2xl font-bold text-red-600" id="problem-projects">0</p>
        </div>
        <div class="h-12 w-12 bg-red-100 dark:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
          <i data-lucide="alert-circle" class="h-6 w-6 text-red-600"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Health Check Button -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Verificação de Saúde</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">Verifique o status de todos os projetos</p>
      </div>
      <button
        id="check-all-health"
        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        title="Verificar todos os projetos"
      >
        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
        <span>Verificar Todos</span>
      </button>
    </div>
  </div>

  <!-- Recent Projects -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Projetos Recentes</h3>
      <i data-lucide="clock" class="h-5 w-5 text-gray-400 dark:text-gray-500"></i>
    </div>

    <div id="recent-projects" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Projects serão carregados aqui -->
    </div>

    <div id="no-projects" class="text-center py-12 hidden">
      <i data-lucide="folder" class="h-12 w-12 text-gray-400 dark:text-gray-500 mx-auto mb-4"></i>
      <p class="text-gray-500 dark:text-gray-300">Nenhum projeto encontrado</p>
    </div>
  </div>

</div>

<script>
$(document).ready(function() {
    loadDashboardData();
    $('#check-all-health').click(checkAllHealth);
});

function loadDashboardData() {
    // Stats
    $.get('<?= base_url('api/projects/stats') ?>')
        .done(stats => {
            $('#total-projects').text(stats.total_projects || 0);
            $('#active-projects').text(stats.active_projects || 0);
            $('#healthy-projects').text(stats.healthy_projects || 0);
            $('#problem-projects').text((stats.warning_projects||0)+(stats.error_projects||0));
        }).fail(() => console.error('Error loading stats'));

    // Recent projects
    $.get('<?= base_url('api/projects') ?>')
        .done(projects => {
            const rec = projects.slice(0,6);
            const $c = $('#recent-projects');
            if (!rec.length) {
                $('#no-projects').removeClass('hidden');
                return;
            }
            $('#no-projects').addClass('hidden');
            $c.empty();
            rec.forEach(p => $c.append(createProjectCard(p)));
            lucide.createIcons();
        }).fail(() => console.error('Error loading projects'));
}

function checkAllHealth() {
    const btn = $('#check-all-health');
    const ico = btn.find('i').addClass('animate-spin');
    btn.prop('disabled', true).find('span').text('Verificando...');
    $.post('<?= base_url('api/healthcheck/all') ?>')
      .always(() => {
        ico.removeClass('animate-spin');
        btn.prop('disabled', false).find('span').text('Verificar Todos');
        loadDashboardData();
      });
}
</script>
