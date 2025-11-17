<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Controller Imports ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Front\TextController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CustomerPersonalController;

// --- Admin Controller Imports ---
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientSayController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DefaultLocationController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\DigitalMarketingGrowthItemController; // Added
use App\Http\Controllers\Admin\DigitalMarketingPageController;     // Added
use App\Http\Controllers\Admin\DigitalMarketingSolutionController;  // Added
use App\Http\Controllers\Admin\DownloadController;                 // Added
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ExtraPageController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HomePageDescriptionController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\IifcStrengthController;
use App\Http\Controllers\Admin\ImportantLinkController;             // Added
use App\Http\Controllers\Admin\MediaController;                     // Added
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\NavbarSettingController;
use App\Http\Controllers\Admin\NewsAndMediaController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\PressReleaseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RewardPointController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SearchLogController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SidebarMenuController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SliderControlController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SolutionController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SystemInformationController;
use App\Http\Controllers\Admin\TeamController;                       // Added
use App\Http\Controllers\Admin\TopHeaderLinkController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhyChooseUsController;               // Added
use App\Http\Controllers\Admin\WhyUsController;
use App\Http\Controllers\Admin\WebSolutionPageController;
use App\Http\Controllers\Admin\WebSolutionChecklistController;
use App\Http\Controllers\Admin\WebSolutionIncludeController;
use App\Http\Controllers\Admin\WebSolutionProvidingController;
use App\Http\Controllers\Admin\WebSolutionWorkCategoryController;
use App\Http\Controllers\Admin\WebSolutionWorkItemController;
use App\Http\Controllers\Admin\WebSolutionCareItemController;                     // Added
use App\Http\Controllers\Admin\FacebookPageController;
use App\Http\Controllers\Admin\FacebookPricingPackageController;
use App\Http\Controllers\Admin\FacebookMoreServiceController;
use App\Http\Controllers\Admin\GraphicDesignPageController;
use App\Http\Controllers\Admin\GraphicDesignChecklistController;
use App\Http\Controllers\Admin\GraphicDesignSolutionController;
use App\Http\Controllers\Admin\FacebookAdsPageController;
use App\Http\Controllers\Admin\FacebookAdsFeatureController;
use App\Http\Controllers\Admin\FacebookAdsCampaignController;
use App\Http\Controllers\Admin\FacebookAdsPricingCategoryController;
use App\Http\Controllers\Admin\FacebookAdsPricingPackageController;
use App\Http\Controllers\Admin\FacebookAdsFaqController;
use App\Http\Controllers\Admin\UkCompanyPageController;
use App\Http\Controllers\Admin\UkPricingPackageController;
use App\Http\Controllers\Admin\UkTestimonialController;
use App\Http\Controllers\Admin\UkReviewPlatformController;
use App\Http\Controllers\Admin\StoreMainBannerController;
use App\Http\Controllers\Admin\StoreSideBannerController;
use App\Http\Controllers\Admin\VpsPageController;
use App\Http\Controllers\Admin\VpsPackageCategoryController;
use App\Http\Controllers\Admin\VpsPackageController;
/*
|--------------------------------------------------------------------------
| Public (Guest) Routes
|--------------------------------------------------------------------------
*/

// Utility
Route::get('/clear', function() {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return redirect()->back();
});

// Standard Laravel Auth Routes (Login, Register, etc. - mostly for backend)
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Admin Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'viewLoginPage')->name('viewLoginPage');
    Route::get('/password/reset', 'showLinkRequestForm')->name('showLinkRequestForm');
    Route::post('/password/reset/submit', 'reset')->name('reset');
});

// Frontend Customer Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('/login-register', 'loginregisterPage')->name('front.loginRegister');
    Route::post('/login-user-post', 'loginUserPost')->name('front.loginUserPost');
    Route::post('/register-user-post', 'registerUserPost')->name('front.registerUserPost');

    // Frontend Password Reset
    Route::get('forgot-password', 'showForgotPasswordForm')->name('front.password.request');
    Route::post('forgot-password', 'sendResetLink')->name('front.password.email');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('front.password.reset');
    Route::post('reset-password', 'resetPassword')->name('front.password.update');
});

