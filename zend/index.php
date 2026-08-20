<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/browser.php';

const ZEND_ADMIN_USER = 'admin';
const ZEND_ADMIN_PASS = 'pass';
const ZEND_ROOT = __DIR__ . '/../data';
const ZEND_LOG_LIMIT = 200;
const ZEND_EDITED_TRACKER = __DIR__ . '/edited-files.json';

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
    $root = realpath(ZEND_ROOT);
    if ($root === false) {
        return ZEND_ROOT;
    }

    $raw = trim((string)$relativePath, " \t\n\r\0\x0B");
    if ($raw === '') {
        return $root;
    }

    $candidate = str_replace(["\0", '\\'], ['', '/'], $raw);
    if (strpos($candidate, $root) === 0) {
        $candidate = $candidate;
    } else {
        $candidate = $root . '/' . ltrim($candidate, '/');
    }

    $resolved = realpath($candidate);
    if ($resolved !== false) {
        $candidate = $resolved;
    }

    if (strncmp($candidate, $root, strlen($root)) !== 0) {
        return $root;
    }

    return $candidate;
}

function resolve_file_target(string $submittedFile): string
{
    $candidates = [
        $submittedFile,
        (string)($_POST['file'] ?? ''),
        (string)($_GET['file'] ?? ''),
        (string)($_POST['path'] ?? ''),
        (string)($_GET['path'] ?? ''),
    ];

    foreach ($candidates as $candidate) {
        if ($candidate === '') {
            continue;
        }

        $normalized = normalize_path($candidate);
        if ($normalized !== ZEND_ROOT && is_file($normalized)) {
            return $normalized;
        }
    }

    return ZEND_ROOT;
}

function current_page(): string
{
    $page = (string)($_GET['page'] ?? 'home');
    return in_array($page, ['home', 'browser', 'debug'], true) ? $page : 'home';
}

function nav_link(string $page, string $label, string $currentPage): string
{
    $class = $page === $currentPage ? 'nav-link active' : 'nav-link';
    return '<a class="' . $class . '" href="?page=' . h($page) . '">' . h($label) . '</a>';
}

function append_log(string $message): void
{
    if (!isset($_SESSION['zend_log']) || !is_array($_SESSION['zend_log'])) {
        $_SESSION['zend_log'] = [];
    }

    $line = '[' . date('H:i:s') . '] ' . $message;
    $_SESSION['zend_log'][] = $line;
    if (count($_SESSION['zend_log']) > ZEND_LOG_LIMIT) {
        $_SESSION['zend_log'] = array_slice($_SESSION['zend_log'], -ZEND_LOG_LIMIT);
    }
}

function load_edited_files(): array
{
    if (!is_file(ZEND_EDITED_TRACKER)) {
        return [];
    }

    $raw = (string)@file_get_contents(ZEND_EDITED_TRACKER);
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [];
    }

    $files = [];
    foreach ($data as $item) {
        $item = trim((string)$item);
        if ($item === '') {
            continue;
        }

        $normalized = $item;
        $marker = '/data/';
        $pos = strrpos($normalized, $marker);
        if ($pos !== false) {
            $normalized = substr($normalized, $pos + strlen($marker));
        }

        $normalized = ltrim(str_replace(['\\', "\0"], ['/', ''], $normalized), '/');
        if ($normalized !== '') {
            $files[] = $normalized;
        }
    }

    $files = array_values(array_unique($files));
    save_edited_files($files);
    return $files;
}

