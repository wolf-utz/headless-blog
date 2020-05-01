<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src/', __DIR__.'/tests/')
    ->exclude(__DIR__.'/vendor/');

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_unused_imports' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'blank_line_after_opening_tag' => false,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'declare_strict_types' => true,
        'psr4' => true,
        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'semicolon_after_instruction' => true,
        'align_multiline_comment' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ["author", "package"]],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
    ])
    ->setFinder($finder);
