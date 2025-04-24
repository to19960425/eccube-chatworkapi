<?php

namespace Plugin\ChatworkApi;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'ChatworkApi' => [
                'name' => 'chatwork_api.admin.nav.001',
                'icon' => 'fa-comments',
                'children' => [
                    'chatwork_api_admin_config' => [
                        'id' => 'chatwork_api_admin_config',
                        'url' => 'chatwork_api_admin_config',
                        'name' => 'chatwork_api.admin.nav.002',
                    ],
                ],
            ],
        ];
    }
}
