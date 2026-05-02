<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Thèmes site public + reflet dans l’admin (couleurs d’accent).
 * Palettes inspirées des tons [Flat UI Colors](https://flatuicolors.com/) (carotte, peter river, turquoise, etc.).
 */
final class SiteTheme
{
    public const DEFAULT = 'default';

    /** @return list<string> */
    public static function allowedIds(): array
    {
        return ['default', 'ocean', 'sunset', 'midnight', 'obsidian'];
    }

    public static function normalize(?string $id): string
    {
        $id = $id === null || $id === '' ? self::DEFAULT : $id;

        return in_array($id, self::allowedIds(), true) ? $id : self::DEFAULT;
    }

    /**
     * @return array<string, array{label: string, mode: string, palette: string, swatches: list<string>}>
     */
    public static function catalog(): array
    {
        return [
            'default' => [
                'label' => 'Ambre & rivage',
                'mode' => 'clair',
                'palette' => 'Type Flat UI — pumpkin, peter river',
                'swatches' => ['#ff7a18', '#3b82f6', '#f8fafc'],
            ],
            'ocean' => [
                'label' => 'Océan turquoise',
                'mode' => 'clair',
                'palette' => 'Type Flat UI — turquoise, belize hole',
                'swatches' => ['#1abc9c', '#2980b9', '#ecf0f1'],
            ],
            'sunset' => [
                'label' => 'Sunset corail',
                'mode' => 'clair',
                'palette' => 'Type Flat UI — alizarin, wisteria',
                'swatches' => ['#e74c3c', '#9b59b6', '#fdf2f8'],
            ],
            'midnight' => [
                'label' => 'Minuit ambré',
                'mode' => 'sombre',
                'palette' => 'Asphalte + sunflower (accent chaud)',
                'swatches' => ['#f39c12', '#3498db', '#1b2430'],
            ],
            'obsidian' => [
                'label' => 'Obsidienne rubis',
                'mode' => 'sombre',
                'palette' => 'Graphite + alizarin',
                'swatches' => ['#e74c3c', '#bdc3c7', '#121212'],
            ],
        ];
    }
}
