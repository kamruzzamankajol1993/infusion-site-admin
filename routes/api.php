<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SliderController; // <-- Add this import
use App\Http\Controllers\Api\IifcStrengthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AboutUsController; 
use App\Http\Controllers\Api\OfficerCategoryController;
use App\Http\Controllers\Api\OfficerController;
use App\Http\Controllers\Api\ProjectCategoryController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Controllers\Api\NoticeCategoryController; // <-- 1. Add this import
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PressReleaseController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\UpcomingTabImageController;
use App\Http\Controllers\Api\EngageSectionController;
use App\Http\Controllers\Api\TrainingEnrollmentController;
use App\Http\Controllers\Api\JobApplicantController;
use App\Http\Controllers\Api\SocialLinkController;
use App\Http\Controllers\Api\ImportantLinkController;
use App\Http\Controllers\Api\FileDownloadController;
use App\Http\Controllers\Api\ExtraPageController;
use App\Http\Controllers\Api\TopHeaderLinkController;
use App\Http\Controllers\Api\GlobalSearchController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\BreadCrumbImageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/countries-with-projects', [CountryController::class, 'indexWithProjects']); // <-- ADD THIS
Route::get('/clients-paginated', [ClientController::class, 'paginatedIndex']);
Route::get('/settings/all-menu-labels', [SiteSettingController::class, 'getAllMenuLabels']);

// --- ADD COUNTRY ROUTES ---
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries-paginated', [CountryController::class, 'paginatedIndex']);
// --- END COUNTRY ROUTES ---
Route::get('/breadcrumb-image/{type}', [BreadCrumbImageController::class, 'showByType']);
// --- Add this route for IIFC Strengths ---
Route::get('/strengths', [IifcStrengthController::class, 'index']);
Route::get('/sliders', [SliderController::class, 'index']); // <-- Add this route
Route::get('/clients', [ClientController::class, 'index']);
Route::get('/home_page_service_section', [ServiceController::class, 'homePageServices']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);
Route::get('/flagship_projects', [ProjectController::class, 'flagshipProjects']);
Route::get('/all_flagship_projects', [ProjectController::class, 'flagshipProjectsAll']);

Route::get('/about_us', [AboutUsController::class, 'index']);
Route::get('/header_category_list', [OfficerCategoryController::class, 'headerCategoryList']);
Route::get('/officers_by_category/{category}', [OfficerController::class, 'showByCategory']);
Route::get('/officers_by_parent_category/{category}', [OfficerController::class, 'showByParentCategory']);
// --- UPDATED ROUTE ---
Route::get('/officers/{slug}', [OfficerController::class, 'show']); // Changed {officer} to {slug}
// --------------------

// 1. Project Category List (No Pagination)
Route::get('/project_categories', [ProjectCategoryController::class, 'index']);

// 2. Project List (With Pagination)
Route::get('/projects', [ProjectController::class, 'index']);

Route::get('/projects/active-years', [ProjectController::class, 'getActiveProjectYears']);

// 3. Category-wise Project List (With Pagination)
Route::get('/projects_by_category/{slug}', [ProjectController::class, 'showByCategory']);

// 4. Project Detail
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);

// --- ADD THESE TWO NEW ROUTES ---
Route::get('/projects/ongoing', [ProjectController::class, 'ongoingProjects']);
Route::get('/projects/complete', [ProjectController::class, 'completeProjects']);
// --- END NEW ROUTES ---

// 1. Training List (No Pagination)
Route::get('/trainings', [TrainingController::class, 'index']);
Route::get('/all_trainings', [TrainingController::class, 'allTrainings']);

// 2. Training Detail
Route::get('/trainings/{slug}', [TrainingController::class, 'show']);

// 3. Training Calendar Data
Route::get('/training_calendar', [TrainingController::class, 'calendar']);

Route::get('/trainings/document/{document}/download', [TrainingController::class, 'downloadDocument']);

// 1. Notice Category List (No Pagination)
Route::get('/notice_categories', [NoticeCategoryController::class, 'index']);

Route::get('/latest_notices', [NoticeController::class, 'latestNotices']);

// 2. Category-wise Notice List (With Pagination)
Route::get('/notice_by_category/{category}', [NoticeController::class, 'showByCategory']);
Route::get('/gallery', [GalleryController::class, 'index']);

// 1. Event List (With Pagination)
Route::get('/events', [EventController::class, 'index']);

// 2. Event Detail
Route::get('/events/{slug}', [EventController::class, 'show']);

// 1. Press Release List (With Pagination)
Route::get('/press_releases', [PressReleaseController::class, 'index']);

// 2. Press Release Detail
Route::get('/press_releases/{slug}', [PressReleaseController::class, 'show']);
Route::get('/publications', [PublicationController::class, 'index']);

// 1. Career List (With Pagination)
Route::get('/careers', [CareerController::class, 'index']);

// 2. Career Detail
Route::get('/careers', [CareerController::class, 'index']);
Route::get('/careers/{slug}', [CareerController::class, 'show']);
Route::get('/downloads', [DownloadController::class, 'index']);
Route::get('/file_download_list', [FileDownloadController::class, 'index']);

// --- THIS IS THE MODIFIED ROUTE ---
Route::get('/downloads/file/{type}/{id}', [DownloadController::class, 'downloadFile'])
    ->where('type', 'Notice|Publication|Download') // <-- ADDED 'Download'
    ->where('id', '[0-9]+'); // Ensure ID is numeric
// ------------------------------------

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/upcoming_tab_image', [UpcomingTabImageController::class, 'index']);

Route::get('/engage_sections', [EngageSectionController::class, 'index']);

Route::post('/training_enrollments', [TrainingEnrollmentController::class, 'store']);

Route::post('/job_applicants', [JobApplicantController::class, 'store']);

Route::get('/social_links', [SocialLinkController::class, 'index']);

Route::get('/important_links', [ImportantLinkController::class, 'index']);

// --- 2. ADD THESE NEW ROUTES ---
Route::get('/privacy-policy', [ExtraPageController::class, 'privacyPolicy']);
Route::get('/terms-and-conditions', [ExtraPageController::class, 'terms']);
Route::get('/faq', [ExtraPageController::class, 'faq']);

Route::get('/top-header-links', [TopHeaderLinkController::class, 'index']);
// --- END NEW ROUTES ---
Route::get('/global-search', [GlobalSearchController::class, 'search']);