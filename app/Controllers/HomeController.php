<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Locale;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Translation;

final class HomeController extends BaseController
{
    public function index(): void
    {
        $services = array_map(static function (array $row): array {
            $id = (int)($row['id'] ?? 0);

            return $id > 0 ? Translation::mergeRow('service', $id, $row, ['title', 'description', 'category', 'price_label', 'price_unit']) : $row;
        }, Service::published(6));

        $projects = Project::published(6);
        $projectImageMap = Project::firstImagesByProjectIds(array_map(
            static fn (array $p): int => (int)($p['id'] ?? 0),
            $projects
        ));
        $projects = array_map(
            static function (array $project) use ($projectImageMap): array {
                $id = (int)($project['id'] ?? 0);
                $project['cover_image'] = $projectImageMap[$id] ?? null;
                if ($id > 0) {
                    $project = Translation::mergeRow('project', $id, $project, ['title', 'description', 'location', 'category', 'work_type']);
                }

                return $project;
            },
            $projects
        );

        $posts = array_map(static function (array $row): array {
            $id = (int)($row['id'] ?? 0);

            return $id > 0 ? Translation::mergeRow('post', $id, $row, ['title', 'excerpt', 'content', 'author', 'keywords']) : $row;
        }, Post::published(3));

        $testimonials = array_map(static function (array $row): array {
            $id = (int)($row['id'] ?? 0);

            return $id > 0 ? Translation::mergeRow('testimonial', $id, $row, ['client_name', 'client_company', 'content']) : $row;
        }, Testimonial::approved(12));

        $heroSlides = array_map(static function (array $row): array {
            $id = (int)($row['id'] ?? 0);

            return $id > 0 ? Translation::mergeRow('hero_slide', $id, $row, ['title', 'subtitle', 'cta_label']) : $row;
        }, HeroSlide::published(8));

        $settings = Setting::allKeyed();
        if (Locale::current() !== Locale::DEFAULT && Translation::tableReady()) {
            foreach (Translation::mapFor('site', 0, Locale::current()) as $field => $val) {
                if (trim((string)$val) !== '') {
                    $settings[$field] = $val;
                }
            }
        }

        $this->view('home.index', [
            'title' => t('meta.site_brand'),
            'services' => $services,
            'projects' => $projects,
            'posts' => $posts,
            'testimonials' => $testimonials,
            'heroSlides' => $heroSlides,
            'settings' => $settings,
        ]);
    }
}