function save_edited_files(array $files): void
{
    $files = array_values(array_unique(array_filter(array_map(static function ($file): string {
        $file = trim((string)$file);
        if ($file === '') {
            return '';
        }

        $marker = '/data/';
        $pos = strrpos($file, $marker);
        if ($pos !== false) {
            $file = substr($file, $pos + strlen($marker));
        }

        return ltrim(str_replace(['\\', "\0"], ['/', ''], $file), '/');
    }, $files))));
    @file_put_contents(ZEND_EDITED_TRACKER, json_encode($files, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function run_php_script(string $file): array
{
    $descriptor = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($file);
    $process = proc_open($cmd, $descriptor, $pipes, dirname($file));
    if (!is_resource($process)) {
        return ['code' => 1, 'output' => 'Unable to start PHP process.'];
    }

    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $code = proc_close($process);

    return [
        'code' => $code,
        'output' => trim($stdout . "\n" . $stderr),
    ];
}

function run_php_code(string $code): array
{
    $code = preg_replace('/^\s*<\?(php)?/i', '', $code) ?? $code;
    $tmpFile = tempnam(sys_get_temp_dir(), 'zend_');
    if ($tmpFile === false) {
        return ['code' => 1, 'output' => 'Unable to create temp file.'];
    }

    $script = "<?php\n" . $code;
    file_put_contents($tmpFile, $script);
    $result = run_php_script($tmpFile);
    @unlink($tmpFile);

    return $result;
}

if (!isset($_SESSION['zend_log']) || !is_array($_SESSION['zend_log'])) {
    $_SESSION['zend_log'] = [];
}
if (!isset($_SESSION['zend_debug_result']) || !is_string($_SESSION['zend_debug_result'])) {
    $_SESSION['zend_debug_result'] = '';
}
if (!isset($_SESSION['zend_selected_file']) || !is_string($_SESSION['zend_selected_file'])) {
    $_SESSION['zend_selected_file'] = '';
}
$_SESSION['zend_edited_files'] = load_edited_files();

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
        append_log('Login successful for ' . $username);
        header('Location: /zend/');
        exit;
    }

    $loginError = 'Invalid credentials.';
    append_log('Failed login attempt for ' . $username);
}

$page = current_page();
$path = (string)($_GET['path'] ?? '');
$file = (string)($_GET['file'] ?? $_SESSION['zend_selected_file'] ?? '');
$notice = '';
$error = '';
$debugCode = '';
$logStream = $_SESSION['zend_log'];
$debugResult = $_SESSION['zend_debug_result'];

if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'complete_theme') {
    save_edited_files([]);
    $_SESSION['zend_edited_files'] = [];
    $_SESSION['zend_selected_file'] = '';
    $_SESSION['zend_debug_result'] = '';
    append_log('Theme marked complete. Edited file guide reset.');
    $notice = 'Theme marked complete. Edited file guide reset.';
    $page = 'browser';
}

if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    $submittedFile = (string)($_POST['file'] ?? $_SESSION['zend_selected_file'] ?? '');
    $target = resolve_file_target($submittedFile);
    $content = (string)($_POST['content'] ?? '');
    $targetRel = relative_path($target);

    if ($target === ZEND_ROOT || !is_file($target) || !is_dir(dirname($target))) {
        $error = 'This file cannot be saved.';
        append_log('Save failed: ' . ($targetRel !== '' ? $targetRel : '(no file selected)'));
    } else {
        $bytes = @file_put_contents($target, $content);
        if ($bytes === false) {
            $last = error_get_last();
            $error = 'Save failed. Check file permissions and ownership.';
            append_log('Save failed: ' . $targetRel . ' (' . ($last['message'] ?? 'unknown error') . ')');
        } else {
            $notice = 'Saved successfully.';
            append_log('Saved file: ' . $targetRel);
            $file = $targetRel;
            $path = dirname($file);
            $_SESSION['zend_selected_file'] = $file;
            $_SESSION['zend_edited_files'][] = $targetRel;
            $_SESSION['zend_edited_files'] = array_values(array_unique($_SESSION['zend_edited_files']));
            save_edited_files($_SESSION['zend_edited_files']);
            $page = 'browser';
        }
    }
}

if (is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'run') {
    $debugCode = (string)($_POST['code'] ?? '');
    if (trim($debugCode) === '') {
        $error = 'Paste PHP code to run.';
        $_SESSION['zend_debug_result'] = "No code provided.";
    } else {
        $result = run_php_code($debugCode);
        $output = trim($result['output']);
        $_SESSION['zend_debug_result'] = $output !== ''
            ? $output
            : '(no output)';
        $notice = 'Script executed.';
        $page = 'debug';
        $debugResult = $_SESSION['zend_debug_result'];
    }
}

