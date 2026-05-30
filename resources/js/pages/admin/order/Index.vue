<template>
  <div class="p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">Экспорт отчетов</h2>
    
    <div class="flex flex-col space-y-4">
      <!-- Блок экспорта продаж -->
      <div class="border p-4 rounded bg-gray-50">
        <h3 class="font-semibold mb-2">Продажи за период</h3>
        <div class="flex items-end gap-3">
          <div>
            <label class="block text-sm">От</label>
            <input type="date" v-model="filter.from" class="border rounded p-1">
          </div>
          <div>
            <label class="block text-sm">До</label>
            <input type="date" v-model="filter.to" class="border rounded p-1">
          </div>
          <button @click="downloadSalesReport" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Скачать отчет (CSV/Excel)
          </button>
        </div>
      </div>

      <!-- Блок наличия -->
      <div class="border p-4 rounded bg-gray-50">
        <h3 class="font-semibold mb-2">Складской отчет</h3>
        <button @click="downloadInventoryReport" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Выгрузить остатки товаров
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const filter = ref({
    from: '',
    to: ''
});

const downloadSalesReport = () => {
    if (!filter.value.from || !filter.value.to) {
        alert('Выберите даты');
        return;
    }
    // Переход по прямой ссылке инициирует скачивание
    window.location.href = `/admin/orders/export-sales?from={filter.value.from}&to=${filter.value.to}`;
};

const downloadInventoryReport = () => {
    window.location.href = '/admin/orders/export-inventory';
};
</script>
