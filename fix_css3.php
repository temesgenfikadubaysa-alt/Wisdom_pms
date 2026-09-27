<?php
$file = 'C:/xampp/htdocs/wisdom/resources/css/style.css';
$content = file_get_contents($file);
$lines = explode("\n", $content);

// Find the COLLAPSE section and replace it with proper CSS
$start = null;
for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], '/* COLLAPSE / TOGGLE */') !== false) {
        $start = $i;
        break;
    }
}
if ($start !== null) {
    // Find end of collapse section (next /* comment */)
    $end = null;
    for ($i = $start + 1; $i < count($lines); $i++) {
        if (strpos($lines[$i], '/* ') !== false && $i > $start) {
            $end = $i;
            break;
        }
    }
    if ($end === null) $end = count($lines);
    
    $newCSS = "/* COLLAPSE / TOGGLE */\n"
        . ".dash-section { background: var(--white); border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 16px; border: 1px solid var(--gray-200); overflow: hidden; }\n"
        . ".dash-section-head { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; cursor: pointer; background: var(--gray-100); border-bottom: 1px solid var(--gray-200); transition: background 0.15s; user-select: none; }\n"
        . ".dash-section-head:hover { background: var(--gray-200); }\n"
        . ".dash-section-head-left { display: flex; align-items: center; gap: 8px; }\n"
        . ".dash-section-chevron { width: 18px; height: 18px; color: var(--gray-500); transition: transform 0.25s; flex-shrink: 0; }\n"
        . ".dash-section.collapsed .dash-section-chevron { transform: rotate(-90deg); }\n"
        . ".dash-section-title { margin: 0; font-size: 0.95rem; color: var(--blue); font-weight: 700; }\n"
        . ".dash-section-badge { font-size: 0.72rem; background: var(--blue); color: var(--white); padding: 2px 8px; border-radius: 10px; font-weight: 600; }\n"
        . ".dash-section-head-right { display: flex; align-items: center; gap: 6px; }\n"
        . ".dash-section-body { padding: 16px; }\n"
        . ".dash-section.collapsed .dash-section-body { display: none; }\n"
        . ".collapse-toggle { width: 28px; height: 28px; border: 1px solid var(--gray-300); border-radius: 6px; background: var(--white); color: var(--gray-500); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; flex-shrink: 0; padding: 0; margin-left: 4px; }\n"
        . ".collapse-toggle:hover { background: var(--gray-100); border-color: var(--gray-400); color: var(--gray-700); }\n"
        . ".collapse-toggle svg { width: 16px; height: 16px; transition: transform 0.25s; }\n"
        . ".dash-section.collapsed .collapse-toggle svg { transform: rotate(-90deg); }\n"
        . ".view-toggle { display: flex; gap: 2px; }\n"
        . ".view-toggle-btn { padding: 4px 10px; font-size: 0.72rem; background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: 4px; cursor: pointer; color: var(--gray-500); transition: all 0.15s; display: inline-flex; align-items: center; gap: 3px; }\n"
        . ".view-toggle-btn svg { width: 12px; height: 12px; }\n"
        . ".view-toggle-btn.active { background: var(--blue); color: var(--white); border-color: var(--blue); }\n"
        . ".tableView { display: block; }\n"
        . ".cardView { display: none; }\n"
        . ".tableView.active { display: block; }\n"
        . ".cardView.active { display: block; }\n"
        . "@keyframes dashFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }\n"
        . ".dash-section { animation: dashFadeIn 0.3s ease; }\n"
        . "@media (max-width: 768px) { .dash-section-head { padding: 12px; } .dash-section-body { padding: 12px; } .dash-section-title { font-size: 0.85rem; } }";
    
    array_splice($lines, $start, $end - $start, explode("\n", $newCSS));
    file_put_contents($file, implode("\n", $lines));
    echo "Done\n";
} else {
    echo "Not found\n";
}
