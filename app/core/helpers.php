<?php
declare(strict_types=1);

/** Escape output untuk mencegah XSS. WAJIB dipakai di semua view. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Redirect ke page tertentu lalu hentikan eksekusi. */
function redirect_to(string $page): void
{
    header('Location: index.php?page=' . urlencode($page));
    exit;
}

/** Render view dengan data terbatas. */
function view(string $path, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require BASE_PATH . '/app/views/' . $path . '.php';
}
