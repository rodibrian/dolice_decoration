<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Core\DB;

final class HomeController extends BaseController
{
    public function index(): void
    {
        $services = Service::published(6);
        $projects = Project::published(6);
        $posts = Post::published(3);

        // Testimonials approved (lightweight query here for v1)
        $pdo = DB::pdo();
        $stmt = $pdo->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY id DESC LIMIT 6");
        $testimonials = $stmt->fetchAll();

        $this->view('home.index', [
            'title' => 'Dolice Decoration',
            'services' => $services,
            'projects' => $projects,
            'posts' => $posts,
            'testimonials' => $testimonials,
            'settings' => Setting::allKeyed(),
        ]);
    }
}
