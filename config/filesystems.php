<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'csv' => [
            'driver' => 'local',
            'root'   => public_path() . '/temp',
        ],

        'restaurant_preview' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/restaurants/preview',
        ],

        'restaurant_category' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/restaurants/category',
        ],

        'notification' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/notifications',
        ],

        'user_image' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/users/images',
        ],

        'grocery_category' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/grocery/category',
        ],

        'grocery_products' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/grocery/products',
        ],
        'grocery_banners' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/grocery/banners',
        ],
        'grocery_homepage_banners' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/grocery/homepage-banners',
        ],

        'grocery_leads' => [
            'driver' => 'local',
            'root'   => public_path() . '/leads',
        ],

        'mega_days' => [
            'driver' => 'local',
            'root'   => public_path() . '/app/megadays',
        ],
    ],

];
