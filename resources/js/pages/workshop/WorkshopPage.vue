<script setup>
import AppBaseLayout from '../../layouts/AppBaseLayout.vue';

// Данные об услугах (полностью соответствуют сайту Kickscootershop)
const serviceCategories = [
  {
    name: 'Настройка / Сборка',
    icon: 'fa-solid fa-sliders-up',
    items: [
      { name: 'Настройка б/у самоката', price: '1000₽' },
      { name: 'Устранение люфта комплексное', price: '500₽' },
      { name: 'Настройка б/у грязного самоката', price: '1200₽' },
      { name: 'Сборка трюкового самоката *', price: 'Бесплатно' },
      { name: 'Сборка кастома **', price: 'Бесплатно' },
      { name: 'Сборка кастома (частичный выкуп)', price: '800₽' },
    ],
  },
  {
    name: 'Дека',
    icon: 'fa-solid fa-chalkboard-user',
    items: [
      { name: 'Замена деки', price: '600₽' },
      { name: 'Установка деки', price: '500₽' },
      { name: 'Отпил деки', price: '2000₽' },
      { name: 'Отпил деки с кв. дропаутами*', price: '2500₽' },
      { name: 'Облегчение деки (отверстия)', price: '1000₽' },
      { name: 'Снятие деки', price: '500₽' },
    ],
  },
  {
    name: 'Руль и баренды',
    icon: 'fa-solid fa-hand-peace',
    items: [
      { name: 'Пробить ромашку внутрь', price: '200₽' },
      { name: 'Установка ромашки', price: '300₽' },
      { name: 'Выровнять руль', price: '200₽' },
      { name: 'Обрезать руль в высоту', price: '300₽' },
      { name: 'Снятие руля', price: '300₽' },
      { name: 'Замена руля', price: '300₽' },
      { name: 'Обрезать руль в ширину', price: '400₽' },
      { name: 'Пропил в руле', price: '200₽' },
      { name: 'Установка руля', price: '300₽' },
      { name: 'Замена барендов', price: '200₽' },
      { name: 'Установка барендов', price: '200₽' },
      { name: 'Снятие барендов', price: '200₽' },
      { name: 'Установка экстендеров', price: '300₽' },
    ],
  },
  {
    name: 'Вилка / Рулевая / Зажим',
    icon: 'fa-solid fa-gears',
    items: [
      { name: 'Замена вилки', price: '400₽' },
      { name: 'Обрезать вилку', price: '200₽' },
      { name: 'Установка вилки', price: '400₽' },
      { name: 'Снятие вилки', price: '400₽' },
      { name: 'Замена рулевой', price: '400₽' },
      { name: 'Снятие рулевой', price: '300₽' },
      { name: 'Установка рулевой', price: '300₽' },
      { name: 'Высверлить 1 болт в зажиме', price: '300₽' },
      { name: 'Подкрутить зажим', price: '100₽' },
      { name: 'Установить зажим', price: '300₽' },
      { name: 'Замена зажима', price: '300₽' },
      { name: 'Снять зажим', price: '300₽' },
    ],
  },
  {
    name: 'Колесо / Подшипники',
    icon: 'fa-solid fa-gear',
    items: [
      { name: 'Высверлить сорванный болт в колесе', price: '500₽' },
      { name: 'Замена подшипников', price: '250₽' },
      { name: 'Снятие колеса', price: '200₽' },
      { name: 'Установка подшипников', price: '200₽' },
      { name: 'Замена колеса', price: '200₽' },
      { name: 'Подкрутить колесо', price: '100₽' },
      { name: 'Установка колеса', price: '200₽' },
    ],
  },
  {
    name: 'Грипсы / Шкурка / Пеги',
    icon: 'fa-solid fa-hand-back-fist',
    items: [
      { name: 'Замена грипс', price: '200₽' },
      { name: 'Установка грипс', price: '200₽' },
      { name: 'Снятие грипс', price: '200₽' },
      { name: 'Замена шкурки', price: '450₽' },
      { name: 'Снятие шкурки', price: '300₽' },
      { name: 'Установка шкурки', price: '300₽' },
      { name: 'Установка пеги', price: '200₽' },
      { name: 'Снятие пеги', price: '200₽' },
    ],
  },
  {
    name: 'Тормоз / Прочее',
    icon: 'fa-solid fa-stop',
    items: [
      { name: 'Просверлить отверстие под тормоз', price: '300₽' },
      { name: 'Установка тормоза', price: '300₽' },
      { name: 'Замена тормоза', price: '300₽' },
      { name: 'Снятие тормоза', price: '300₽' },
    ],
  },
];
</script>

