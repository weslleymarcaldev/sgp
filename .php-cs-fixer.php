<?php
// .php-cs-fixer.php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/public')
    ;

// aqui você escolhe o conjunto de regras que quer aplicar
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12'                             => true,
        'array_syntax'                       => ['syntax' => 'short'],
        'binary_operator_spaces'             => ['default' => 'align_single_space'],
        'no_unused_imports'                  => true,
        'ordered_imports'                    => ['sort_algorithm' => 'alpha'],
        'trim_array_spaces'                  => true,
        'blank_line_after_namespace'         => true,
        'blank_line_after_opening_tag'       => true,
        'braces'                             => true,
        // adicione outras regras que desejar...
    ])
    ->setFinder($finder)
;
