<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
    ->setParallelConfig(new PhpCsFixer\Runner\Parallel\ParallelConfig(4))
    ->setCacheFile('var/.php-cs-fixer.cache')
;