<template>
  <AppBaseLayout>
    <div class="workshop-services">
      <!-- Плашка с городом (зелёная) -->
      <div class="city-badge">
        <i class="fa-solid fa-location-dot"></i> Ремонтная мастерская в Иркутске
      </div>

      <h1 class="main-title">⚙️ Ремонт и настройка трюковых самокатов</h1>

      <!-- Категории и услуги -->
      <div v-for="category in serviceCategories" :key="category.name" class="category-block">
        <div class="category-header">
          <i :class="category.icon" class="category-icon"></i>
          <h2 class="category-title">{{ category.name }}</h2>
        </div>
        <div class="services-grid">
          <div v-for="item in category.items" :key="item.name" class="service-item">
            <span class="service-name">{{ item.name }}</span>
            <span class="service-price">{{ item.price }}</span>
          </div>
        </div>
      </div>

      <!-- Блок информации об оплате (зелёные акценты) -->
      <div class="info-payment">
        <div class="payment-box">
          <i class="fa-solid fa-credit-card"></i>
          <div>
            <h3>Оплата и порядок работ</h3>
            <p>Оплата после выполнения работы. Живая очередь — предварительная запись не требуется.<br>
            Фиксированные цены на большинство операций.</p>
          </div>
        </div>
      </div>
    </div>
  </AppBaseLayout>
</template>

<style scoped>
.workshop-services {
  max-width: 1280px;
  margin: 0 auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 28px;
  font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* зелёные акценты */
.city-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #e8f5e9;  /* светло-зелёный фон */
  padding: 6px 16px;
  border-radius: 40px;
  font-size: 13px;
  font-weight: 500;
  color: #2e7d32;  /* тёмно-зелёный текст */
  margin-bottom: 20px;
}

.main-title {
  font-size: 28px;
  font-weight: 700;
  margin: 0 0 32px 0;
  background: linear-gradient(135deg, #1e293b, #2d3a4f);
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
  letter-spacing: -0.3px;
}

.category-block {
  margin-bottom: 40px;
}

.category-header {
  display: flex;
  align-items: center;
  gap: 12px;
  border-left: 5px solid #22c55e;  /* зелёная полоса */
  padding-left: 16px;
  margin-bottom: 18px;
}

.category-icon {
  font-size: 26px;
  width: 36px;
  color: #22c55e;  /* зелёные иконки категорий */
}

.category-title {
  font-size: 22px;
  font-weight: 700;
  margin: 0;
  color: #0f172a;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 8px 20px;
}

.service-item {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  padding: 8px 0;
  border-bottom: 1px dashed #e9eef3;
  font-size: 15px;
}

.service-name {
  color: #1f2a3e;
  font-weight: 500;
}

.service-price {
  font-weight: 700;
  color: #16a34a;  /* зелёный цвет цен */
  white-space: nowrap;
  margin-left: 12px;
}

.info-payment {
  margin-top: 48px;
}

.payment-box {
  display: flex;
  align-items: center;
  gap: 18px;
  background: #f8fafc;
  padding: 20px 24px;
  border-radius: 20px;
  border: 1px solid #eef2f8;
}

.payment-box i {
  font-size: 40px;
  color: #22c55e;  /* зелёная иконка оплаты */
}

.payment-box h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  font-weight: 700;
}

.payment-box p {
  margin: 0;
  font-size: 14px;
  color: #334155;
  line-height: 1.4;
}

@media (max-width: 760px) {
  .workshop-services {
    padding: 16px;
  }
  .main-title {
    font-size: 24px;
  }
  .category-title {
    font-size: 20px;
  }
  .services-grid {
    grid-template-columns: 1fr;
  }
  .payment-box {
    flex-direction: column;
    text-align: center;
  }
}
</style>