// Payment Webhooks
Route::post('/payment/success', [FrontController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/fail', [FrontController::class, 'paymentFail'])->name('payment.fail');
Route::post('/payment/cancel', [FrontController::class, 'paymentCancel'])->name('payment.cancel');

// Other
Route::post('/textMessageAll', [TextController::class, 'textMessage'])->name('text.index');


/*
|--------------------------------------------------------------------------
| Authenticated Frontend (Customer) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
Route::get('ajax/products', [App\Http\Controllers\Admin\ProductController::class, 'data'])->name('ajax.product.data');
    Route::post('products/update-order', [App\Http\Controllers\Admin\ProductController::class, 'updateOrder'])->name('product.updateOrder');
    Route::resource('product', App\Http\Controllers\Admin\ProductController::class);

    // Category Routes
    Route::get('ajax/categories', [App\Http\Controllers\Admin\CategoryController::class, 'data'])->name('ajax.category.data');
    Route::resource('category', App\Http\Controllers\Admin\CategoryController::class);

// Store Main Banner (Slider)
    Route::get('ajax/store-main-banners', [StoreMainBannerController::class, 'data'])->name('ajax.storeMainBanner.data');
    Route::post('store-main-banners/update-order', [StoreMainBannerController::class, 'updateOrder'])->name('storeMainBanner.updateOrder');
    Route::resource('storeMainBanner', StoreMainBannerController::class);

    // Store Side Banners (Singleton)
    Route::get('store-side-banners', [StoreSideBannerController::class, 'index'])->name('storeSideBanner.index');
    Route::post('store-side-banners', [StoreSideBannerController::class, 'storeOrUpdate'])->name('storeSideBanner.storeOrUpdate');
    // --- Frontend: VPS/RDP Page Content ---
    Route::prefix('vps-page-setup')->name('vpsPage.')->group(function () {
        
        // Page Content (Singleton)
        Route::get('page-content', [VpsPageController::class, 'index'])->name('page.index');
        Route::post('page-content', [VpsPageController::class, 'storeOrUpdate'])->name('page.storeOrUpdate');

        // Package Categories (CRUD + Reorder)
        Route::get('ajax/categories', [VpsPackageCategoryController::class, 'data'])->name('category.data');
        Route::post('categories/update-order', [VpsPackageCategoryController::class, 'updateOrder'])->name('category.updateOrder');
        Route::resource('category', VpsPackageCategoryController::class);

        // Packages (CRUD + Reorder)
        Route::get('ajax/packages', [VpsPackageController::class, 'data'])->name('package.data');
        Route::post('packages/update-order', [VpsPackageController::class, 'updateOrder'])->name('package.updateOrder');
        Route::resource('package', VpsPackageController::class);

    });


    // --- Frontend: UK Company Setup Page ---
    Route::prefix('uk-company-setup')->name('ukCompany.')->group(function () {
        
        // Page Content (Singleton)
        Route::get('page-content', [UkCompanyPageController::class, 'index'])->name('page.index');
        Route::post('page-content', [UkCompanyPageController::class, 'storeOrUpdate'])->name('page.storeOrUpdate');

        // Pricing Packages (CRUD + Reorder)
        Route::get('ajax/pricing-packages', [UkPricingPackageController::class, 'data'])->name('package.data');
        Route::post('pricing-packages/update-order', [UkPricingPackageController::class, 'updateOrder'])->name('package.updateOrder');
        Route::resource('package', UkPricingPackageController::class);

        // Testimonials (CRUD + Reorder)
        Route::get('ajax/testimonials', [UkTestimonialController::class, 'data'])->name('testimonial.data');
        Route::post('testimonials/update-order', [UkTestimonialController::class, 'updateOrder'])->name('testimonial.updateOrder');
        Route::resource('testimonial', UkTestimonialController::class);

        // Review Platforms (CRUD + Reorder)
        Route::get('ajax/review-platforms', [UkReviewPlatformController::class, 'data'])->name('reviewPlatform.data');
        Route::post('review-platforms/update-order', [UkReviewPlatformController::class, 'updateOrder'])->name('reviewPlatform.updateOrder');
        Route::resource('review-platform', UkReviewPlatformController::class);
    });


    // --- Frontend: Facebook Ads Page Content ---
    Route::prefix('facebook-ads')->name('facebookAds.')->group(function () {
        
        // Page Content (Singleton)
        Route::get('page-content', [FacebookAdsPageController::class, 'index'])->name('page.index');
        Route::post('page-content', [FacebookAdsPageController::class, 'storeOrUpdate'])->name('page.storeOrUpdate');

        // Features (CRUD + Reorder)
        Route::get('ajax/features', [FacebookAdsFeatureController::class, 'data'])->name('feature.data');
        Route::post('features/update-order', [FacebookAdsFeatureController::class, 'updateOrder'])->name('feature.updateOrder');
        Route::resource('feature', FacebookAdsFeatureController::class);

        // Campaign Types (Accordion) (CRUD + Reorder)
        Route::get('ajax/campaigns', [FacebookAdsCampaignController::class, 'data'])->name('campaign.data');
        Route::post('campaigns/update-order', [FacebookAdsCampaignController::class, 'updateOrder'])->name('campaign.updateOrder');
        Route::resource('campaign', FacebookAdsCampaignController::class);

        // Pricing Categories (CRUD + Reorder)
        Route::get('ajax/pricing-categories', [FacebookAdsPricingCategoryController::class, 'data'])->name('pricingCategory.data');
        Route::post('pricing-categories/update-order', [FacebookAdsPricingCategoryController::class, 'updateOrder'])->name('pricingCategory.updateOrder');
        Route::resource('pricingCategory', FacebookAdsPricingCategoryController::class);

        // Pricing Packages (CRUD + Reorder)
        Route::get('ajax/pricing-packages', [FacebookAdsPricingPackageController::class, 'data'])->name('pricingPackage.data');
        Route::post('pricing-packages/update-order', [FacebookAdsPricingPackageController::class, 'updateOrder'])->name('pricingPackage.updateOrder');
        Route::resource('pricingPackage', FacebookAdsPricingPackageController::class);

        // FAQs (CRUD + Reorder)
        Route::get('ajax/faqs', [FacebookAdsFaqController::class, 'data'])->name('faq.data');
        Route::post('faqs/update-order', [FacebookAdsFaqController::class, 'updateOrder'])->name('faq.updateOrder');
        Route::resource('faq', FacebookAdsFaqController::class);
    });


    // --- Frontend: Facebook Page Setup ---
    Route::prefix('facebook-page-setup')->name('facebookPage.')->group(function () {
        
        // Page Content (Singleton)
        Route::get('page-content', [FacebookPageController::class, 'index'])->name('page.index');
        Route::post('page-content', [FacebookPageController::class, 'storeOrUpdate'])->name('page.storeOrUpdate');

        // Pricing Packages (CRUD + Reorder)
        Route::get('ajax/pricing-packages', [FacebookPricingPackageController::class, 'data'])->name('package.data');
        Route::post('pricing-packages/update-order', [FacebookPricingPackageController::class, 'updateOrder'])->name('package.updateOrder');
        Route::resource('package', FacebookPricingPackageController::class);

        // More Services (CRUD + Reorder)
        Route::get('ajax/more-services', [FacebookMoreServiceController::class, 'data'])->name('service.data');
        Route::post('more-services/update-order', [FacebookMoreServiceController::class, 'updateOrder'])->name('service.updateOrder');
        Route::resource('service', FacebookMoreServiceController::class);

    });

    Route::prefix('graphic-design')->name('graphicDesign.')->group(function () {
        
        // Page Content (Singleton)
        Route::get('page-content', [GraphicDesignPageController::class, 'index'])->name('page.index');
        Route::post('page-content', [GraphicDesignPageController::class, 'storeOrUpdate'])->name('page.storeOrUpdate');

        // Checklist (CRUD + Reorder)
        Route::get('ajax/checklist', [GraphicDesignChecklistController::class, 'data'])->name('checklist.data');
        Route::post('checklist/update-order', [GraphicDesignChecklistController::class, 'updateOrder'])->name('checklist.updateOrder');
        Route::resource('checklist', GraphicDesignChecklistController::class);

        // Solutions (CRUD + Reorder)
        Route::get('ajax/solutions', [GraphicDesignSolutionController::class, 'data'])->name('solution.data');
        Route::post('solutions/update-order', [GraphicDesignSolutionController::class, 'updateOrder'])->name('solution.updateOrder');
        Route::resource('solution', GraphicDesignSolutionController::class);

    });


    // --- Frontend: Web Solution Page Content ---
    Route::prefix('web-solution')->name('webSolution.')->group(function () {
        
        // Page Content (Singleton)
        Route::get('page-content', [WebSolutionPageController::class, 'index'])->name('page.index');
        Route::post('page-content', [WebSolutionPageController::class, 'storeOrUpdate'])->name('page.storeOrUpdate');

        // Checklist (CRUD + Reorder)
        Route::get('ajax/checklist', [WebSolutionChecklistController::class, 'data'])->name('checklist.data');
        Route::post('checklist/update-order', [WebSolutionChecklistController::class, 'updateOrder'])->name('checklist.updateOrder');
        Route::resource('checklist', WebSolutionChecklistController::class);

        // Service Includes (CRUD + Reorder)
        Route::get('ajax/includes', [WebSolutionIncludeController::class, 'data'])->name('include.data');
        Route::post('includes/update-order', [WebSolutionIncludeController::class, 'updateOrder'])->name('include.updateOrder');
        Route::resource('include', WebSolutionIncludeController::class);

        // Service Providing (CRUD + Reorder)
        Route::get('ajax/providing', [WebSolutionProvidingController::class, 'data'])->name('providing.data');
        Route::post('providing/update-order', [WebSolutionProvidingController::class, 'updateOrder'])->name('providing.updateOrder');
        Route::resource('providing', WebSolutionProvidingController::class);

        // Work Categories (CRUD + Reorder)
        Route::get('ajax/work-categories', [WebSolutionWorkCategoryController::class, 'data'])->name('workCategory.data');
        Route::post('work-categories/update-order', [WebSolutionWorkCategoryController::class, 'updateOrder'])->name('workCategory.updateOrder');
        Route::resource('workCategory', WebSolutionWorkCategoryController::class);

        // Work Items (Portfolio) (CRUD + Reorder)
        Route::get('ajax/work-items', [WebSolutionWorkItemController::class, 'data'])->name('workItem.data');
        Route::post('work-items/update-order', [WebSolutionWorkItemController::class, 'updateOrder'])->name('workItem.updateOrder');
        Route::resource('workItem', WebSolutionWorkItemController::class);

        // Care Items (CRUD + Reorder)
        Route::get('ajax/care-items', [WebSolutionCareItemController::class, 'data'])->name('careItem.data');
        Route::post('care-items/update-order', [WebSolutionCareItemController::class, 'updateOrder'])->name('careItem.updateOrder');
        Route::resource('careItem', WebSolutionCareItemController::class);
    });
    
    // Customer Dashboard
    Route::controller(AuthController::class)->group(function () {
        Route::get('/user-dashboard', 'userDashboard')->name('front.userDashboard');
        Route::post('/profile/update', 'updateProfile')->name('profile.update');
        Route::post('/password/update', 'updatePassword')->name('password.update');
    });

    // Customer Tickets
    Route::resource('customerPersonalTicket', CustomerPersonalController::class);
    Route::controller(CustomerPersonalController::class)->group(function () {
        Route::get('/customerGeneralTicketPdf/{id}', 'customerGeneralTicketPdf')->name('customerGeneralTicketPdf');
        Route::get('/customerPersonalTicketPdf/{id}', 'customerPersonalTicketPdf')->name('customerPersonalTicketPdf');
        Route::get('/customerPersonalTicket', 'customerPersonalTicket')->name('customerPersonalTicket');
    });

});


/*
|--------------------------------------------------------------------------
| Authenticated Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // --- Dashboard ---
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // --- Settings & Access Control ---
    Route::resource('users', UserController::class);
    Route::controller(UserController::class)->group(function () {
        Route::get('/downloadUserPdf','downloadUserPdf')->name('downloadUserPdf');
        Route::get('/downloadUserExcel','downloadUserExcel')->name('downloadUserExcel');
        Route::get('/ajax-table-user/data','data')->name('ajax.usertable.data');
        Route::get('/activeOrInActiveUser/{status}/{id}', 'activeOrInActiveUser')->name('activeOrInActiveUser');
    });

    Route::resource('roles', RoleController::class);
    Route::controller(RoleController::class)->group(function () {
        Route::get('/downloadRolePdf','downloadRolePdf')->name('downloadRolePdf');
        Route::get('/downloadRoleExcel','downloadRoleExcel')->name('downloadRoleExcel');
        Route::get('/ajax-table-role/data','data')->name('ajax.roletable.data');
    });

    Route::resource('permissions', PermissionController::class);
    Route::delete('permissions-delete', [PermissionController::class, 'destroyMultiple'])->name('permissions.destroyMultiple');
    Route::controller(PermissionController::class)->group(function () {
        Route::get('/downloadPermissionPdf','downloadPermissionPdf')->name('downloadPermissionPdf');
        Route::get('/downloadPermissionExcel','downloadPermissionExcel')->name('downloadPermissionExcel');
        Route::get('/ajax-table-permission/data','data')->name('ajax.permissiontable.data');
    });

    Route::resource('department', DepartmentController::class);
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('/downloadDepartmentPdf','downloadDepartmentPdf')->name('downloadDepartmentPdf');
        Route::get('/downloadDepartmentExcel','downloadDepartmentExcel')->name('downloadDepartmentExcel');
        Route::get('/ajax-table-department/data','data')->name('ajax.departmenttable.data');
    });

    Route::resource('designation', DesignationController::class);
    Route::controller(DesignationController::class)->group(function () {
        Route::get('/downloadDesignationPdf','downloadDesignationPdf')->name('downloadDesignationPdf');
        Route::get('/downloadDesignationExcel','downloadDesignationExcel')->name('downloadDesignationExcel');
        Route::get('/ajax-table-designation/data','data')->name('ajax.designationtable.data');
    });

    Route::resource('systemInformation', SystemInformationController::class);
    Route::controller(SystemInformationController::class)->group(function () {
        Route::get('/downloadSystemInformationPdf','downloadSystemInformationPdf')->name('downloadSystemInformationPdf');
        Route::get('/downloadSystemInformationExcel','downloadSystemInformationExcel')->name('downloadSystemInformationExcel');
        Route::get('/ajax-table-systemInformation/data','data')->name('ajax.systemInformationtable.data');
    });

    Route::resource('setting', SettingController::class);
    Route::controller(SettingController::class)->group(function () {
        Route::get('/error_500', 'error_500')->name('error_500');
        Route::get('/profileView', 'profileView')->name('profileView');
        Route::get('/profileSetting', 'profileSetting')->name('profileSetting');
        Route::post('/profileSettingUpdate', 'profileSettingUpdate')->name('profileSettingUpdate');
        Route::post('/passwordUpdate', 'passwordUpdate')->name('passwordUpdate');
        Route::post('/checkMailPost', 'checkMailPost')->name('checkMailPost');
        Route::get('/checkMailForPassword', 'checkMailForPassword')->name('checkMailForPassword');
        Route::get('/newEmailNotify', 'newEmailNotify')->name('newEmailNotify');
        Route::post('/postPasswordChange', 'postPasswordChange')->name('postPasswordChange');
        Route::get('/accountPasswordChange/{id}', 'accountPasswordChange')->name('accountPasswordChange');
    });

    Route::get('top-header-links', [TopHeaderLinkController::class, 'index'])->name('topHeaderLink.index');
    Route::post('top-header-links', [TopHeaderLinkController::class, 'storeOrUpdate'])->name('topHeaderLink.storeOrUpdate');
    
    Route::get('settings/navbar-menus', [NavbarSettingController::class, 'index'])->name('navbarSetting.index');
    Route::post('settings/navbar-menus', [NavbarSettingController::class, 'storeOrUpdate'])->name('navbarSetting.storeOrUpdate');

    Route::get('social-links', [SocialLinkController::class, 'index'])->name('socialLink.index');
    Route::post('social-links', [SocialLinkController::class, 'store'])->name('socialLink.store');
    Route::get('social-links/{id}', [SocialLinkController::class, 'show'])->name('socialLink.show');
    Route::put('social-links/{id}', [SocialLinkController::class, 'update'])->name('socialLink.update');
    Route::delete('social-links/{id}', [SocialLinkController::class, 'destroy'])->name('socialLink.destroy');
    Route::get('ajax/social-links', [SocialLinkController::class, 'data'])->name('ajax.socialLink.data');


    // --- E-Commerce & Business ---
    Route::resource('product', ProductController::class);
    Route::get('ajax-products-data', [ProductController::class, 'data'])->name('ajax.product.data');
    Route::get('get-attributes-by-category/{category}', [ProductController::class, 'getAttributesByCategory'])->name('products.get-attributes');
    Route::get('/products/{product}/attributes-for-order', [ProductController::class, 'getAttributesForOrder'])->name('product.get-attributes-for-order');

    Route::resource('order', OrderController::class);
    Route::get('ajax_orders', [OrderController::class, 'data'])->name('ajax.order.data');
    Route::controller(OrderController::class)->group(function () {
        Route::post('orders/bulk-update-status', 'bulkUpdateStatus')->name('order.bulk-update-status');
        Route::post('order-payment/{order}', 'storePayment')->name('order.payment.store');
        Route::get('order-print-a4/{order}', 'printA4')->name('order.print.a4');
        Route::get('order-print-a5/{order}', 'printA5')->name('order.print.a5');
        Route::get('order-print-pos/{order}', 'printPOS')->name('order.print.pos');
        Route::get('order-search-customers', 'searchCustomers')->name('order.search-customers');
        Route::post('storeorder-update-status/{order}', 'updateStatus')->name('order.update-status');
        Route::get('orderstore_details/{id}', 'getDetails')->name('order.get-details');
        Route::get('ordersdestroymultiple', 'destroyMultiple')->name('order.destroy-multiple');
        Route::get('order-get-customer-details/{id}', 'getCustomerDetails')->name('order.get-customer-details');
        Route::get('order-search-products', 'searchProducts')->name('order.search-products');
        Route::get('order-get-product-details/{id}', 'getProductDetails')->name('order.get-product-details');
    });
    
    Route::resource('customer', CustomerController::class);
    Route::get('ajax-customers', [CustomerController::class, 'data'])->name('ajax.customer.data');
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customers/export','exportCustomers')->name('customer.export');
        Route::get('/customers/check-email','checkEmailUniqueness')->name('customers.checkEmail');
        Route::get('/downloadcustomerPdf','downloadcustomerPdf')->name('downloadcustomerPdf');
        Route::get('/downloadcustomerExcel','downloadcustomerExcel')->name('downloadcustomerExcel');
        Route::get('/ajax-table-customer/data','data')->name('ajax.customertable.data');
    });

    Route::resource('coupon', CouponController::class);
    Route::get('ajax-coupons', [CouponController::class, 'data'])->name('ajax.coupons.data');
    Route::post('/coupons/apply', [CouponController::class, 'applyCoupon'])->name('coupons.apply');


    // --- Frontend: Home Page Content ---
    Route::get('/hero-section', [HeroSectionController::class, 'index'])->name('admin.hero.index');
    Route::post('/hero-section', [HeroSectionController::class, 'update'])->name('admin.hero.update');
    Route::get('/home-description', [HomePageDescriptionController::class, 'index'])->name('admin.home_description.index');
    Route::post('/home-description', [HomePageDescriptionController::class, 'update'])->name('admin.home_description.update');
    
    Route::resource('slider', SliderController::class);
    Route::get('ajax/sliders', [SliderController::class, 'data'])->name('ajax.slider.data');
    Route::post('sliders/update-order', [SliderController::class, 'updateOrder'])->name('slider.updateOrder');
    Route::get('sliders/all-for-reorder', [SliderController::class, 'allForReorder'])->name('slider.allForReorder');
    
    Route::get('iifc-strength-settings', [IifcStrengthController::class, 'index'])->name('iifcStrength.index');
    Route::put('iifc-strength-settings/{id}', [IifcStrengthController::class, 'update'])->name('iifcStrength.update');

    Route::get('slider-control', [SliderControlController::class, 'index'])->name('slider.control.index');
    Route::post('slider-control', [SliderControlController::class, 'update'])->name('slider.control.update');
    Route::get('slider-control/search', [SliderControlController::class, 'searchProducts'])->name('slider.control.search');


    // --- Frontend: About Us Page Content ---
    Route::get('about-us', [AboutUsController::class, 'index'])->name('aboutUs.index');
    Route::post('about-us', [AboutUsController::class, 'store'])->name('aboutUs.store');
    Route::put('about-us/{aboutUs}', [AboutUsController::class, 'update'])->name('aboutUs.update');
    
    Route::resource('team', TeamController::class);
    Route::get('ajax/teams', [TeamController::class, 'data'])->name('ajax.team.data');
    Route::post('teams/update-order', [TeamController::class, 'updateOrder'])->name('team.updateOrder');


    // --- Frontend: Digital Marketing Page Content ---
    Route::get('digital-marketing-page', [DigitalMarketingPageController::class, 'index'])->name('digitalMarketingPage.index');
    Route::post('digital-marketing-page', [DigitalMarketingPageController::class, 'storeOrUpdate'])->name('digitalMarketingPage.storeOrUpdate');

    Route::resource('digital-marketing-growth', DigitalMarketingGrowthItemController::class);
    Route::get('ajax/digital-marketing-growth', [DigitalMarketingGrowthItemController::class, 'data'])->name('ajax.digitalMarketingGrowth.data');
    Route::post('digital-marketing-growth/update-order', [DigitalMarketingGrowthItemController::class, 'updateOrder'])->name('digitalMarketingGrowth.updateOrder');

    Route::resource('digital-marketing-solution', DigitalMarketingSolutionController::class);
    Route::get('ajax/digital-marketing-solutions', [DigitalMarketingSolutionController::class, 'data'])->name('ajax.digitalMarketingSolution.data');
    Route::post('digital-marketing-solutions/update-order', [DigitalMarketingSolutionController::class, 'updateOrder'])->name('digitalMarketingSolution.updateOrder');


    // --- Frontend: Other Content Modules ---
    Route::resource('service', ServiceController::class);
    Route::get('ajax/services', [ServiceController::class, 'data'])->name('ajax.service.data');
    Route::post('services-update-order', [ServiceController::class, 'updateOrder'])->name('service.updateOrder');
    Route::post('/service/update-homepage-order', [ServiceController::class, 'updateHomepageOrder'])->name('service.updateHomepageOrder');

    Route::resource('solution', SolutionController::class);
    Route::get('ajax-solutions', [SolutionController::class, 'data'])->name('ajax.solution.data');

    Route::resource('why-us', WhyUsController::class);
    Route::get('ajax/why-us', [WhyUsController::class, 'data'])->name('ajax.why-us.data');
    
    Route::resource('why-choose-us', WhyChooseUsController::class);
    Route::get('ajax/why-choose-us', [WhyChooseUsController::class, 'data'])->name('ajax.whyChooseUs.data');
    Route::post('why-choose-us/update-order', [WhyChooseUsController::class, 'updateOrder'])->name('whyChooseUs.updateOrder');
    
    Route::resource('client', ClientController::class)->except(['create', 'edit']);
    Route::get('ajax/clients', [ClientController::class, 'data'])->name('ajax.client.data');
    
    Route::resource('country', CountryController::class)->except(['create', 'edit']);
    Route::get('ajax/countries', [CountryController::class, 'data'])->name('ajax.country.data');

    Route::resource('gallery', GalleryController::class);
    Route::get('ajax/gallery', [GalleryController::class, 'data'])->name('ajax.gallery.data');

    Route::resource('media', MediaController::class);
    Route::get('ajax/media', [MediaController::class, 'data'])->name('ajax.media.data');
    Route::post('media/update-order', [MediaController::class, 'updateOrder'])->name('media.updateOrder');

    Route::resource('publication', PublicationController::class);
    Route::get('ajax/publications', [PublicationController::class, 'data'])->name('ajax.publication.data');

    Route::resource('event', EventController::class);
    Route::get('ajax/events', [EventController::class, 'data'])->name('ajax.event.data');

    Route::resource('press-release', PressReleaseController::class)->names('pressRelease');
    Route::get('ajax/press-releases', [PressReleaseController::class, 'data'])->name('ajax.pressRelease.data');
    
    Route::resource('blog', BlogController::class);
    Route::resource('extraPage', ExtraPageController::class);

    Route::resource('download', \App\Http\Controllers\Admin\DownloadController::class);
    Route::get('ajax/downloads', [\App\Http\Controllers\Admin\DownloadController::class, 'data'])->name('ajax.download.data');

    Route::get('important-links', [ImportantLinkController::class, 'index'])->name('importantLink.index');
    Route::get('ajax-important-links/data', [ImportantLinkController::class, 'data'])->name('ajax.importantLink.data');
    Route::post('important-links', [ImportantLinkController::class, 'store'])->name('importantLink.store');
    Route::get('important-links/{id}', [ImportantLinkController::class, 'show'])->name('importantLink.show');
    Route::put('important-links/{id}', [ImportantLinkController::class, 'update'])->name('importantLink.update');
    Route::delete('important-links/{id}', [ImportantLinkController::class, 'destroy'])->name('importantLink.destroy');
    

    // --- Communication ---
    Route::get('contact-us-messages', [ContactUsController::class, 'index'])->name('contactUs.index');
    Route::get('contact-us-messages/{id}', [ContactUsController::class, 'show'])->name('contactUs.show');
    Route::delete('contact-us-messages/{id}', [ContactUsController::class, 'destroy'])->name('contactUs.destroy');
    Route::delete('contact-us-messages', [ContactUsController::class, 'destroyMultiple'])->name('contactUs.destroyMultiple');
    Route::get('ajax/contact-us-messages', [ContactUsController::class, 'data'])->name('ajax.contactUs.data');

    Route::resource('message', MessageController::class);
    Route::resource('contact', ContactController::class);
    

    // --- Other Modules & Utilities ---
    Route::resource('banner', BannerController::class);
    Route::resource('clientSay', ClientSayController::class);
    
    Route::resource('review', ReviewController::class);
    Route::get('ajax/reviews/data', [ReviewController::class, 'data'])->name('ajax.review.data');
    Route::delete('review-images/{image}', [ReviewController::class, 'destroyImage'])->name('review.image.destroy');
    
    Route::resource('newsAndMedia', NewsAndMediaController::class);
    Route::resource('defaultLocation', DefaultLocationController::class);
    
    Route::resource('searchLog', SearchLogController::class);
    Route::get('/ajax-table-searchLog/data',[SearchLogController::class, 'data'])->name('ajax.searchLogtable.data');
    
    Route::get('sidebar-menu-control', [SidebarMenuController::class, 'index'])->name('sidebar-menu.control.index');
    Route::post('sidebar-menu-control', [SidebarMenuController::class, 'update'])->name('sidebar-menu.control.update');

    

});