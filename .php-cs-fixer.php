<?php

use Cego\CegoFixer;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/publishable')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/tests');

return CegoFixer::applyRules($finder);
