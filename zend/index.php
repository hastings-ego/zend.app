<?php
declare(strict_types=1);

session_start();

const ZEND_ADMIN_USER = 'admin';
const ZEND_ADMIN_PASS = 'pass';
const ZEND_ROOT = __DIR__ . '/../data';

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_logged_in(): bool
{
    return !empty($_SESSION['zend_logged_in']);
}

function login_attempt(string $username, string $password): bool
{
    return hash_equals(ZEND_ADMIN_USER, $username) && hash_equals(ZEND_ADMIN_PASS, $password);
}

function relative_path(string $fullPath): string
{
    return ltrim(str_replace(ZEND_ROOT, '', $fullPath), DIRECTORY_SEPARATOR);
}

function normalize_path(string $relativePath): string
{
    $relativePath = trim(str_replace(["\0", '\\'], ['', '/'], $relativePath), '/');
    $candidate = realpath(ZEND_ROOT . ($relativePath !== '' ? '/' . $relativePath : ''));

    if ($candidate === false) {
        return ZEND_ROOT;
    }

    $root = realpath(ZEND_ROOT);
    if ($root === false || strncmp($candidate, $root, strlen($root)) !== 0) {
        return ZEND_ROOT;
    }

    return $candidate;
}

function build_tree(string $dir, string $selected = ''): string
{
    $items = @scandir($dir);
    if ($items === false) {
        return '';
    }

    $html = '';
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === 'log') {
            continue;
        }

        $full = $dir . DIRECTORY_SEPARATOR . $item;
        $relative = relative_path($full);
        $relativeUrl = rawurlencode(str_replace(DIRECTORY_SEPARATOR, '/', $relative));
        $isDir = is_dir($full);
        $selectedNorm = str_replace(DIRECTORY_SEPARATOR, '/', $selected);
        $relativeNorm = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
        $active = $selectedNorm === $relativeNorm ? 'active' : '';

        if ($isDir) {
            $html .= '<div class="tree-item dir ' . $active . '">';
            $html .= '<a href="?path=' . $relativeUrl . '">' . h($item) . '</a>';
            $html .= '<div class="tree-children">';
            $html .= build_tree($full, $selected);
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="tree-item file ' . $active . '">';
            $html .= '<a href="?file=' . $relativeUrl . '">' . h($item) . '</a>';
            $html .= '</div>';
        }
    }

    return $html;
}

function file_kind(string $path): string
{
    if (is_dir($path)) {
        return 'dir';
    }

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return $ext !== '' ? $ext : 'file';
}

function render_breadcrumb(string $path): string
{
    $root = ['label' => 'data', 'href' => '?path='];
    $crumbs = [$root];
    $parts = array_values(array_filter(explode('/', trim($path, '/'))));
    $accum = '';

    foreach ($parts as $part) {
        $accum = $accum === '' ? $part : $accum . '/' . $part;
        $crumbs[] = ['label' => $part, 'href' => '?path=' . rawurlencode($accum)];
    }

    $out = '';
    foreach ($crumbs as $index => $crumb) {
        if ($index > 0) {
            $out .= '<span class="crumb-sep">/</span>';
        }
        $out .= '<a href="' . $crumb['href'] . '">' . h($crumb['label']) . '</a>';
    }

    return $out;
}

if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    session_destroy();
    header('Location: /zend/');
    exit;
}

$loginError = '';
if (!is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (login_attempt($username, $password)) {
        session_regenerate_id(true);
        $_SESSION['zend_logged_in'] = true;
        $_SESSION['zend_user'] = $username;
        header('Location: /zend/');
        exit;
    }

    $loginError = 'Invalid credentials.';
}

$saveNotice = '';
$saveError = '';
$selectedPath = $_GET['path'] ?? '';
$selectedFile = $_GET['file'] ?? '';
$currentPath = normalize_path((string)$selectedPath);
$currentPathRel = relative_path($currentPath);
$currentFile = '';
$fileContents = '';
$fileError = '';

