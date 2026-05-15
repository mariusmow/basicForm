<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page not found — Contact Portal</title>
    <style>
        :root {
            --bg: #f9fafb;
            --surface: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --ring: #e5e7eb;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            line-height: 1.5;
        }
        .wrap {
            max-width: 28rem;
            width: 100%;
            text-align: center;
        }
        .card {
            background: var(--surface);
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 10px 40px -12px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--ring);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1.5rem;
            border-radius: 1rem;
            background: linear-gradient(145deg, #eff6ff 0%, #dbeafe 100%);
            color: var(--accent);
        }
        .badge svg {
            width: 2.5rem;
            height: 2.5rem;
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: clamp(2.5rem, 8vw, 3.25rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.1;
            color: var(--text);
        }
        .subtitle {
            margin: 0 0 1.25rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
        }
        p {
            margin: 0 0 1.75rem;
            font-size: 0.9375rem;
            color: var(--muted);
        }
        .path {
            font-size: 0.8125rem;
            font-family: ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, monospace;
            color: #9ca3af;
            word-break: break-all;
            margin-bottom: 1.75rem;
            padding: 0.5rem 0.75rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
            border: 1px solid var(--ring);
        }
        a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
            background: var(--accent);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: background 0.15s ease, transform 0.15s ease;
        }
        a:hover {
            background: var(--accent-hover);
        }
        a:active {
            transform: scale(0.98);
        }
        a svg {
            width: 1.125rem;
            height: 1.125rem;
        }
        footer {
            margin-top: 2rem;
            font-size: 0.8125rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>404</h1>
            <p class="subtitle">This page doesn’t exist</p>
            <p>The link may be broken, or the page may have been removed. Check the URL or head back to the contact portal.</p>
            <?php
            $requested = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            if (is_string($requested) && $requested !== '') {
                echo '<p class="path" title="Requested path">' . htmlspecialchars($requested, ENT_QUOTES, 'UTF-8') . '</p>';
            }
            ?>
            <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Back to home
            </a>
        </div>
        <footer>Contact Portal</footer>
    </div>
</body>
</html>
