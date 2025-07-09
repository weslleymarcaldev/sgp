<?php // main.php ?>
<!DOCTYPE html>
<!-- 1) detecção antes de renderizar -->
<script>
  if (
    localStorage.theme === 'dark'
    || (!('theme' in localStorage)
        && window.matchMedia('(prefers-color-scheme: dark)').matches)
  ) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
</script>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $title ?? 'Gestor de projetos' ?></title>

  <!-- CSS Tailwind + custom -->
  <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">

  <script>
    window.APP = { baseUrl: '<?= rtrim(base_url(), "/") ?>' };
  </script>

  <!-- libs -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.276.0/dist/umd/lucide.js"></script>
  <script defer src="<?= base_url('assets/js/app.js') ?>"></script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors">
  <!-- spinner global -->
  <div id="global-spinner"
       class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden z-50">
    <div class="loading-spinner"></div>
  </div>

  <div class="min-h-screen flex">
    <!-- sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg transition-colors">
      <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">GDP</h1>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Gestor de projetos</p>
      </div>
      <nav class="mt-6 space-y-1">
        <?php $links = [
          ['/',         'home',       'Painel',      'dashboard'],
          ['/projects', 'folder',     'Projetos',    'projects'],
          ['/monitoring','activity',   'Monitoramento','monitoring'],
          ['/settings', 'settings',    'Configurações','settings'],
        ]; ?>
        <?php foreach($links as [$url,$icon,$label,$key]): ?>
          <a href="<?= base_url($url) ?>"
            class="flex items-center px-6 py-3 
                    text-gray-600 hover:bg-blue-50 hover:text-blue-600 
                    dark:text-gray-300 dark:hover:bg-gray-700 
                    rounded transition-colors
                    <?= ($page ?? '') === $key ? 'bg-blue-100 dark:bg-blue-900 text-blue-700' : '' ?>"
            title="<?= $label ?>">
            <i data-lucide="<?= $icon ?>" class="w-5 h-5 mr-3"></i>
            <?= $label ?>
          </a>
        <?php endforeach; ?>

        <!-- toggle tema -->
        <button type="button" id="theme-toggle" title="Alternar tema"
                class="flex items-center px-6 py-3 
                      text-gray-600 hover:bg-blue-50 hover:text-blue-600 
                      dark:text-gray-300 dark:hover:bg-gray-700 
                      rounded transition-colors w-full">
          <i data-lucide="sun"  class="w-5 h-5 dark:hidden"></i>
          <i data-lucide="moon" class="w-5 h-5 hidden dark:inline"></i>
          <span class="ml-2">Tema</span>
        </button>
      </nav>
    </aside>


    <!-- conteúdo principal -->
    <div class="flex-1 flex flex-col bg-white dark:bg-gray-800 transition-colors">
      <header class="p-6 border-b border-gray-200 dark:border-gray-700 transition-colors">
        <h2 id="page-title" class="text-xl font-semibold text-gray-800 dark:text-gray-100">
          <?= [
            'dashboard'=>'Painel',
            'projects'=>'Projetos',
            'monitoring'=>'Monitoramento',
            'settings'=>'Configurações'
          ][$page ?? 'dashboard'] ?>
        </h2>
      </header>
      <main id="main-content" class="flex-1 p-6 overflow-auto transition-colors">
        <?php
          switch ($page ?? 'dashboard') {
            case 'projects':    echo view('pages/projects');    break;
            case 'monitoring':  echo view('pages/monitoring');  break;
            case 'settings':    echo view('pages/settings');    break;
            default:            echo view('pages/dashboard');   break;
          }
        ?>
      </main>
    </div>
  </div>

  <!-- modal -->
  <?= view('partials/project_modal') ?>

  <!-- inicia ícones Lucide e toggle de tema -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();

      // toggle dark/light
      const btn = document.getElementById('theme-toggle');
      btn.addEventListener('click', () => {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.theme = isDark ? 'dark' : 'light';
      });
    });
  </script>
</body>
</html>
