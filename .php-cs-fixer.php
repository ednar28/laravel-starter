<?php

// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/index.rst
return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'=> true,
        'array_indentation'=> true,
        'array_syntax'=> [
            'syntax'=> 'short',
        ],
        'concat_space'=> [
            'spacing'=> 'one',
        ],
        'function_typehint_space'=> true,
        'method_argument_space' => [
            'on_multiline' => 'ignore',
            // 'on_multiline' => 'ensure_single_line',
            'keep_multiple_spaces_after_comma' => false,
        ],
        'method_chaining_indentation'=> true,
        'native_function_casing'=> true,
        'native_function_type_declaration_casing'=> true,
        'no_empty_statement'=> true,
        'no_leading_namespace_whitespace'=> true,
        'no_singleline_whitespace_before_semicolons'=> true,
        'no_trailing_comma_in_singleline_array'=> true,
        'no_unused_imports'=> true,
        'no_whitespace_before_comma_in_array'=> true,
        'object_operator_without_whitespace'=> true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => null,
        ],
        'single_blank_line_before_namespace'=> true,
        'single_quote'=> true,
        'standardize_not_equals'=> true,
        'ternary_operator_spaces'=> true,
        'trailing_comma_in_multiline'=> [
            'elements'=> [
                'arrays',
            ],
        ],
        'unary_operator_spaces'=> true,
        'whitespace_after_comma_in_array'=> true,
    ])
    ->setLineEnding("\n");
