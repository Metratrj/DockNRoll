<?php
/*
 * Copyright (c) 2025.
 */

/**
 * @generated
 * @link https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/HEAD/doc/config.rst
 */
$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__)
  ->exclude("vendor")
  ->exclude("test")
  ->exclude("tests")
  ->exclude("app/External/docker-php-client")
  ->exclude("app/External/docker-php-client/vendor")
  ->exclude("app/External/docker-php-client/test")
  ->exclude("app/External/docker-php-client/tests")
  ->exclude("app/External/docker-php-client/lib")
  ->exclude("app/External/docker-php-client/lib/Api")
  ->exclude("app/External/docker-php-client/lib/Model");

$config = new PhpCsFixer\Config();
return $config
  ->setRules([
    "@PSR12" => true,
    "phpdoc_order" => true,
    "array_syntax" => ["syntax" => "short"],
    "strict_comparison" => false,
    "strict_param" => false,
    "no_trailing_whitespace" => false,
    "no_trailing_whitespace_in_comment" => false,
    "braces" => false,
    "single_blank_line_at_eof" => true,
    "blank_line_after_namespace" => true,
    "no_leading_import_slash" => false,
  ])
  ->setFinder($finder);
