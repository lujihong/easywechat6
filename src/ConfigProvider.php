<?php
declare(strict_types=1);

namespace Hyperf\EasyWechat;

use EasyWeChat\Kernel\Traits\InteractWithHttpClient;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'class_map' => [
                        InteractWithHttpClient::class => __DIR__ . '/ClassMap/InteractWithHttpClient.php',
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for wechat.',
                    'source' => __DIR__ . '/../publish/wechat.php',
                    'destination' => BASE_PATH . '/config/autoload/wechat.php',
                ],
            ],
        ];
    }
}