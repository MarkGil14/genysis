<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::group(['prefix' => 'app'], function () {

    Route::get('/', [
        'uses' => 'Users\PagesController@uploadPage',
        'as' => 'app.upload'
    ]);


    Route::get('/upload', [
        'uses' => 'Users\PagesController@uploadPage',
        'as' => 'app.upload'
    ]);


    Route::get('/orders', [
        'uses' => 'Users\PagesController@orderPage',
        'as' => 'app.orders'
    ]);



    Route::get('/returns', [
        'uses' => 'Users\PagesController@returnPage',
        'as' => 'app.returns'
    ]);


    Route::get('/order/{keyid}', [
        'uses' => 'Users\PagesController@orderDetail',
        'as' => 'app.order-detail'
    ]);


    Route::get('/test', [
        'uses' => 'Users\PagesController@test',
        'as' => 'app.index'
    ]);



    Route::post('/importData', [
        'uses' => 'Users\DataController@importData',
        'as' => 'user.import-data'
    ]);


    Route::get('/order/edit/{key_id}', [
        'uses' => 'Users\PagesController@editOrder',
        'as' => 'app.order-edit'
    ]);



    Route::post('/order/update', [
        'uses' => 'Users\OrdersController@updateOrder',
        'as' => 'user.update-order'
    ]);

    Route::post('/return/update', [
        'uses' => 'Users\ReturnsController@updateReturn',
        'as' => 'user.update-return'
    ]);



    Route::post('/order-item/get-data/', [
        'uses' => 'Users\OrdersController@getItemDataByGuid',
        'as' => 'user.get.order-item'
    ]);

    Route::post('/return-item/get-data/', [
        'uses' => 'Users\ReturnsController@getItemDataByGuid',
        'as' => 'user.get.return-item'
    ]);



    Route::post('/order-item/update/', [
        'uses' => 'Users\OrdersController@updateItem',
        'as' => 'user.item-update-detail'
    ]);

    Route::post('/return-item/update/', [
        'uses' => 'Users\ReturnsController@updateItem',
        'as' => 'user.return-item-update-detail'
    ]);



    Route::get('/return/{keyid}', [
        'uses' => 'Users\PagesController@returnDetail',
        'as' => 'app.return-detail'
    ]);


    Route::get('/return/edit/{key_id}', [
        'uses' => 'Users\PagesController@editReturn',
        'as' => 'app.return-edit'
    ]);


    Route::get('/order-report', [
        'uses' => 'Users\PagesController@reportPage',
        'as' => 'app.order-report'
    ]);


    Route::get('/return-report', [
        'uses' => 'Users\PagesController@returnReportPage',
        'as' => 'app.return-report'
    ]);


    Route::post('/order-report', [
        'uses' => 'Users\OrdersController@getFilterOrderReport',
        'as' => 'app.filter-order-report'
    ]);

    Route::post('/return-report', [
        'uses' => 'Users\ReturnsController@getFilterReturnReport',
        'as' => 'app.filter-return-report'
    ]);


    Route::get('/orders-filter', [
        'uses' => 'Users\OrdersController@filterOrders',
        'as' => 'app.filter-orders'
    ]);



    Route::get('/dashboard/rates', [
        'uses' => 'Users\PagesController@dashboard',
        'as' => 'app.filter.data-rates'
    ]);


    Route::get('/returns-filter', [
        'uses' => 'Users\ReturnsController@filterReturns',
        'as' => 'app.filter-returns'
    ]);


    Route::post('/store/order-item', [
        'uses' => 'Users\OrdersController@addOrderItem',
        'as' => 'app.add-order-item'
    ]);


    Route::get('/format/order-header', [
        'uses' => 'Users\HeadersController@exportOrderHeader',
        'as' => 'format.siforder'
    ]);

    Route::get('/format/discount-header', [
        'uses' => 'Users\HeadersController@exportDiscountHeader',
        'as' => 'format.sifdiscount'
    ]);


    Route::get('/format/return-header', [
        'uses' => 'Users\HeadersController@exportReturnHeader',
        'as' => 'format.sifreturn'
    ]);


    Route::get('/format/item-header', [
        'uses' => 'Users\HeadersController@exportItemHeader',
        'as' => 'format.sifitem'
    ]);



    Route::get('/format/customer-header', [
        'uses' => 'Users\HeadersController@exportCustomerHeader',
        'as' => 'format.sifcustomer'
    ]);



    Route::get('/format/dsp-header', [
        'uses' => 'Users\HeadersController@exportDspHeader',
        'as' => 'format.sifdsp'
    ]);



    Route::get('/field-settings/{field}', [
        'uses' => 'Users\PagesController@fieldSettings',
        'as' => 'app.field-settings'
    ]);



    Route::get('/company-settings', [
        'uses' => 'Users\PagesController@companySettingsPage',
        'as' => 'app.company-settings'
    ]);

    Route::post('/company-settings/update', [
        'uses' => 'Users\CompaniesController@updateCompanySettings',
        'as' => 'app.update.company-settings'
    ]);

    Route::post('/company-settings/logo', [
        'uses' => 'Users\CompaniesController@uploadLogo',
        'as' => 'app.update.company-logo'
    ]);

    Route::post('/field-validation-settings/update', [
        'uses' => 'Users\DataController@updateFieldValidations',
        'as' => 'app.update.field-validations'
    ]);

    Route::get('/dashboard', [
        'uses' => 'Users\PagesController@dashboard',
        'as' => 'app.dashboard'
    ]);

    Route::post('/deleteOrder', [
        'uses' => 'Users\OrdersController@deleteOrder',
        'as' => 'app.delete-order'
    ]);

    Route::post('/deleteReturn', [
        'uses' => 'Users\ReturnsController@deleteReturn',
        'as' => 'app.delete-return'
    ]);

    Route::post('/add/orderitem/{id}', [
        'uses' => 'Users\OrdersController@addOrderItem',
        'as' => 'app.add.order-item'
    ]);

    Route::post('/add/returnitem/{id}', [
        'uses' => 'Users\ReturnsController@addReturnItem',
        'as' => 'app.add.return-item'
    ]);


    Route::get('/search', [
        'uses' => 'Users\PagesController@searchPage',
        'as' => 'app.search'
    ]);

    Route::get('/about-system', [
        'uses' => 'Users\PagesController@aboutSystem',
        'as' => 'app.about-system'
    ]);


    Route::get('/order-change-status/{keyid}/{status}', [
        'uses' => 'Users\OrdersController@changeStatus',
        'as' => 'app.order-change-status'
    ]);


    Route::get('/return-change-status/{keyid}/{status}', [
        'uses' => 'Users\ReturnsController@changeStatus',
        'as' => 'app.return-change-status'
    ]);



    Route::get('/order-item-isdelete/{orderitemid}', [
        'uses' => 'Users\OrdersController@orderItemDelete',
        'as' => 'app.order-item-isdeleted'
    ]);


    Route::get('/return-item-isdelete/{returnitemid}', [
        'uses' => 'Users\ReturnsController@returnItemDelete',
        'as' => 'app.return-item-isdeleted'
    ]);



    Route::get('/order-item-cancel-delete/{orderitemid}', [
        'uses' => 'Users\OrdersController@orderItemCancelDelete',
        'as' => 'app.order-item-cancel-deleted'
    ]);



    Route::get('/return-item-cancel-delete/{returnitemid}', [
        'uses' => 'Users\ReturnsController@returnItemCancelDelete',
        'as' => 'app.return-item-cancel-deleted'
    ]);


    Route::get('/order-isdelete/{guid}', [
        'uses' => 'Users\OrdersController@orderIsDelete',
        'as' => 'app.order-is-delete'
    ]);

    Route::get('/return-isdelete/{guid}', [
        'uses' => 'Users\ReturnsController@returnIsDelete',
        'as' => 'app.return-is-delete'
    ]);



    Route::get('/order-cancel-delete/{guid}', [
        'uses' => 'Users\OrdersController@orderCancelDelete',
        'as' => 'app.order-cancel-delete'
    ]);


    Route::get('/return-cancel-delete/{guid}', [
        'uses' => 'Users\ReturnsController@returnCancelDelete',
        'as' => 'app.return-cancel-delete'
    ]);

    Route::get('/order-upload-history', [
        'uses' => 'Users\PagesController@orderUploadHistory',
        'as' => 'app.order-upload-history'
    ]);

    Route::get('/return-upload-history', [
        'uses' => 'Users\PagesController@returnUploadHistory',
        'as' => 'app.return-upload-history'
    ]);

    Route::get('/summary-report', [
        'uses' => 'Users\PagesController@summaryReport',
        'as' => 'app.summary-report'
    ]);


    Route::post('/process-summary-report', [
        'uses' => 'Users\PagesController@processSummaryReport',
        'as' => 'app.process-summary-report'
    ]);
});


Route::get('/vue_test', [
    'uses' => 'Users\PagesController@vue_test',
    'as' => 'app.vue_test'
]);
