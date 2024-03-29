<?php

$header = <<<'EOF'
This file is part of the SgDatatablesBundle package.

(c) stwe <https://github.com/stwe/DatatablesBundle>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor/')
    ->in(__DIR__)
    ->append([__DIR__.'/php-cs-fixer'])
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHPUnit60Migration:risky' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'header_comment' => ['header' => $header],
        'list_syntax' => ['syntax' => 'long'],
        'no_php4_constructor' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_useless_return' => true,
        'not_operator_with_successor_space' => true,
    ])
    ->setFinder($finder);
