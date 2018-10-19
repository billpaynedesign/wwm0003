<?php
define("AUTHORIZENET_API_LOGIN_ID", env("AUTHORIZENET_API_LOGIN_ID"));
define("AUTHORIZENET_TRANSACTION_KEY", env("AUTHORIZENET_TRANSACTION_KEY"));
define("AUTHORIZENET_SANDBOX", env("AUTHORIZENET_SANDBOX"));

Route::get('/', ['uses'=>'HomeController@index','as'=>'home']);
Route::get('/home', function(){ return redirect()->route('home'); });
Route::get('/about-us',['uses'=>'HomeController@aboutus','as'=>'about-us']);
Route::get('/contact-us',['uses'=>'HomeController@contactus','as'=>'contact-us']);
Route::post('/contact-us',['uses'=>'HomeController@contact','as'=>'contact-us-submit']);
// Authentication routes...
Route::get('/register-or-login',['uses'=>'HomeController@registerOrLogin','as'=>'register-or-login']);
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*
 * ------------
 * Api
 * ------------
 */
Route::get('/api/search', ['uses'=>'ApiSearchController@index']);
Route::get('/api/product/add/search', ['uses'=>'ApiSearchController@addProduct']);
Route::get('/api/cart/add/search', ['uses'=>'ApiSearchController@addUom']);
Route::get('/api/get/cart/count',['uses'=>'ApiSearchController@getCartCount','as'=>'api-cart-get-count']);

/*
 * ------------
 * Admin Api
 * ------------
 */
Route::group(['middleware'=>'admin'],function(){
	Route::get('/api/get/users',['uses'=>'ApiSearchController@getUsers','as'=>'api-users-get']);
	Route::get('/api/get/uom/product/option/html',['uses'=>'ApiSearchController@getUomProductOptionsHtml','as'=>'api-get-uom-product-options-html']);
	Route::get('/api/get/product/uom/data',['uses'=>'ApiSearchController@getProductUomData','as'=>'api-product-uom-get-data']);
});

/*
 * ------------
 * Admin Dashboard
 * ------------
 */
Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function() {
	Route::group(['middleware'=>'admin'],function(){
		Route::pattern('id', '[0-9]+');

	    // Admin Dashboard
	    Route::get('/', ['uses'=>'DashboardController@index','as'=>'admin-dashboard']);
	    Route::get('/category', ['uses'=>'DashboardController@category_index','as'=>'admin-categories']);
	    Route::get('/product', ['uses'=>'DashboardController@product_index','as'=>'admin-products']);
	    Route::get('/option',['uses'=>'DashboardController@option_index','as'=>'admin-options']);
	    Route::get('/order', ['uses'=>'DashboardController@order_index','as'=>'admin-orders']);
	    Route::get('/backorder', ['uses'=>'DashboardController@backorder_index','as'=>'admin-backorders']);
	    Route::get('/user', ['uses'=>'DashboardController@user_index','as'=>'admin-users']);
	    Route::get('/special', ['uses'=>'DashboardController@special_index','as'=>'admin-specials']);
	    Route::get('/accounts-receivable', ['uses'=>'DashboardController@accounts_receivable','as'=>'admin-accounts-receivable']);
	    Route::get('/accounts-payable', ['uses'=>'DashboardController@accounts_payable','as'=>'admin-accounts-payable']);
	    Route::get('/vendors', ['uses'=>'DashboardController@vendors','as'=>'admin-vendors']);
	});
});

/*
 * ------------
 * Products
 * ------------
 */
