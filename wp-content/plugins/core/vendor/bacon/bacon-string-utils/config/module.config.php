<?php
return array(
    'filters' => array(
        'aliases' => array(
            'slugify' => 'BaconStringUtils\Filter\Slugify',
        ),
        'factories' => array(
            'BaconStringUtils\Filter\Slugify' => 'BaconStringUtils\Filter\SlugifyFactory',
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'BaconStringUtils\UniDecoder' => 'BaconStringUtils\UniDecoder',
        ),
        'factories' => array(
            'BaconStringUtils\Slugifier' => 'BaconStringUtils\SlugifierFactory',
        ),
    ),
);
