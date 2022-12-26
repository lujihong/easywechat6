<?php
declare(strict_types=1);

namespace Hyperf\EasyWechat;

use Hyperf\EasyWechat\Kernel\ApplicationFactory;
use Hyperf\Utils\ApplicationContext;

/**
 * Author lujihong
 * Description
 */
class EasyWechat
{
    /**
     * 公众号
     * @param string $name
     * @param array $config
     * @return \EasyWeChat\OfficialAccount\Application
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function officialAccount(string $name = "default", array $config = []): \EasyWeChat\OfficialAccount\Application
    {
        return ApplicationContext::getContainer()->get(ApplicationFactory::class)->officialAccount($name, $config);
    }

    /**
     * 微信支付
     * @param string $name
     * @param array $config
     * @return \EasyWeChat\Pay\Application
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function pay(string $name = "default", array $config = []): \EasyWeChat\Pay\Application
    {
        return ApplicationContext::getContainer()->get(ApplicationFactory::class)->pay($name, $config);
    }

    /**
     * 小程序
     * @param string $name
     * @param array $config
     * @return \EasyWeChat\MiniApp\Application
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function miniApp(string $name = "default", array $config = []): \EasyWeChat\MiniApp\Application
    {
        return ApplicationContext::getContainer()->get(ApplicationFactory::class)->miniApp($name, $config);
    }

    /**
     * 微信开放平台
     * @param string $name
     * @param array $config
     * @return \EasyWeChat\OpenPlatform\Application
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function openPlatform(string $name = "default", array $config = []): \EasyWeChat\OpenPlatform\Application
    {
        return ApplicationContext::getContainer()->get(ApplicationFactory::class)->openPlatform($name, $config);
    }

    /**
     * 企业微信
     * @param string $name
     * @param array $config
     * @return \EasyWeChat\Work\Application
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function work(string $name = "default", array $config = []): \EasyWeChat\Work\Application
    {
        return ApplicationContext::getContainer()->get(ApplicationFactory::class)->work($name, $config);
    }

    /**
     * 企业微信开放平台
     * @param string $name
     * @param array $config
     * @return \EasyWeChat\OpenWork\Application
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function openWork(string $name = "default", array $config = []): \EasyWeChat\OpenWork\Application
    {
        return ApplicationContext::getContainer()->get(ApplicationFactory::class)->openWork($name, $config);
    }

}