Route::get('/sale',['uses'=>'ProductController@sale','as'=>'sale-products']);
Route::get('/products',['uses'=>'ProductController@index','as'=>'product-all']);
Route::group(['prefix'=>'product'],function(){
	Route::get('/latest',['uses'=>'ProductController@latest','as'=>'product-latest']);
	Route::get('/{slug}',['uses'=>'ProductController@show','as'=>'product-show']);
	Route::post('/review/add',['uses'=>'ProductController@addReview','as'=>'product-review-add']);
	//login routes
	Route::group(['middleware'=>'auth'],function(){

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
	    	Route::post('/new', ['uses'=>'ProductController@create','as' => 'product-new']);
	    	Route::post('/delete', ['uses'=>'ProductController@postDelete','as' => 'product-delete']);
	    	Route::get('/delete/{id}', ['uses'=>'ProductController@getDelete','as' => 'product-delete']);
	    	Route::get('/import', ['uses'=>'ProductController@import','as' => 'product-import']);
	    	Route::post('/import', ['uses'=>'ProductController@import_upload','as' => 'product-import-upload']);
	    	Route::post('/import/preview', ['uses'=>'ProductController@import_preview','as' => 'product-import-preview']);
			Route::get('/toggle/taxable', ['uses'=>'ProductController@toggle_taxable','as'=>'product-taxable']);
			Route::get('/toggle/active', ['uses'=>'ProductController@toggleActive','as'=>'product-toggle-active']);
			Route::get('/toggle/featured', ['uses'=>'ProductController@toggleFeatured','as'=>'product-toggle-featured']);
			Route::get('/edit/{id}',['uses'=>'ProductController@edit','as'=>'product-edit']);
			Route::post('/edit',['uses'=>'ProductController@update','as'=>'product-update']);
			Route::post('/info/modal',['uses'=>'ProductController@infoModal','as'=>'product-info-modal']);
		});
	});
});

/*
 * ------------
 * Category
 * ------------
 */
Route::group(['prefix'=>'category'],function(){
	Route::get('/',['uses'=>'CategoryController@index','as'=>'category-index']);
	Route::get('/{slug}',['uses'=>'CategoryController@show','as'=>'category-show']);
	//login routes
	Route::group(['middleware'=>'auth'],function(){

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
				Route::post('/create',['uses'=>'CategoryController@create','as'=>'category-create']);
				Route::post('/delete',['uses'=>'CategoryController@delete','as'=>'category-delete']);
				Route::post('/edit',['uses'=>'CategoryController@edit','as'=>'category-edit']);
				Route::get('/toggle/featured',['uses'=>'CategoryController@toggleFeatured','as'=>'category-toggle-featured']);
				Route::get('/toggle/active',['uses'=>'CategoryController@toggleActive','as'=>'category-toggle-active']);
		});
	});
});

/*
 * ------------
 * Cart
 * ------------
 */
Route::group(['prefix'=>'cart'],function(){
	Route::get('/',['uses'=>'CartController@show','as'=>'cart']);
	Route::post('/add',['uses'=>'CartController@add','as'=>'add-to-cart']);
	Route::get('/checkout/shipping',['uses'=>'CartController@shipping','as'=>'cart-shipping']);
	Route::post('/checkout/payment',['uses'=>'CartController@payment','as'=>'cart-payment']);
	Route::post('/checkout',['uses'=>'CartController@checkout','as'=>'cart-checkout']);
	Route::post('/update',['uses'=>'CartController@update','as'=>'cart-update']);
	Route::get('/remove/{rowid}',['uses'=>'CartController@remove','as'=>'cart-remove']);
	Route::get('/select/shipping', ['uses'=>'CartController@select_shipping','as'=>'cart-select-shipping']);
	Route::post('/set/shipping', ['uses'=>'CartController@set_shipping','as'=>'cart-set-shipping']);
	Route::get('/get/total',['uses'=>'CartController@get_cart_total','as'=>'cart-get-total']);

	Route::group(['middleware'=>'admin'],function(){
		Route::get('/checkout/shipping/{id}/admin',['uses'=>'CartController@admin_shipping','as'=>'admin-cart-shipping']);
		Route::post('/checkout/payment/{id}/admin',['uses'=>'CartController@admin_payment','as'=>'admin-cart-payment']);
		Route::post('/checkout/{id}/admin',['uses'=>'CartController@admin_checkout','as'=>'admin-cart-checkout']);
	});
});

/*
 * ------------
 * Order
 * ------------
 */
