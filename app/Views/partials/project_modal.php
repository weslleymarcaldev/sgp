<div id="project-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-title"
     aria-describedby="modal-description"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
  <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <!-- 1) Cabeçalho -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
      <h2 id="modal-title"
          class="text-xl font-semibold text-gray-800 dark:text-gray-100">
        Novo Projeto
      </h2>
      <button type="button"
              id="close-modal"
              class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
              title="Fechar">
        <i data-lucide="x"
           class="w-5 h-5 text-gray-600 dark:text-gray-100"></i>
      </button>
    </div>

    <!-- 2) Formulário -->
    <form id="project-form" class="p-6 space-y-6">
      <input type="hidden" id="project-id" name="project_id" value="">

      <div>
        <label for="project-name"
               class="block text-sm font-medium text-gray-700 dark:text-gray-100">
          Nome do Projeto *
        </label>
        <input type="text"
               id="project-name"
               name="name"
               required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg
                      bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100
                      dark:focus:ring-blue-600 dark:focus:border-blue-600">
      </div>

      <div>
        <label for="project-status"
               class="block text-sm font-medium text-gray-700 dark:text-gray-100">
          Status
        </label>
        <select id="project-status"
                name="status"
                aria-label="Status do projeto"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg
                       bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100
                       dark:focus:ring-blue-600 dark:focus:border-blue-600">
          <option value="development">Desenvolvimento</option>
          <option value="active">Ativo</option>
          <option value="maintenance">Manutenção</option>
          <option value="inactive">Inativo</option>
        </select>
      </div>

      <div>
        <label for="project-description"
               class="block text-sm font-medium text-gray-700 dark:text-gray-100">
          Descrição
        </label>
        <textarea id="project-description"
                  name="description"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg
                         bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100
                         dark:focus:ring-blue-600 dark:focus:border-blue-600"></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="project-url"
                 class="block text-sm font-medium text-gray-700 dark:text-gray-100">
            URL do Projeto
          </label>
          <input type="url"
                 id="project-url"
                 name="url"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg
                        bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                        dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100
                        dark:focus:ring-blue-600 dark:focus:border-blue-600">
        </div>
        <div>
          <label for="project-repository"
                 class="block text-sm font-medium text-gray-700 dark:text-gray-100">
            Repositório
          </label>
          <input type="url"
                 id="project-repository"
                 name="repository"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg
                        bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                        dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100
                        dark:focus:ring-blue-600 dark:focus:border-blue-600">
        </div>
      </div>

      <div>
        <label for="tech-select"
               class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-2">
          Tecnologias
        </label>
        <select id="tech-select"
                name="technologies[]"
                multiple
                autocomplete="off"
                aria-label="Tecnologias do projeto"
                class="w-full border border-gray-300 rounded-lg px-3 py-2
                       bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100
                       dark:focus:ring-blue-600 dark:focus:border-blue-600">
          <!-- opções preenchidas via JS -->
        </select>
        <div id="technologies-container" class="mt-2 space-y-2"></div>
        <button type="button"
                id="add-technology"
                class="mt-2 inline-flex items-center gap-1 px-3 py-1
                       bg-blue-600 hover:bg-blue-700 text-white rounded-lg
                       dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Adicionar
        </button>
      </div>

      <!-- 3) Botões -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <button type="button"
                id="cancel-form"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg
                       dark:bg-gray-500 dark:hover:bg-gray-600 transition-colors"
                title="Cancelar">
          <i data-lucide="x" class="w-4 h-4"></i> Cancelar
        </button>
        <button type="submit"
                id="submit-form"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg
                       dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
                title="Criar">
          <i data-lucide="plus" class="w-4 h-4"></i> Criar
        </button>
      </div>
    </form>
  </div>
</div>
