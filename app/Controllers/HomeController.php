<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\HeroSlide;

final class HomeController extends BaseController
{
    public function index(): void
    {
        $services = Service::published(6);
        $projects = Project::published(6);
        $projectImageMap = Project::firstImagesByProjectIds(array_map(
            static fn (array $p): int => (int)($p['id'] ?? 0),
            $projects
        ));
        $projects = array_map(
            static function (array $project) use ($projectImageMap): array {
                $id = (int)($project['id'] ?? 0);
                $project['cover_image'] = $projectImageMap[$id] ?? null;
                return $project;
            },
            $projects
        );
        $posts = Post::published(3);

        $testimonials = Testimonial::approved(12);
        $heroSlides = HeroSlide::published(8);

        $this->view('home.index', [
            'title' => 'Dolice Decoration',
            'services' => $services,
            'projects' => $projects,
            'posts' => $posts,
            'testimonials' => $testimonials,
            'heroSlides' => $heroSlides,
            'settings' => Setting::allKeyed(),
        ]);
    }
}
