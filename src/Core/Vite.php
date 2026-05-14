<?php

namespace Marius\BasicForm\Core;

use RuntimeException;

class Vite
{
    public static function asset(string $entry): string
    {
        $publicDir = dirname(__DIR__, 2) . '/public';
        $hotFile   = $publicDir . '/hot';

        if (is_file($hotFile)) {
            $host = rtrim(trim((string) file_get_contents($hotFile)), '/');

            return sprintf(
                '<script type="module" src="%s/@vite/client"></script>'
                . '<script type="module" src="%s/%s"></script>',
                htmlspecialchars($host, ENT_QUOTES),
                htmlspecialchars($host, ENT_QUOTES),
                htmlspecialchars($entry, ENT_QUOTES)
            );
        }

        $manifestPath = $publicDir . '/build/.vite/manifest.json';
        if (!is_file($manifestPath)) {
            throw new RuntimeException(
                'Vite manifest not found. Run "npm run build" (or "npm run dev" with a public/hot file).'
            );
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        if (!is_array($manifest) || !isset($manifest[$entry])) {
            throw new RuntimeException(sprintf('Vite entry "%s" not found in manifest.', $entry));
        }

        $item = $manifest[$entry];
        $tags = sprintf(
            '<script type="module" src="/build/%s"></script>',
            htmlspecialchars($item['file'], ENT_QUOTES)
        );

        foreach ($item['css'] ?? [] as $css) {
            $tags .= sprintf(
                '<link rel="stylesheet" href="/build/%s">',
                htmlspecialchars($css, ENT_QUOTES)
            );
        }

        return $tags;
    }
}
