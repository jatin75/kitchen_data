<?php
if(env('IOS_PUSH_ENV') == 'development')
{
    $cert = public_path('cert/ASKitchenDevAPN.pem');
}
else
{
    $cert = public_path('cert/ASKitchenDistAPN.pem');
}
return array(

    'KITCHENIOS'     => array(
        'environment' =>env('IOS_PUSH_ENV', 'development'),
        'certificate' =>env('IOS_PUSH_CERT', $cert),
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