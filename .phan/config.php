<?php

return [
    "plugins" => [
        "vendor/phan/phan/.phan/plugins/UnknownElementTypePlugin.php",
        "vendor/phan/phan/.phan/plugins/UnreachableCodePlugin.php",
        "vendor/phan/phan/.phan/plugins/PrintfCheckerPlugin.php",
        "vendor/phan/phan/.phan/plugins/PregRegexCheckerPlugin.php",
        "vendor/phan/phan/.phan/plugins/UnusedSuppressionPlugin.php",
        "vendor/phan/phan/.phan/plugins/DuplicateArrayKeyPlugin.php"
    ],
    "allow_missing_properties" => false,
    "allow_method_param_type_widening" => true,
    "null_casts_as_any_type" => false,
    'backward_compatibility_checks' => false,
    "quick_mode" => true,
    "minimum_severity" => 0,
    'directory_list' => [ '.' ],
    "exclude_analysis_directory_list" => [
        'vendor',
        '.phan',
        'tests',
    ],
];
