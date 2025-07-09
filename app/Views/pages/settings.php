<div class="space-y-6">

  <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Configurações</h2>

  <!-- General Settings -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
      <i data-lucide="settings" class="h-5 w-5 text-gray-600 dark:text-gray-300"></i>
      Configurações Gerais
    </h3>
    <div class="space-y-4">
      <div>
        <label for="auto-check-interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Intervalo de Verificação Automática (minutos)
        </label>
        <input
          type="number"
          id="auto-check-interval"
          min="1"
          max="60"
          value="5"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 transition-colors"
        >
      </div>

      <div class="flex items-center gap-3">
        <input
          type="checkbox"
          id="notifications"
          checked
          class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 transition-colors"
        >
        <label for="notifications" class="text-sm font-medium text-gray-700 dark:text-gray-300">
          Ativar notificações de status
        </label>
      </div>

      <div class="flex items-center gap-3">
        <input
          type="checkbox"
          id="dark-mode"
          class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 transition-colors"
        >
        <label for="dark-mode" class="text-sm font-medium text-gray-700 dark:text-gray-300">
          Modo escuro
        </label>
      </div>

      <button
        id="save-settings"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
        title="Salvar Configurações"
      >
        Salvar Configurações
      </button>
    </div>
  </div>

  <!-- Database Management -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
      <i data-lucide="database" class="h-5 w-5 text-gray-600 dark:text-gray-300"></i>
      Gerenciamento de Dados
    </h3>
    <div class="space-y-4">
      <p class="text-sm text-gray-600 dark:text-gray-300">
        Gerencie seus dados de projetos e configurações
      </p>
      <div class="flex flex-wrap gap-4">

        <button
          class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition-colors"
          title="Exportar Dados"
        >
          <i data-lucide="download" class="h-4 w-4"></i>
          Exportar Dados
        </button>

        <button
          class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
          title="Importar Dados"
        >
          <i data-lucide="upload" class="h-4 w-4"></i>
          Importar Dados
        </button>

        <button
          class="flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 transition-colors"
          title="Limpar Cache"
        >
          <i data-lucide="refresh-cw" class="h-4 w-4"></i>
          Limpar Cache
        </button>
      </div>
    </div>
  </div>

  <!-- System Information -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
      Informações do Sistema
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <p class="text-sm text-gray-600 dark:text-gray-300">Versão</p>
        <p class="font-semibold dark:text-gray-100">1.0.0</p>
      </div>
      <div>
        <p class="text-sm text-gray-600 dark:text-gray-300">Framework</p>
        <p class="font-semibold dark:text-gray-100">CodeIgniter 4</p>
      </div>
      <div>
        <p class="text-sm text-gray-600 dark:text-gray-300">Banco de Dados</p>
        <p class="font-semibold dark:text-gray-100">MySQL</p>
      </div>
      <div>
        <p class="text-sm text-gray-600 dark:text-gray-300">Ambiente</p>
        <p class="font-semibold dark:text-gray-100">Development</p>
      </div>
    </div>
  </div>

  <!-- Help & Support -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
      Ajuda & Suporte
    </h3>
    <div class="space-y-3">
      <p class="text-sm text-gray-600 dark:text-gray-300">
        Sistema de gerenciamento de projetos de desenvolvimento
      </p>
      <div class="flex flex-wrap gap-4">
        <a href="#"
           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm font-medium transition-colors"
           title="Documentação"
        >
          Documentação
        </a>
        <a href="#"
           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm font-medium transition-colors"
           title="FAQ"
        >
          FAQ
        </a>
        <a href="#"
           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm font-medium transition-colors"
           title="Suporte"
        >
          Suporte
        </a>
      </div>
    </div>
  </div>

</div>