$currentPath = normalize_path($path);
$currentPathRel = relative_path($currentPath);
$currentFile = $file !== '' ? normalize_path($file) : '';
if ($page === 'debug' && $currentFile === ZEND_ROOT && $path !== '') {
    $maybeFile = normalize_path($path);
    if (is_file($maybeFile)) {
        $currentFile = $maybeFile;
    }
}
if ($currentFile !== '' && is_file($currentFile)) {
    $currentPath = dirname($currentFile);
    $currentPathRel = relative_path($currentPath);
    $_SESSION['zend_selected_file'] = relative_path($currentFile);
}

$selectedFileRel = $currentFile !== '' && is_file($currentFile) ? relative_path($currentFile) : $_SESSION['zend_selected_file'];
$editorValue = '';
if ($currentFile !== '' && is_file($currentFile) && is_readable($currentFile)) {
    $editorValue = (string)file_get_contents($currentFile);
}

if ($page === 'debug' && $debugCode === '') {
    $debugCode = "// Paste PHP here\n";
}

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
            <p>Access the secure admin workspace.</p>
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
            <h1><?= $page === 'debug' ? 'PHP Debugger' : ($page === 'browser' ? 'File Browser' : 'Menu') ?></h1>
            <?php if ($page === 'browser' || $page === 'debug'): ?>
                <div class="crumbs"><?= zend_render_breadcrumb($currentPathRel, $page) ?></div>
            <?php endif; ?>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="ghost-btn">Logout</button>
        </form>
    </header>

    <main class="workspace">
        <?php if ($page === 'home'): ?>
            <aside class="sidebar">
                <div class="panel-title">Menu</div>
                <nav class="menu-nav">
                    <?= nav_link('home', 'Home', $page) ?>
                    <?= nav_link('browser', 'File Browser', $page) ?>
                    <?= nav_link('debug', 'PHP Debugger', $page) ?>
                </nav>
            </aside>
            <section class="content">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Dashboard</div>
                            <div class="muted">Choose a tool below.</div>
                        </div>
                    </div>
                    <div class="home-grid">
                        <a class="home-card" href="?page=browser">
                            <span class="home-card-title">File Browser</span>
                            <span class="home-card-copy">Browse and edit files inside `/data/`.</span>
                        </a>
                        <a class="home-card" href="?page=debug">
                            <span class="home-card-title">PHP Debugger</span>
                            <span class="home-card-copy">Run PHP scripts and capture output in the terminal log.</span>
                        </a>
                    </div>
                </div>
            </section>
        <?php elseif ($page === 'browser'): ?>
            <?= zend_render_browser_shell($page, $currentPathRel, $selectedFileRel, $currentFile, $editorValue, $logStream, $notice, $error) ?>
        <?php elseif ($page === 'debug'): ?>
            <aside class="sidebar">
                <div class="panel-title">Menu</div>
                <nav class="menu-nav">
                    <?= nav_link('home', 'Home', $page) ?>
                    <?= nav_link('browser', 'File Browser', $page) ?>
                    <?= nav_link('debug', 'PHP Debugger', $page) ?>
                </nav>
            </aside>
            <section class="content">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Debugger</div>
                            <div class="muted">Run a PHP file from `/data/`.</div>
                        </div>
                    </div>
                    <?php if ($notice !== ''): ?><div class="notice success"><?= h($notice) ?></div><?php endif; ?>
                    <?php if ($error !== ''): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
                    <form method="post" class="debug-form">
                        <input type="hidden" name="action" value="run">
                        <div class="debug-row">
                            <div class="debug-field">
                                <label>PHP code</label>
                                <textarea name="code" class="editor debug-input" spellcheck="false" placeholder="// Paste PHP code here"><?= h($debugCode) ?></textarea>
                            </div>
                            <button type="submit" class="ghost-btn">Run Script</button>
                        </div>
                    </form>
                    <div class="debug-pane">
                        <div class="panel-title">Terminal</div>
                        <pre class="terminal"><?= h($debugResult) ?></pre>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
<?php endif; ?>
</body>
</html>