if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    $target = normalize_path((string)($_POST['file'] ?? ''));
    $content = (string)($_POST['content'] ?? '');

    if ($target === ZEND_ROOT || !is_file($target) || !is_writable($target)) {
        $saveError = 'This file cannot be saved.';
        $currentFile = $target;
    } else {
        $bytes = file_put_contents($target, $content);
        if ($bytes === false) {
            $saveError = 'Save failed.';
            $currentFile = $target;
        } else {
            $saveNotice = 'Saved successfully.';
            $currentFile = $target;
            $selectedFile = relative_path($target);
            $fileContents = $content;
            $currentPath = dirname($target);
            $currentPathRel = relative_path($currentPath);
        }
    }
}

if ($selectedFile !== '') {
    $currentFile = normalize_path((string)$selectedFile);
    if (is_file($currentFile) && is_readable($currentFile)) {
        $fileContents = (string)file_get_contents($currentFile);
        $currentPath = dirname($currentFile);
        $currentPathRel = relative_path($currentPath);
    } else {
        $fileError = 'Unable to open file.';
    }
}

$tree = build_tree(ZEND_ROOT, $currentPathRel);
$entries = @scandir($currentPath) ?: [];
$selectedFileRel = $currentFile !== '' ? relative_path($currentFile) : '';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zend Admin</title>
    <link rel="stylesheet" href="/zend/style.css">
</head>
<body class="app-shell">
<?php if (!is_logged_in()): ?>
    <main class="auth-wrap">
        <section class="auth-card">
            <div class="auth-kicker">Zend Admin Panel</div>
            <h1>Login</h1>
            <p>Access the secure file browser for `/data/`.</p>
            <?php if ($loginError !== ''): ?>
                <div class="notice error"><?= h($loginError) ?></div>
            <?php endif; ?>
            <form method="post" class="auth-form">
                <input type="hidden" name="action" value="login">
                <label>
                    <span>Username</span>
                    <input type="text" name="username" autocomplete="username" required>
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" autocomplete="current-password" required>
                </label>
                <button type="submit">Sign In</button>
            </form>
        </section>
    </main>
<?php else: ?>
    <header class="topbar">
        <div>
            <div class="auth-kicker">Zend Admin Panel</div>
            <h1>File Browser</h1>
            <div class="crumbs"><?= render_breadcrumb($currentPathRel) ?></div>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="ghost-btn">Logout</button>
        </form>
    </header>

    <main class="workspace">
        <aside class="sidebar">
            <div class="panel-title">Explorer</div>
            <div class="tree">
                <?= $tree ?>
            </div>
        </aside>

        <section class="content">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Open File</div>
                        <div class="muted"><?= $selectedFileRel !== '' ? h($selectedFileRel) : 'Select a file to edit' ?></div>
                    </div>
                </div>
                <?php if ($saveNotice !== ''): ?>
                    <div class="notice success"><?= h($saveNotice) ?></div>
                <?php endif; ?>
                <?php if ($saveError !== ''): ?>
                    <div class="notice error"><?= h($saveError) ?></div>
                <?php elseif ($fileError !== ''): ?>
                    <div class="notice error"><?= h($fileError) ?></div>
                <?php endif; ?>

                <?php if ($currentFile !== '' && is_file($currentFile)): ?>
                    <form method="post" class="editor-shell">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="file" value="<?= h(relative_path($currentFile)) ?>">
                        <div class="editor-meta">
                            <span><?= h(file_kind($currentFile)) ?></span>
                            <span><?= filesize($currentFile) !== false ? number_format((int)filesize($currentFile)) . ' bytes' : '' ?></span>
                        </div>
                        <textarea name="content" class="editor" spellcheck="false"><?= h($fileContents) ?></textarea>
                        <div class="editor-actions">
                            <button type="submit" class="ghost-btn">Save File</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="empty-state">Open a file from the explorer to start editing.</div>
                <?php endif; ?>
            </div>
        </section>
    </main>
<?php endif; ?>
</body>
</html>
