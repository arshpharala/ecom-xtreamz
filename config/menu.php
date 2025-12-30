<?php
return [

    'header' => [

        /*
|--------------------------------------------------------------------------
| Sustainable (STATIC LINKS)
|--------------------------------------------------------------------------
*/
        'sustainable' => [
            'label' => 'SUSTAINABLE',
            'type' => 'static',
            'dropdown' => true,
            'links' => [
                [
                    'label' => 'Technology',
                    'url' => '/products?category=technology-gifts&tags[]=Sustainable',
                ],
                [
                    'label' => 'Apparel',
                    'url' => '/products?category=apparel-clothing&tags[]=Sustainable',
                ],
                [
                    'label' => 'Drinkware',
                    'url' => '/products?category=drinkware&tags[]=Sustainable',
                ],
                [
                    'label' => 'Bags & Travel',
                    'url' => '/products?category=bags-travel&tags[]=Sustainable',
                ],
                [
                    'label' => 'Home & Lifestyle',
                    'url' => '/products?category=home-living&tags[]=Sustainable',
                ],
                [
                    'label' => 'Awards & Trophies',
                    'url' => '/products?category=awards-trophies&tags[]=Sustainable',
                ],
                [
                    'label' => 'Gift Sets',
                    'url' => '/products?category=gift-sets-packaging&tags[]=Sustainable',
                ],
            ],
        ],

        /*
|--------------------------------------------------------------------------
| Apparel (CATEGORY BASED)
|--------------------------------------------------------------------------
*/
        'apparel' => [
            'label' => 'APPAREL',
            'type' => 'category',
            'menu_tag' => 'apparel',
            'dropdown' => true,
        ],

        /*
|--------------------------------------------------------------------------
| Tech
|--------------------------------------------------------------------------
*/
        'tech' => [
            'label' => 'TECH',
            'type' => 'category',
            'menu_tag' => 'tech',
            'dropdown' => true,
        ],

        /*
|--------------------------------------------------------------------------
| Drinkware
|--------------------------------------------------------------------------
*/
        'drinkware' => [
            'label' => 'DRINKWARE',
            'type' => 'category',
            'menu_tag' => 'drinkware',
            'dropdown' => true,
        ],

        /*
|--------------------------------------------------------------------------
| bags (NORMAL LINK)
|--------------------------------------------------------------------------
*/
        'bags' => [
            'label' => 'BAGS',
            'type' => 'category',
            'menu_tag' => 'bags',
            'dropdown' => true,
        ],
        /*
|--------------------------------------------------------------------------
| OFFICE (NORMAL LINK)
|--------------------------------------------------------------------------
*/
        'office' => [
            'label' => 'OFFICE',
            'type' => 'category',
            'menu_tag' => 'office',
            'dropdown' => true,
        ],
        /*
|--------------------------------------------------------------------------
| OTHERS (NORMAL LINK)
|--------------------------------------------------------------------------
*/
        'others' => [
            'label' => 'OTHERS',
            'type' => 'category',
            'menu_tag' => 'others',
            'dropdown' => true,
        ],

    ],

];
