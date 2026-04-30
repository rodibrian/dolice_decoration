<?php
declare(strict_types=1);

namespace App\Core;

use App\Controllers\HomeController;
use App\Controllers\ServicesPublicController;
use App\Controllers\ProjectsPublicController;
use App\Controllers\BlogController;
use App\Controllers\PagesPublicController;
use App\Controllers\QuotesPublicController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ServicesController;
use App\Controllers\Admin\ProjectsController;
use App\Controllers\Admin\PostsController;
use App\Controllers\Admin\TestimonialsController;
use App\Controllers\Admin\QuotesController;
use App\Controllers\Admin\MessagesController;
use App\Controllers\Admin\PartnersController;
use App\Controllers\Admin\PagesController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\HeroSlidesController;

final class App
{
    public function run(): void
    {
        $router = new Router();

        // Public
        $router->get('/', [HomeController::class, 'index']);
        $router->get('/services', [ServicesPublicController::class, 'index']);
        $router->get('/services/{slug}', [ServicesPublicController::class, 'show']);

        $router->get('/realisations', [ProjectsPublicController::class, 'index']);
        $router->get('/realisations/{slug}', [ProjectsPublicController::class, 'show']);

        $router->get('/blog', [BlogController::class, 'index']);
        $router->get('/blog/{slug}', [BlogController::class, 'show']);

        $router->get('/notre-histoire', [PagesPublicController::class, 'show']);
        $router->get('/faq', [PagesPublicController::class, 'show']);
        $router->get('/contact', [PagesPublicController::class, 'show']);
        $router->post('/contact', [PagesPublicController::class, 'contactSubmit']);

        $router->get('/devis', [QuotesPublicController::class, 'showForm']);
        $router->post('/devis', [QuotesPublicController::class, 'submit']);

        // Admin auth
        $router->get('/admin/login', [AuthController::class, 'showLogin']);
        $router->post('/admin/login', [AuthController::class, 'login']);
        $router->post('/admin/logout', [AuthController::class, 'logout']);

        // Admin
        $router->get('/admin', [DashboardController::class, 'index']);

        // Admin - Services
        $router->get('/admin/services', [ServicesController::class, 'index']);
        $router->get('/admin/services/create', [ServicesController::class, 'create']);
        $router->post('/admin/services/store', [ServicesController::class, 'store']);
        $router->get('/admin/services/edit', [ServicesController::class, 'edit']);
        $router->post('/admin/services/update', [ServicesController::class, 'update']);
        $router->post('/admin/services/delete', [ServicesController::class, 'delete']);

        // Admin - Réalisations
        $router->get('/admin/projects', [ProjectsController::class, 'index']);
        $router->get('/admin/projects/create', [ProjectsController::class, 'create']);
        $router->post('/admin/projects/store', [ProjectsController::class, 'store']);
        $router->get('/admin/projects/edit', [ProjectsController::class, 'edit']);
        $router->post('/admin/projects/update', [ProjectsController::class, 'update']);
        $router->post('/admin/projects/delete', [ProjectsController::class, 'delete']);
        $router->post('/admin/projects/images/delete', [ProjectsController::class, 'deleteImage']);

        // Admin - Articles
        $router->get('/admin/posts', [PostsController::class, 'index']);
        $router->get('/admin/posts/create', [PostsController::class, 'create']);
        $router->post('/admin/posts/store', [PostsController::class, 'store']);
        $router->get('/admin/posts/edit', [PostsController::class, 'edit']);
        $router->post('/admin/posts/update', [PostsController::class, 'update']);
        $router->post('/admin/posts/delete', [PostsController::class, 'delete']);

        // Admin - Témoignages
        $router->get('/admin/testimonials', [TestimonialsController::class, 'index']);
        $router->get('/admin/testimonials/create', [TestimonialsController::class, 'create']);
        $router->post('/admin/testimonials/store', [TestimonialsController::class, 'store']);
        $router->get('/admin/testimonials/edit', [TestimonialsController::class, 'edit']);
        $router->post('/admin/testimonials/update', [TestimonialsController::class, 'update']);
        $router->post('/admin/testimonials/approve', [TestimonialsController::class, 'approve']);
        $router->post('/admin/testimonials/delete', [TestimonialsController::class, 'delete']);

        // Admin - Devis
        $router->get('/admin/quotes', [QuotesController::class, 'index']);
        $router->get('/admin/quotes/show', [QuotesController::class, 'show']);
        $router->post('/admin/quotes/update', [QuotesController::class, 'update']);

        // Admin - Messages
        $router->get('/admin/messages', [MessagesController::class, 'index']);
        $router->get('/admin/messages/show', [MessagesController::class, 'show']);
        $router->post('/admin/messages/status', [MessagesController::class, 'updateStatus']);

        // Admin - Partenaires
        $router->get('/admin/partners', [PartnersController::class, 'index']);
        $router->get('/admin/partners/create', [PartnersController::class, 'create']);
        $router->post('/admin/partners/store', [PartnersController::class, 'store']);
        $router->get('/admin/partners/edit', [PartnersController::class, 'edit']);
        $router->post('/admin/partners/update', [PartnersController::class, 'update']);
        $router->post('/admin/partners/delete', [PartnersController::class, 'delete']);

        // Admin - Slides accueil
        $router->get('/admin/hero-slides', [HeroSlidesController::class, 'index']);
        $router->get('/admin/hero-slides/create', [HeroSlidesController::class, 'create']);
        $router->post('/admin/hero-slides/store', [HeroSlidesController::class, 'store']);
        $router->get('/admin/hero-slides/edit', [HeroSlidesController::class, 'edit']);
        $router->post('/admin/hero-slides/update', [HeroSlidesController::class, 'update']);
        $router->post('/admin/hero-slides/delete', [HeroSlidesController::class, 'delete']);

        // Admin - Pages
        $router->get('/admin/pages', [PagesController::class, 'index']);
        $router->get('/admin/pages/edit', [PagesController::class, 'edit']);
        $router->post('/admin/pages/update', [PagesController::class, 'update']);

        // Admin - Settings
        $router->get('/admin/settings', [SettingsController::class, 'index']);
        $router->post('/admin/settings/update', [SettingsController::class, 'update']);

        $router->dispatch();
    }
}
