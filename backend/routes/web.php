<?php

use App\Http\Controllers\Admin\AboutSettingsController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\ApproachCardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CaseStudyCategoryController;
use App\Http\Controllers\Admin\CaseStudyController;
use App\Http\Controllers\Admin\ContactDepartmentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FormTemplateController;
use App\Http\Controllers\Admin\HeroSettingsController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\MissionBulletController;
use App\Http\Controllers\Admin\NavItemController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\ReorderController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TimelineMilestoneController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/site-settings', [SiteSettingsController::class, 'edit'])->name('site-settings.edit');
    Route::put('/site-settings', [SiteSettingsController::class, 'update'])->name('site-settings.update');
    Route::get('/hero-settings', [HeroSettingsController::class, 'edit'])->name('hero-settings.edit');
    Route::put('/hero-settings', [HeroSettingsController::class, 'update'])->name('hero-settings.update');
    Route::get('/about-settings', [AboutSettingsController::class, 'edit'])->name('about-settings.edit');
    Route::put('/about-settings', [AboutSettingsController::class, 'update'])->name('about-settings.update');

    Route::resource('hero-slides', HeroSlideController::class);
    Route::resource('nav-items', NavItemController::class);
    Route::resource('social-links', SocialLinkController::class);
    Route::resource('service-categories', ServiceCategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('case-study-categories', CaseStudyCategoryController::class);
    Route::resource('case-studies', CaseStudyController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('timeline-milestones', TimelineMilestoneController::class);
    Route::resource('mission-bullets', MissionBulletController::class);
    Route::resource('approach-cards', ApproachCardController::class);
    Route::resource('achievements', AchievementController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('team-members', TeamMemberController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('contact-departments', ContactDepartmentController::class);
    Route::resource('publications', PublicationController::class);
    Route::resource('form-templates', FormTemplateController::class);
    Route::resource('news-posts', NewsPostController::class);
    Route::resource('pages', PageController::class);

    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{id}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::patch('/contact-messages/{id}/read', [ContactMessageController::class, 'markRead'])->name('contact-messages.markRead');
    Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::post('/reorder/{model}', [ReorderController::class, 'update'])->name('reorder');
});