Route::group(['prefix'=>'order'],function(){

	//login routes
	Route::group(['middleware'=>'auth'],function(){
		Route::get('/history',['uses'=>'OrderController@history','as'=>'order-history']);
		Route::post('/modal',['uses'=>'OrderController@orderModal','as'=>'order-modal']);
		Route::get('/invoice/{token}',['uses'=>'OrderController@invoice','as'=>'order-invoice']);
		//admin routes
		Route::group(['middleware'=>'admin'],function(){
			Route::get('/edit/{id}',['uses'=>'OrderController@edit','as'=>'order-edit']);
			Route::post('/product/add/{id}', ['uses'=>'OrderController@productAdd', 'as'=>'order-product-add']);
			Route::post('/update',['uses'=>'OrderController@update','as'=>'order-update']);
			Route::get('/delete/{id}',['uses'=>'OrderController@delete','as'=>'order-delete']);
			Route::post('/status',['uses'=>'OrderController@status','as'=>'order-status']);
			Route::post('/status/update',['uses'=>'OrderController@statusUpdate','as'=>'order-status-update']);
			Route::get('/edit/line/{id}',['uses'=>'OrderController@editLine','as'=>'order-edit-line']);
			Route::post('/edit/line/{id}',['uses'=>'OrderController@editLineUpdate','as'=>'order-edit-line-update']);
			Route::get('/toggle/backordered/{id}',['uses'=>'OrderController@toggleBackordered','as'=>'order-toggle-backordered']);
			Route::get('/print/backordered',['uses'=>'OrderController@backordered','as'=>'order-print-backordered']);

    		Route::get('/{id}/create/invoice/{connection}',['uses'=>'OrderController@createQBInvoice','as'=>'order-create-qb-invoice']);
		});

		Route::get('/{token}',['uses'=>'OrderController@show','as'=>'order-show']);
	});
});

/*
 * ------------
 * User profile
 * User Crud
 * User info
 * User Products
 * User Cart
 * User Favorites
 * ------------
 */
Route::group(['prefix'=>'user'],function(){

	//login routes
	Route::group(['middleware'=>'auth'],function(){
		Route::get('/profile',['uses'=>'UserController@profile','as'=>'user-profile']);
		Route::post('/profile/update',['uses'=>'UserController@profile_update','as'=>'user-profile-update']);

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
			Route::get('/delete/{id}',['uses'=>'UserController@delete','as'=>'user-delete']);
			Route::get('/edit/{id}',['uses'=>'UserController@edit','as'=>'user-edit']);
			Route::post('/update',['uses'=>'UserController@update','as'=>'user-update']);
			Route::get('/info/{id}',['uses'=>'UserController@info','as'=>'user-info']);
			Route::get('/product/{id}',['uses'=>'UserController@product', 'as'=>'user-product']);
			Route::post('/product/{id}',['uses'=>'UserController@product_submit', 'as'=>'user-product-submit']);
			Route::post('/product/add/{id}',['uses'=>'UserController@product_add','as'=>'user-product-add']);
			Route::post('/favorites/update/{id}',['uses'=>'UserController@favorites_update','as'=>'user-favorites-update']);
    		Route::post('/new/cart',['uses'=>'UserController@new_cart','as'=>'user-new-cart']);
    		Route::post('/add/product/cart',['uses'=>'UserController@add_product_cart','as'=>'user-cart-add']);
    		Route::post('/update/product/cart',['uses'=>'UserController@update_product_cart','as'=>'user-cart-update']);
    		Route::post('/remove/product/cart',['uses'=>'UserController@remove_product_cart','as'=>'user-cart-remove']);
		});
	});
});

/*
 * ------------
 * Shipto
 * UnitOfMeasure
 * Product Group
 * Option Group
 * ------------
 */
Route::group(['middleware'=>'auth'],function(){
	Route::resource('shipto','ShipToController');
	Route::group(['middleware'=>'admin'],function(){
		Route::resource('unit_of_measure','UnitOfMeasureController');
		Route::resource('option','OptionGroupController');
		Route::get('group/edit/{id}',['uses'=>'OptionGroupController@group_edit','as'=>'option-group-edit']);
		Route::post('group/edit/{id}',['uses'=>'OptionGroupController@group_update','as'=>'option-group-update']);
		Route::get('group/product/select/group',['uses'=>'OptionGroupController@group_product_select_group', 'as'=>'group-product-select-group']);
		Route::post('group/product/select/products',['uses'=>'OptionGroupController@group_product_select_products', 'as'=>'group-product-select-products']);
		Route::post('group/product/option/associate',['uses'=>'OptionGroupController@group_product_option_associate', 'as'=>'group-product-option-associate']);
		Route::post('group/product/option/save',['uses'=>'OptionGroupController@group_product_option_save', 'as'=>'group-product-option-save']);
		Route::post('group/product/option/{id}/add',['uses'=>'OptionGroupController@group_product_option_add','as'=>'group-product-option-add']);
		Route::post('group/product/product/{id}/add',['uses'=>'OptionGroupController@group_product_product_add','as'=>'group-product-product-add']);
		Route::post('group/product/{id}/delete',['uses'=>'OptionGroupController@group_product_delete','as'=>'group-product-option-delete']);
	});
});

