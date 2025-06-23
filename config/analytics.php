<?php
return [

    /*
     * Google Analytics property ID (numeric only, e.g. 494249356)
     */
    'view_id' => env('ANALYTICS_PROPERTY_ID'),

    /*
     * Path to the JSON key file
     */
    'service_account_credentials_json' => base_path(env('GOOGLE_SERVICE_ACCOUNT_JSON')),

    /*
     * Caching (optional)
     */
    'cache_lifetime_in_minutes' => 60 * 24,

    'cache' => [
        'store' => 'file',
    ],
];
