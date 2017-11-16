<?php

return [
    "quickbooks1" => [
        "client_id"         => env('QB_ClientID'),
        "client_secret"     => env('QB_ClientSecret'),
        "access_token"      => env('QB_accessTokenKey'),
        "refresh_token"     => env('QB_refreshTokenKey'),
        "realm_id"          => env('QB_RealmID'),
    ],
    "quickbooks2" => [
        "client_id"         => env('QB_ClientID2'),
        "client_secret"     => env('QB_ClientSecret2'),
        "access_token"      => env('QB_accessTokenKey2'),
        "refresh_token"     => env('QB_refreshTokenKey2'),
        "realm_id"          => env('QB_RealmID2'),
    ]
];
