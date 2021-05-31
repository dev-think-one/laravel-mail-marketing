<?php

return [
    'default'                   => 'mailchimp',

    'mailchimp'                 => [
        'key'  => env('MAILCHIMP_API_KEY'),
        'list' => [
            'contact'             => [
                'company'  => 'MyCompany',
                'address1' => '',
                'address2' => '',
                'city'     => '',
                'state'    => '',
                'zip'      => '',
                'country'  => 'GB',
                'phone'    => '',
            ],
            'permission_reminder' => 'Created from laravel app',
            'email_type_option'   => false,
            'campaign_defaults'   => [
                'from_name'  => 'MyApp.co.uk',
                'from_email' => 'emailservices@my.app',
                'subject'    => '',
                'language'   => 'en',
            ],
        ],
    ],
];
