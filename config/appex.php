<?php

// `appex.php` = config/app.php extended. Leave the original `config/app.php` alone for slightly easier upgrades.

return [
    'github' => 'https://github.com/anrosol/team-trends',

    'cloud' => 'https://team-trends.com',

    'passphrase' => [
        'pepper' => env('PASSPHRASE_PEPPER'),

        'words' => 5,
    ],

    'default_likert_scale' => 7,

    'max_questions' => 7,
];
