<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\MatierController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SubventionController;
use App\Http\Controllers\JobCreationController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ExportImportController;
use App\Http\Controllers\StudentEntityController;
use App\Http\Controllers\StudentSalaryController;
use App\Http\Controllers\BusinessStatusController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\StudentStatisticsController;
use App\Http\Controllers\PromotionApprenantController;
use App\Http\Controllers\StudentJobCreationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser'])->name('login.user');

Route::prefix('student')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('student.login.submit');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [StudentAuthController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

    Route::get('/student/entities', [StudentEntityController::class, 'index'])->name('student.entities.index');
    Route::get('/student/entities/create', [StudentEntityController::class, 'create'])->name('student.entities.create');
    Route::post('/student/entities', [StudentEntityController::class, 'store'])->name('student.entities.store');
    Route::get('/student/entities/{entity}/edit', [StudentEntityController::class, 'edit'])->name('student.entities.edit');
    Route::put('/student/entities/{entity}', [StudentEntityController::class, 'update'])->name('student.entities.update');
    Route::delete('/student/entities/{entity}', [StudentEntityController::class, 'destroy'])->name('student.entities.destroy');

    Route::get('/student/job-creations', [StudentJobCreationController::class, 'index'])->name('student.job_creations.index');
    Route::get('/student/job-creations/create', [StudentJobCreationController::class, 'create'])->name('student.job_creations.create');
    Route::post('/student/job-creations', [StudentJobCreationController::class, 'store'])->name('student.job_creations.store');
    Route::get('/student/job-creations/{jobCreation}/edit', [StudentJobCreationController::class, 'edit'])->name('student.job_creations.edit');
    Route::put('/student/job-creations/{jobCreation}', [StudentJobCreationController::class, 'update'])->name('student.job_creations.update');
    Route::delete('/student/job-creations/{jobCreation}', [StudentJobCreationController::class, 'destroy'])->name('student.job_creations.destroy');

    Route::get('/student/salaries', [StudentSalaryController::class, 'index'])->name('student.salaries.index');
    Route::get('/student/salaries/create', [StudentSalaryController::class, 'create'])->name('student.salaries.create');
    Route::post('/student/salaries', [StudentSalaryController::class, 'store'])->name('student.salaries.store');
    Route::get('/student/salaries/{salary}/edit', [StudentSalaryController::class, 'edit'])->name('student.salaries.edit');
    Route::put('/student/salaries/{salary}', [StudentSalaryController::class, 'update'])->name('student.salaries.update');
    Route::delete('/student/salaries/{salary}', [StudentSalaryController::class, 'destroy'])->name('student.salaries.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
    Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
    Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
    Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
    Route::get('/sites/{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');
    Route::put('/sites/{site}', [SiteController::class, 'update'])->name('sites.update');
    Route::delete('/sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/specializations', [SpecializationController::class, 'index'])->name('specializations.index');
    Route::get('/specializations/create', [SpecializationController::class, 'create'])->name('specializations.create');
    Route::post('/specializations', [SpecializationController::class, 'store'])->name('specializations.store');
    Route::get('/specializations/{specialization}', [SpecializationController::class, 'show'])->name('specializations.show');
    Route::get('/specializations/{specialization}/edit', [SpecializationController::class, 'edit'])->name('specializations.edit');
    Route::put('/specializations/{specialization}', [SpecializationController::class, 'update'])->name('specializations.update');
    Route::delete('/specializations/{specialization}', [SpecializationController::class, 'destroy'])->name('specializations.destroy');
    //  PromotionController routes
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::get('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
    Route::get('/promotions/{promotion}', [PromotionController::class, 'show'])->name('promotions.show');
    Route::get('/promotions/{promotion}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('/promotions/{promotion}', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
    // EntityController routes
    Route::get('entities', [EntityController::class, 'index'])->name('entities.index');
    Route::get('entities/create', [EntityController::class, 'create'])->name('entities.create');
    Route::post('entities', [EntityController::class, 'store'])->name('entities.store');
    Route::get('entities/{entity}', [EntityController::class, 'show'])->name('entities.show');
    Route::get('entities/{entity}/edit', [EntityController::class, 'edit'])->name('entities.edit');
    Route::put('entities/{entity}', [EntityController::class, 'update'])->name('entities.update');
    Route::delete('entities/{entity}', [EntityController::class, 'destroy'])->name('entities.destroy');
    // JobCreationController routes
    Route::get('/job-creations', [JobCreationController::class, 'index'])->name('jobcreations.index');
    Route::get('/job-creations/create', [JobCreationController::class, 'create'])->name('job-creations.create');
    Route::post('/job-creations', [JobCreationController::class, 'store'])->name('job-creations.store');
    Route::get('/job-creations/{jobCreation}/edit', [JobCreationController::class, 'edit'])->name('job-creations.edit');
    Route::put('/job-creations/{jobCreation}', [JobCreationController::class, 'update'])->name('job-creations.update');
    Route::delete('/job-creations/{jobCreation}', [JobCreationController::class, 'destroy'])->name('job-creations.destroy');
    // SalaryController routes
    Route::get('/salaries', [SalaryController::class, 'index'])->name('salaries.index');
    Route::get('/salaries/create', [SalaryController::class, 'create'])->name('salaries.create');
    Route::post('/salaries', [SalaryController::class, 'store'])->name('salaries.store');
    Route::get('/salaries/{salary}/edit', [SalaryController::class, 'edit'])->name('salaries.edit');
    Route::put('/salaries/{salary}', [SalaryController::class, 'update'])->name('salaries.update');
    Route::delete('/salaries/{salary}', [SalaryController::class, 'destroy'])->name('salaries.destroy');
    // SubventionController routes
    Route::get('subventions', [SubventionController::class, 'index'])->name('subventions.index');
    Route::get('subventions/create', [SubventionController::class, 'create'])->name('subventions.create');
    Route::post('subventions', [SubventionController::class, 'store'])->name('subventions.store');
    Route::get('subventions/{subvention}', [SubventionController::class, 'show'])->name('subventions.show');
    Route::get('subventions/{subvention}/edit', [SubventionController::class, 'edit'])->name('subventions.edit');
    Route::put('subventions/{subvention}', [SubventionController::class, 'update'])->name('subventions.update');
    Route::delete('subventions/{subvention}', [SubventionController::class, 'destroy'])->name('subventions.destroy');
    // SubventionController routes
    Route::get('/subventions/create', [SubventionController::class, 'create'])->name('subventions.create');
    Route::post('/subventions', [SubventionController::class, 'store'])->name('subventions.store');
    Route::get('/subventions/students/{promotionId}', [SubventionController::class, 'getStudentsByPromotion']);
    // PromotionApprenantController routes
    Route::get('utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::get('utilisateurs/create', [UtilisateurController::class, 'create'])->name('utilisateurs.create');
    Route::post('utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
    Route::get('utilisateurs/{utilisateur}/edit', [UtilisateurController::class, 'edit'])->name('utilisateurs.edit');
    Route::delete('utilisateurs/{utilisateur}', [UtilisateurController::class, 'destroy'])->name('utilisateurs.destroy');
    // MatierController routes
    Route::get('matieres', [MatierController::class, 'index'])->name('matieres.index');
    Route::get('matieres/create', [MatierController::class, 'create'])->name('matieres.create');
    Route::post('matieres', [MatierController::class, 'store'])->name('matieres.store');
    Route::get('matieres/{matier}/edit', [MatierController::class, 'edit'])->name('matieres.edit');
    Route::put('matieres/{matier}', [MatierController::class, 'update'])->name('matieres.update');
    Route::delete('matieres/{matier}', [MatierController::class, 'destroy'])->name('matieres.destroy');
    // EvaluationController routes
    Route::get('evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('evaluations/{evaluation}', [EvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');
    // AuthController routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // PromotionApprenantController routes
    Route::get('/promotion-apprenant', [PromotionApprenantController::class, 'index'])->name('promotion_apprenant.index');
    Route::get('/promotion-apprenant/create', [PromotionApprenantController::class, 'create'])->name('promotion_apprenant.create');
    Route::post('/promotion-apprenant', [PromotionApprenantController::class, 'store'])->name('promotion_apprenant.store');
    Route::get('/promotion-apprenant/{promotionApprenant}', [PromotionApprenantController::class, 'show'])->name('promotion_apprenant.show');
    Route::get('/promotion-apprenant/{promotionApprenant}/edit', [PromotionApprenantController::class, 'edit'])->name('promotion_apprenant.edit');
    Route::put('/promotion-apprenant/{promotionApprenant}', [PromotionApprenantController::class, 'update'])->name('promotion_apprenant.update');
    Route::delete('/promotion-apprenant/{promotionApprenant}', [PromotionApprenantController::class, 'destroy'])->name('promotion_apprenant.destroy');
    // SpecialiteController routes
    Route::get('/specialites', [SpecialiteController::class, 'index'])->name('specialites.index');
    Route::get('/specialites/create', [SpecialiteController::class, 'create'])->name('specialites.create');
    Route::post('/specialites', [SpecialiteController::class, 'store'])->name('specialites.store');
    Route::get('/specialites/{specialite}/edit', [SpecialiteController::class, 'edit'])->name('specialites.edit');
    Route::put('/specialites/{specialite}', [SpecialiteController::class, 'update'])->name('specialites.update');
    Route::delete('/specialites/{specialite}', [SpecialiteController::class, 'destroy'])->name('specialites.destroy');
    // FollowUpController routes
    Route::get('/follow_ups', [FollowUpController::class, 'index'])->name('follow_ups.index');
    Route::get('/follow_ups/create', [FollowUpController::class, 'create'])->name('follow_ups.create');
    Route::post('/follow_ups', [FollowUpController::class, 'store'])->name('follow_ups.store');
    Route::get('/follow_ups/{id}', [FollowUpController::class, 'show'])->name('follow_ups.show');
    Route::get('/follow_ups/{id}/edit', [FollowUpController::class, 'edit'])->name('follow_ups.edit');
    Route::put('/follow_ups/{id}', [FollowUpController::class, 'update'])->name('follow_ups.update');
    Route::delete('/follow_ups/{id}', [FollowUpController::class, 'destroy'])->name('follow_ups.destroy');
    Route::get('/follow_ups/students/{promotionId}', [FollowUpController::class, 'getStudentsByPromotion']);
    // BusinessStatusController routes
    Route::get('/business_status', [BusinessStatusController::class, 'index'])->name('business_status.index');
    Route::get('/business_status/create', [BusinessStatusController::class, 'create'])->name('business_status.create');
    Route::post('/business_status', [BusinessStatusController::class, 'store'])->name('business_status.store');
    Route::get('/business_status/{id}', [BusinessStatusController::class, 'show'])->name('business_status.show');
    Route::get('/business_status/{id}/edit', [BusinessStatusController::class, 'edit'])->name('business_status.edit');
    Route::put('/business_status/{id}', [BusinessStatusController::class, 'update'])->name('business_status.update');
    Route::delete('/business_status/{id}', [BusinessStatusController::class, 'destroy'])->name('business_status.destroy');
    // StatisticsController routes
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::post('/statistics/filter-students', [StatisticsController::class, 'filterStudents'])->name('statistics.filter.students');
    Route::post('/statistics/filter-matiers', [StatisticsController::class, 'filterMatiers'])->name('statistics.filter.matiers');
    Route::post('/statistics/filter-users', [StatisticsController::class, 'filterUsers'])->name('statistics.filter.users');
    Route::get('/statistics/print', [StatisticsController::class, 'print'])->name('statistics.print');
    // StudentStatisticsController routes
    Route::get('/studentstatistics', [StudentStatisticsController::class, 'index'])->name('student.statistics');
    Route::get('/get-promotions', [StudentStatisticsController::class, 'getPromotions'])->name('get.promotions');
    Route::get('/get-students', [StudentStatisticsController::class, 'getStudents'])->name('get.students');
    Route::get('/studentstatistics/{id}', [StudentStatisticsController::class, 'showStudent'])->name('student.details');
    Route::get('/studentstatistics/{id}/print', [StudentStatisticsController::class, 'printStudent'])->name('student.print');
    // StatisticsController routes
    Route::get('/get-promotions/{siteId}', [StatisticsController::class, 'getPromotions'])->name('get.promotions');
    Route::get('/get-students/{promotionId}', [StatisticsController::class, 'getStudents'])->name('get.students');
    Route::get('/student/{id}', [StatisticsController::class, 'showStudent'])->name('student.show');
    Route::get('/get-promotions/{siteId}', [StudentStatisticsController::class, 'getPromotions'])->name('get.promotions');
    Route::get('/get-students/{promotionId}', [StudentStatisticsController::class, 'getStudents'])->name('get.students');

    // Statistics routes
    Route::prefix('statistics')->group(function () {
        Route::get('students-by-site-period', [StatisticController::class, 'showStudentsBySitePeriodForm'])->name('statistics.students_by_site_period.form');
        Route::get('students-by-site-promotion-period', [StatisticController::class, 'showStudentsBySitePromotionPeriodForm'])->name('statistics.students_by_site_promotion_period.form');
        Route::get('students-with-subvention-by-site', [StatisticController::class, 'showStudentsWithSubventionBySiteForm'])->name('statistics.students_with_subvention_by_site.form');
        Route::get('students-with-subvention-by-site-promotion', [StatisticController::class, 'showStudentsWithSubventionBySiteAndPromotionForm'])->name('statistics.students_with_subvention_by_site_promotion.form');
        Route::post('students-by-site-period', [StatisticController::class, 'studentsBySiteAndPeriod'])->name('statistics.students_by_site_period');
        Route::post('students-by-site-promotion-period', [StatisticController::class, 'studentsBySitePromotionAndPeriod'])->name('statistics.students_by_site_promotion_period');
        Route::post('students-with-subvention-by-site', [StatisticController::class, 'studentsWithSubventionBySite'])->name('statistics.students_with_subvention_by_site');
        Route::post('students-with-subvention-by-site-promotion', [StatisticController::class, 'studentsWithSubventionBySiteAndPromotion'])->name('statistics.students_with_subvention_by_site_promotion');
        Route::get('export/students-by-site-period/{site_id}/{start_date}/{end_date}', [StatisticController::class, 'exportStudentsBySitePeriod'])->name('statistics.export.students_by_site_period');
        Route::get('export/students-by-site-promotion-period/{site_id}/{promotion_id}/{start_date}/{end_date}', [StatisticController::class, 'exportStudentsBySitePromotionPeriod'])->name('statistics.export.students_by_site_promotion_period');
        Route::get('export/students-with-subvention-by-site/{site_id}', [StatisticController::class, 'exportStudentsWithSubventionBySite'])->name('statistics.export.students_with_subvention_by_site');
        Route::get('export/students-with-subvention-by-site-promotion/{site_id}/{promotion_id}', [StatisticController::class, 'exportStudentsWithSubventionBySiteAndPromotion'])->name('statistics.export.students_with_subvention_by_site_promotion');
    });

    // Salary routes
    Route::get('/salaries/{id}', [SalaryController::class, 'show'])->name('salaries.show');
    Route::get('/promotions/{id}/students', [PromotionController::class, 'showStudents'])->name('promotions.students');
    Route::get('/get-students-by-promotion', [SalaryController::class, 'getStudentsByPromotion'])->name('get.students.by.promotion');
    Route::get('/get-students-by-promotion/{promotion}', [SubventionController::class, 'getStudentsByPromotion']);
    Route::get('/get-students-by-promotion/{promotionId}', [SubventionController::class, 'getStudentsByPromotion'])->name('getStudentsByPromotion');
    Route::get('/subventions/students/{promotionId}', [SubventionController::class, 'getStudentsByPromotion']);
    Route::get('/evaluations/students-by-promotion/{promotionId}', [EvaluationController::class, 'getStudentsByPromotion']);
    Route::get('/get-students-by-promotion', [PromotionApprenantController::class, 'getStudentsByPromotion'])->name('get_students_by_promotion');
    Route::get('/get-students-by-specialization', [SpecializationController::class, 'getStudentsBySpecialization'])->name('get_students_by_specialization');
    Route::get('/student-statistics', [StudentStatisticsController::class, 'index'])->name('student.statistics.index');
    Route::post('/statistics/filter-matiers', [StatisticsController::class, 'filterMatiers'])->name('statistics.filter.matiers');
    Route::get('/get-students-by-promotion', [PromotionApprenantController::class, 'getStudentsByPromotion'])->name('get_students_by_promotion');
    Route::get('/entities/get-students-by-site', [EntityController::class, 'getStudentsBySite'])->name('entities.getStudentsBySite');
    Route::get('/get-students-by-promotion', [SalaryController::class, 'getStudentsByPromotion'])->name('get_students_by_promotion');
    // Dans web.php
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
});

// Admin routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('utilisateurs', UtilisateurController::class);
    Route::get('/export-users', [ExportImportController::class, 'export'])->name('export.users');
    Route::post('/import-users', [ExportImportController::class, 'import'])->name('import.users');
    Route::get('/export-users', [ExportImportController::class, 'export'])->name('export.users');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
});
