<?php
// One-off helper: convert docs/payment-structure.md to a self-contained, styled HTML
// that prints cleanly via headless Chrome/Edge.
//
// Usage:  php tools/md_to_html.php docs/payment-structure.md docs/payment-structure.html

require __DIR__ . '/../vendor/autoload.php';

use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Environment\Environment;

$src  = $argv[1] ?? __DIR__ . '/../docs/payment-structure.md';
$dst  = $argv[2] ?? __DIR__ . '/../docs/payment-structure.html';
$lang = (stripos(basename($src), '-fr') !== false) ? 'fr' : 'en';

$markdown = file_get_contents($src);
if ($markdown === false) {
    fwrite(STDERR, "Cannot read $src\n");
    exit(1);
}

$config = [
    'html_input'         => 'allow',
    'allow_unsafe_links' => false,
];

$env = new Environment($config);
$env->addExtension(new CommonMarkCoreExtension());
$env->addExtension(new GithubFlavoredMarkdownExtension());
$env->addExtension(new TableExtension());

$converter = new MarkdownConverter($env);
$bodyHtml  = (string) $converter->convert($markdown);

$css = <<<'CSS'
@page { size: A4; margin: 18mm 16mm 18mm 16mm; }
* { box-sizing: border-box; }
html, body { font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif; color: #1f2328; line-height: 1.5; font-size: 11pt; }
body { max-width: 820px; margin: 0 auto; padding: 0 4px; }
h1, h2, h3, h4 { color: #0b3d91; line-height: 1.25; page-break-after: avoid; }
h1 { font-size: 22pt; border-bottom: 3px solid #0b3d91; padding-bottom: 6px; margin-top: 0; }
h2 { font-size: 16pt; border-bottom: 1px solid #c9d4e3; padding-bottom: 4px; margin-top: 28px; }
h3 { font-size: 13pt; margin-top: 22px; }
h4 { font-size: 11.5pt; margin-top: 18px; }
p, li { font-size: 10.5pt; }
blockquote { border-left: 4px solid #0b3d91; background: #f4f7fb; margin: 12px 0; padding: 8px 14px; color: #243447; }
code { font-family: "Consolas", "Cascadia Mono", "Courier New", monospace; background: #f1f3f5; padding: 1px 4px; border-radius: 3px; font-size: 9.8pt; }
pre { background: #0f172a; color: #e2e8f0; padding: 12px 14px; border-radius: 6px; overflow-x: auto; font-size: 9.5pt; line-height: 1.45; page-break-inside: avoid; }
pre code { background: transparent; color: inherit; padding: 0; }
table { border-collapse: collapse; width: 100%; margin: 10px 0 18px; font-size: 10pt; page-break-inside: avoid; }
th, td { border: 1px solid #c9d4e3; padding: 6px 9px; text-align: left; vertical-align: top; }
th { background: #0b3d91; color: #fff; font-weight: 600; }
tr:nth-child(even) td { background: #f7f9fc; }
a { color: #0b3d91; text-decoration: none; word-break: break-word; }
ul, ol { padding-left: 22px; }
hr { border: none; border-top: 1px solid #c9d4e3; margin: 24px 0; }
.toc { background: #f4f7fb; border: 1px solid #c9d4e3; border-radius: 6px; padding: 8px 18px; }
.cover { text-align: center; padding: 80px 0 30px; page-break-after: always; }
.cover h1 { border: none; font-size: 30pt; margin-bottom: 8px; }
.cover .sub { color: #475569; font-size: 13pt; }
.cover .meta { margin-top: 60px; color: #64748b; font-size: 10pt; }
CSS;

$isFr   = ($lang === 'fr');
$sub    = $isFr ? 'Structure, Procédure &amp; Flux des Paiements' : 'Payment Structure, Procedure &amp; Workflow';
$meta   = $isFr
    ? 'Laravel 10 · Livewire 3 · Symfony Workflow<br>Espèces · Chèque · Mobile Money (QOSIC / FedaPay / PayGate)'
    : 'Laravel 10 · Livewire 3 · Symfony Workflow<br>Cash · Cheque · Mobile Money (QOSIC / FedaPay / PayGate)';
$title  = $isFr ? 'SIG-RECETTE — Structure des Paiements' : 'SIG-RECETTE — Payment Structure';
$cover = "<div class=\"cover\">\n  <h1>SIG-RECETTE</h1>\n  <div class=\"sub\">{$sub}</div>\n  <div class=\"meta\">{$meta}</div>\n</div>";

$html = "<!doctype html>\n<html lang=\"{$lang}\"><head><meta charset=\"utf-8\"><title>{$title}</title><style>{$css}</style></head><body>{$cover}{$bodyHtml}</body></html>";

if (!is_dir(dirname($dst))) {
    mkdir(dirname($dst), 0777, true);
}

file_put_contents($dst, $html);
fwrite(STDOUT, "Wrote $dst (" . strlen($html) . " bytes)\n");
