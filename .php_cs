<?php
$fixers = [
    '-psr0',
    'php_closing_tag',
    'blankline_after_open_tag',
    'concat_without_spaces',
    'double_arrow_multiline_whitespaces',
    'duplicate_semicolon',
    'empty_return',
    'extra_empty_lines',
    'include',
    'join_function',
    'list_commas',
    'multiline_array_trailing_comma',
    'namespace_no_leading_whitespace',
    'newline_after_open_tag',
    'no_blank_lines_after_class_opening',
    'no_empty_lines_after_phpdocs',
    'object_operator',
    'operators_spaces',
    'phpdoc_indent',
    'phpdoc_no_access',
    'phpdoc_no_package',
    'phpdoc_scalar',
    'phpdoc_short_description',
    'phpdoc_trim',
    'phpdoc_type_to_var',
    'phpdoc_var_without_name',
    'remove_leading_slash_use',
    'remove_lines_between_uses',
    'return',
    'self_accessor',
    'single_array_no_trailing_comma',
    'single_blank_line_before_namespace',
    'single_quote',
    'spaces_before_semicolon',
    'spaces_cast',
    'standardize_not_equal',
    'ternary_spaces',
    'trim_array_spaces',
    'unalign_equals',
    'unary_operators_spaces',
    'whitespacy_lines',
    'multiline_spaces_before_semicolon',
    'short_array_syntax',
    'short_echo_tag',
];
if (class_exists('PhpCsFixer\Finder')) {    // PHP-CS-Fixer 2.x
    $finder = PhpCsFixer\Finder::create()
        ->in(__DIR__);
    return PhpCsFixer\Config::create()
        ->setRules(array(
            '@PSR2' => true,
        ))
        ->setFinder($finder);
} elseif (class_exists('Symfony\CS\Finder\DefaultFinder')) {  // PHP-CS-Fixer 1.x
    $finder = Symfony\CS\Finder\DefaultFinder::create()
        ->in(__DIR__);
    return Symfony\CS\Config\Config::create()
        ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
        ->fixers($fixers)
        ->finder($finder);
}