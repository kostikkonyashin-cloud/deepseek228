<?php

use App\Http\Controllers\Web\About\AboutCompanyController;
use App\Http\Controllers\Web\Admin\Brand\AdminBrandController;
use App\Http\Controllers\Web\Admin\Category\AdminCategoryController;
use App\Http\Controllers\Web\Admin\Color\AdminColorController;
use App\Http\Controllers\Web\Admin\Material\AdminMaterialController;
use App\Http\Controllers\Web\Admin\Member\AdminCommandMemberController;
use App\Http\Controllers\Web\Admin\Product\AdminProductController;
use App\Http\Controllers\Web\Admin\Size\AdminSizeController;
use App\Http\Controllers\Web\Admin\User\AdminUserController;
use App\Http\Controllers\Web\Admin\Workshop\AdminWorkshopController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\SocialiteController;
use App\Http\Controllers\Web\Brand\BrandController;
use App\Http\Controllers\Web\Cart\CartController;
use App\Http\Controllers\Web\Favorite\FavoriteController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Manager\ManagerController;
use App\Http\Controllers\Web\Order\OrderController;
use App\Http\Controllers\Web\Order\YookassaWebhookController;
use App\Http\Controllers\Web\Product\ProductController;
use App\Http\Controllers\Web\Profile\ProfileController;
use App\Http\Controllers\Web\Search\SearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\Admin\Export\AdminExportController;

// Услуги мастерской
Route::get('/workshop', [AdminWorkshopController::class, 'publicIndex'])->name('workshop.index');

// Главная страница
Route::get('/', HomeController::class)->name('index');

// Аутентификация
Route::controller(LoginController::class)->prefix('/login')->name('login.')->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
});

// Регистрация
Route::controller(RegisterController::class)->prefix('/register')->name('register.')->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
});

// Вход через социальные сети
Route::controller(SocialiteController::class)->prefix('/auth/{provider}')->name('socialite-account.')->group(function () {
    Route::get('/redirect', 'create')->name('create');
    Route::get('/callback', 'store')->name('store');
});

// Страница с товарами
Route::controller(ProductController::class)->prefix('/categories/{category}')->name('product.')->group(function () {
    Route::get('/', 'index')->name('index'); // Страница для показа всех продуктов по категории
    Route::get('/{product}/show', 'show')->name('show'); // Страница для просмотра конкретного продукта
});

// Поиск товаров по названию
Route::get('/search', SearchController::class)->name('search.index');

// Бренды
Route::controller(BrandController::class)->prefix('/brands')->name('brand.')->group(function () {
    Route::get('/', 'index')->name('index'); // Просмотр всех брендов
    Route::get('/{brand}/show', 'show')->name('show'); // Просмотр всех товаров по конкретному бренду
});

// Страница для просмотра дополнительной информации о компании
Route::get('/about-company', AboutCompanyController::class)->name('about-company.index');

