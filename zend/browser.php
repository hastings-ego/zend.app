<?php
declare(strict_types=1);

function zend_render_tree(string $dir, string $activePath = '', string $mode = 'browser', array $editedFiles = []): string
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
        $relativeNorm = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
        $activeNorm = str_replace(DIRECTORY_SEPARATOR, '/', $activePath);
        $isDir = is_dir($full);
        $active = $activeNorm === $relativeNorm ? 'active' : '';
        $editedStyle = in_array($relativeNorm, $editedFiles, true) ? ' style="color:green"' : '';
        $href = $mode === 'debug'
            ? '?page=debug&path=' . rawurlencode($relativeNorm)
            : ($isDir ? '?page=browser&path=' . rawurlencode($relativeNorm) : '?page=browser&file=' . rawurlencode($relativeNorm));

        if ($isDir) {
            $html .= '<div class="tree-item dir ' . $active . '">';
            $html .= '<a href="' . $href . '"' . $editedStyle . '>' . h($item) . '</a>';
            $html .= '<div class="tree-children">' . zend_render_tree($full, $activePath, $mode, $editedFiles) . '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="tree-item file ' . $active . '">';
            $html .= '<a href="' . $href . '"' . $editedStyle . '>' . h($item) . '</a>';
            $html .= '</div>';
        }
    }

    return $html;
}

function zend_render_breadcrumb(string $path, string $page): string
{
    $base = $page === 'debug' ? '?page=debug&path=' : '?page=browser&path=';
    $crumbs = [['label' => 'data', 'href' => $base]];
    $parts = array_values(array_filter(explode('/', trim($path, '/'))));
    $accum = '';

    foreach ($parts as $part) {
        $accum = $accum === '' ? $part : $accum . '/' . $part;
        $crumbs[] = ['label' => $part, 'href' => $base . rawurlencode($accum)];
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

function zend_render_browser_shell(
    string $page,
    string $currentPathRel,
    string $selectedFileRel,
    string $currentFile,
    string $editorValue,
    array $logStream,
    string $notice,
    string $error
): string {
    $treeActive = $currentFile !== '' && is_file($currentFile) ? relative_path($currentFile) : $currentPathRel;
    $editedFiles = $_SESSION['zend_edited_files'] ?? [];
    if (!is_array($editedFiles)) {
        $editedFiles = [];
    }
    $tree = zend_render_tree(ZEND_ROOT, $treeActive, $page, $editedFiles);

    $fileLabel = $selectedFileRel !== '' ? $selectedFileRel : 'No file open';
    $fileExt = $currentFile !== '' && is_file($currentFile) ? (pathinfo($currentFile, PATHINFO_EXTENSION) ?: 'file') : 'file';
    $fileSize = $currentFile !== '' && is_file($currentFile) ? number_format((int)filesize($currentFile)) . ' bytes' : '';
    ob_start();
    ?>
    <div class="editor-shell-vscode">
        <aside class="vscode-sidebar">
            <div class="sidebar-group">
                <div class="sidebar-title">Explorer</div>
                <div class="tree vscode-tree">
                    <?= $tree ?>
                </div>
            </div>

            <form method="post" class="complete-form">
                <input type="hidden" name="action" value="complete_theme">
                <button type="submit" class="ghost-btn ghost-block">Mark Site Complete</button>
            </form>
        </aside>

        <section class="vscode-main">
            <header class="editor-topbar">
                <div class="editor-topbar-left">
                    <div class="editor-filechip"><?= h($fileExt) ?></div>
                    <div>
                        <div class="editor-title"><?= h($fileLabel) ?></div>
                        <div class="editor-subtitle"><?= $currentFile !== '' && is_file($currentFile) ? h($fileSize) : 'Open a PHP file from the explorer' ?></div>
                    </div>
                </div>
                <div class="editor-topbar-right">
                    <span class="status-pill">Zend</span>
                    <span class="status-pill"><?= h($page) ?></span>
                </div>
            </header>

            <?php if ($notice !== ''): ?><div class="notice success"><?= h($notice) ?></div><?php endif; ?>
            <?php if ($error !== ''): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>

            <div class="editor-pane">
                <?php if ($currentFile !== '' && is_file($currentFile)): ?>
                    <form method="post" class="editor-shell editor-shell-vscode-form">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="file" value="<?= h(relative_path($currentFile)) ?>">
                        <div class="editor-frame">
                            <div class="line-gutter" aria-hidden="true">
                                <?php
                                $lineCount = max(1, substr_count($editorValue, "\n") + 1);
                                for ($line = 1; $line <= $lineCount; $line++):
                                ?>
                                    <span><?= $line ?></span>
                                <?php endfor; ?>
                            </div>
                            <textarea name="content" class="editor vscode-editor" spellcheck="false" wrap="off"><?= h($editorValue) ?></textarea>
                        </div>
                        <div class="editor-actions vscode-actions">
                            <div class="muted">PHP source in editable monospace view</div>
                            <button type="submit" class="ghost-btn">Save File</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="empty-state vscode-empty">Open a file from the explorer to start editing.</div>
                <?php endif; ?>
            </div>

            <div class="terminal-panel vscode-terminal">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Terminal</div>
                        <div class="muted">Recent admin activity</div>
                    </div>
                </div>
                <pre class="terminal"><?= h(implode("\n", $logStream)) ?></pre>
            </div>
        </section>
    </div>
    <?php
    return (string)ob_get_clean();
}
