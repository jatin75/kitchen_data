<?php

return array(

    'KITCHENIOS'     => array(
        'environment' =>env('IOS_PUSH_ENV', 'development'),
        'certificate' =>env('IOS_PUSH_CERT', public_path('cert/pushcertDev.pem')),
        'passPhrase'  =>env('IOS_PUSH_PASSWORD', ''),
        'service'     =>'apns'
    ),
    /*'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'yourAPIKey',
        'service'     =>'gcm'
    )
*/
);