// Защищенные маршруты
Route::middleware(['is_auth'])->group(function () {

    Route::post('/webhook/yookassa', [YookassaWebhookController::class, 'handle'])
        ->name('webhook.yookassa');

    // Избранное пользователя
    Route::middleware(['auth'])->controller(FavoriteController::class)->prefix('/favorites')->name('favorites.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::delete('/{productId}', 'destroy')->name('destroy');
        Route::get('/check/{productId}', 'check')->name('check');
        Route::get('/ids', 'getFavoritesIds')->name('ids');
    });

    // Профиль пользователя
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index'); // Главная страница профиля пользователя
            Route::delete('/destroy', 'destroy')->name('destroy'); // Выход пользователя из учетной записи

            // Редактирование профиля
            Route::get('/edit', 'edit')->name('edit');
            Route::put('/update', 'update')->name('update');
            Route::put('/update-password', 'updatePassword')->name('update-password');
        });

        // Панель управления менеджера по продажам
        Route::middleware(['is_manager'])->controller(ManagerController::class)->prefix('management-orders')->name('management.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('{order}/update', 'update')->name('update');
        });
    });

    // Корзина пользователя
    Route::prefix('/cart')->group(function () {
        // Управление продуктами для пользователя
        Route::controller(ProductController::class)->name('product.')->group(function () {
            Route::post('/store', 'store')->name('store'); // Сохранение товара в корзину
            Route::patch('/{product}/update', 'update')->name('update'); // Обновление количества товара в корзине пользователя
            Route::delete('/{product}/destroy', 'destroy')->name('destroy'); // Удаление товара из корзины
        });

        // Заказы пользователя
        Route::controller(OrderController::class)->prefix('/orders')->name('order.')->group(function () {
            Route::post('/store', 'store')->name('store'); // Оформление заказа
            Route::delete('/{order}/destroy', 'destroy')->name('destroy'); // Отмена заказа для пользователя
        });

        // Управление корзиной пользователя
        Route::controller(CartController::class)->name('cart.')->group(function () {
            Route::get('/', 'index')->name('index'); // Главная страница корзины конкретного пользователя
            Route::delete('/destroy', 'destroy')->name('destroy'); // Удаление всех товаров из корзины
        });
    });

    // Административная панель
    Route::middleware(['is_admin'])->prefix('/admin')->name('admin.')->group(function () {


        // Управление участниками команды
        Route::resource('team', AdminCommandMemberController::class);

        // Управление услугами мастерской
        Route::resource('workshop', AdminWorkshopController::class);

        // Управление пользователями
        Route::controller(AdminUserController::class)->prefix('/users')->name('user.')->group(function () {
            Route::get('/', 'index')->name('index'); // Просмотр всех пользователей
            Route::get('/create', 'create')->name('create'); // Страница создания нового пользователя
            Route::get('/{user}/edit', 'edit')->name('edit'); // Редактирование данных о пользователе
            Route::post('/store', 'store')->name('store'); // Маршрут для сохранения нового пользователя
            Route::put('/{user}/update', 'update')->name('update'); // Обновление данных о пользователе
            Route::delete('/{user}/destroy', 'destroy')->name('destroy'); // Удаление пользователя
            Route::post('/{user}/toggle-block', 'toggleBlock')->name('toggle-block'); // Обновление статуса пользователя (активен/заблокирован)
        });

        // Управление товарами
        Route::controller(AdminProductController::class)->prefix('/products')->name('product.')->group(function () {
            Route::get('/', 'index')->name('index'); // Просмотр всех товаров
            Route::get('/create', 'create')->name('create'); // Страница создания нового товара
            Route::post('/store', 'store')->name('store'); // Маршрут для сохранения нового товара
            Route::get('/{product}/edit', 'edit')->name('edit'); // Страница для редактирования данных о товаре
            Route::put('/{product}/update', 'update')->name('update'); // Обновление данных о товаре
            Route::post('/{product}/toggle-availability', 'toggleAvailability')->name('toggle-availability');
        });

        // Управление материалами
        Route::controller(AdminMaterialController::class)->prefix('/materials')->name('material.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{material}/edit', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::put('/{material}/update', 'update')->name('update');
            Route::delete('/{material}/destroy', 'destroy')->name('destroy');
            Route::post('/{material}/toggle-active', 'toggleActive')->name('toggle-active');
        });

        // Управление цветами
        Route::controller(AdminColorController::class)->prefix('/colors')->name('color.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{color}/edit', 'edit')->name('edit');
            Route::put('/{color}/update', 'update')->name('update');
            Route::delete('/{color}/destroy', 'destroy')->name('destroy');
        });

        // Управление размерами
        Route::controller(AdminSizeController::class)->prefix('/sizes')->name('size.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{size}/edit', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::put('/{size}/update', 'update')->name('update');
            Route::post('/{size}/toggle-active', 'toggleActive')->name('toggle-active');
            Route::delete('/{size}/destroy', 'destroy')->name('destroy');
        });

        // Управление производителями
        Route::controller(AdminBrandController::class)->prefix('/brands')->name('brand.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{brand}/edit', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::put('/{brand}/update', 'update')->name('update');
            Route::post('/{brand}/toggle-active', 'toggleActive')->name('toggle-active');
            Route::delete('/{brand}/destroy', 'destroy')->name('destroy');
        });

        // Управление размерами
        Route::controller(AdminSizeController::class)->prefix('/sizes')->name('size.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{size}/edit', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::put('/{size}/update', 'update')->name('update');
            Route::post('/{size}/toggle-active', 'toggleActive')->name('toggle-active');
        });

        Route::get('/export-orders',    [App\Http\Controllers\Web\Admin\Export\AdminExportController::class,
                                        'exportOrders'])->name('export.orders');

        Route::get('/export-inventory', [App\Http\Controllers\Web\Admin\Export\AdminExportController::class,
                                        'exportProducts'])->name('export.inventory');

        // Управление категориями и подкатегориями
        Route::controller(AdminCategoryController::class)->prefix('/categories')->name('category.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{category}/edit', 'edit')->name('edit');
            Route::patch('/{category}/update', 'update')->name('update');
            Route::delete('/{category}/destroy', 'destroy')->name('destroy');
            Route::post('/{category}/toggle-active', 'toggleActive')->name('toggle-active');

            // Подкатегории
            Route::prefix('category-types')->name('types.')->group(function () {
                Route::get('/', 'typesIndex')->name('index');
                Route::get('/create', 'typesCreate')->name('create');
                Route::post('/', 'typesStore')->name('store');
                Route::get('/{categoryType}/edit', 'typesEdit')->name('edit');
                Route::put('/{categoryType}', 'typesUpdate')->name('update');
                Route::delete('/{categoryType}', 'typesDestroy')->name('destroy');
                Route::post('/{categoryType}/toggle-active', 'typesToggleActive')->name('toggle-active');
            });
        });
    });
});

// Запасной маршрут на случай важных переговоров
Route::fallback(function (Exception $e) {
    return Inertia::render('404');
});