/*
 * ------------
 * Specials
 * ------------
 */
Route::group(['prefix'=>'specials'],function(){

	//login routes
	Route::group(['middleware'=>'auth'],function(){

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
	    	Route::post('/new', ['uses'=>'SpecialsController@create','as' => 'specials-new']);
	    	Route::post('/delete', ['uses'=>'SpecialsController@postDelete','as' => 'specials-delete']);
	    	Route::get('/delete/{id}', ['uses'=>'SpecialsController@getDelete','as' => 'specials-delete']);
			Route::post('/edit/{id}',['uses'=>'SpecialsController@update','as'=>'specials-update']);
		});
	});
});

/*
 * ------------
 * Vendors
 * ------------
 */
Route::group(['prefix'=>'vendor'],function(){
	Route::get('/',['uses'=>'VendorController@index','as'=>'vendor-index']);
	//login routes
	Route::group(['middleware'=>'auth'],function(){

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
			Route::get('/{id}',['uses'=>'VendorController@show','as'=>'vendor-show']);
			Route::post('/store',['uses'=>'VendorController@store','as'=>'vendor-create']);
			Route::post('/delete',['uses'=>'VendorController@delete','as'=>'vendor-delete']);
			Route::get('/edit/{id}',['uses'=>'VendorController@edit','as'=>'vendor-edit']);
			Route::post('/update/{id}',['uses'=>'VendorController@update','as'=>'vendor-update']);
		});
	});
});

/*
 * ------------
 * Vendor Bills
 * ------------
 */
Route::group(['prefix'=>'vendor-bill'],function(){
	Route::get('/',['uses'=>'VendorBillController@index','as'=>'vendor-bill-index']);
	//login routes
	Route::group(['middleware'=>'auth'],function(){

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
			Route::post('/store',['uses'=>'VendorBillController@store','as'=>'vendor-bill-create']);
			Route::post('/delete',['uses'=>'VendorBillController@delete','as'=>'vendor-bill-delete']);
			Route::post('/edit',['uses'=>'VendorBillController@edit','as'=>'vendor-bill-edit']);
			Route::get('/update/paid/{id}',['uses'=>'VendorBillController@update_paid','as'=>'vendor-bill-update-paid']);
		});
	});
});

/*
 * ------------
 * Purchase Orders
 * ------------
 */
Route::group(['prefix'=>'vendor-purchase-order'],function(){
	Route::get('/',['uses'=>'VendorPurchaseOrderController@index','as'=>'vendor-purchase-order-index']);
	//login routes
	Route::group(['middleware'=>'auth'],function(){

		//admin routes
		Route::group(['middleware'=>'admin'],function(){
			Route::get('/create',['uses'=>'VendorPurchaseOrderController@create','as'=>'vendor-purchase-order-create']);
			Route::post('/store',['uses'=>'VendorPurchaseOrderController@store','as'=>'vendor-purchase-order-store']);
			Route::post('/delete',['uses'=>'VendorPurchaseOrderController@delete','as'=>'vendor-purchase-order-delete']);
			Route::get('/edit/{id}',['uses'=>'VendorPurchaseOrderController@edit','as'=>'vendor-purchase-order-edit']);
			Route::post('/update/{id}',['uses'=>'VendorPurchaseOrderController@update','as'=>'vendor-purchase-order-update']);
			Route::get('/export/{id}',['uses'=>'VendorPurchaseOrderController@export','as'=>'vendor-purchase-order-export']);
		});
	});
});
