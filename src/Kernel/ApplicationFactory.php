<?php
declare(strict_types=1);

namespace Hyperf\EasyWechat\Kernel;

use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Factory.
 * @method \EasyWeChat\OfficialAccount\Application officialAccount(string $name = "default", array $config = [])
 * @method \EasyWeChat\Pay\Application pay(string $name = "default", array $config = [])
 * @method \EasyWeChat\MiniApp\Application miniApp(string $name = "default", array $config = [])
 * @method \EasyWeChat\OpenPlatform\Application openPlatform(string $name = "default", array $config = [])
 * @method \EasyWeChat\Work\Application work(string $name = "default", array $config = [])
 * @method \EasyWeChat\OpenWork\Application openWork(string $name = "default", array $config = [])
 */
class ApplicationFactory
{
    protected ContainerInterface $container;
    protected ConfigInterface $config;
    protected CacheInterface $cache;
    protected array $configMap = [
        'officialAccount' => 'official_account',
        'pay' => 'pay',
        'miniApp' => 'mini_app',
        'openPlatform' => 'open_platform',
        'work' => 'work',
        'openWork' => 'open_work',
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get(ConfigInterface::class);
        $this->cache = $container->get(CacheInterface::class);
    }

    public function __call($functionName, $args)
    {
        $groupName = $args[0] ?? 'default';
        $customConfig = $args[1] ?? [];

        if (!isset($this->configMap[$functionName])) {
            throw new \RuntimeException('方法不存在!');
        }

        $configName = $this->configMap[$functionName];
        $appName = ucfirst($functionName);
        $config = $this->getConfig(sprintf('wechat.%s.%s', $configName, $groupName), $customConfig);
        $application = "\\EasyWeChat\\{$appName}\\Application";
        $symfonyRequest = $this->getRequest();
        $app = new $application($config);

        //替换缓存
        if (method_exists($app, 'setCache')) {
            $app->setCache($this->cache);
        }

        //替换请求
        if ($symfonyRequest && method_exists($app, 'setRequestFromSymfonyRequest')) {
            $app->setRequestFromSymfonyRequest($symfonyRequest);
        }

        return $app;
    }

    /**
     * 获取配置
     * @param string $name
     * @param array $config
     * @return array
     */
    private function getConfig(string $name, array $config = []): array
    {
        $defaultConfig = $this->config->get('wechat.defaults', []);
        $moduleConfig = $this->config->get($name, []);
        return array_merge($moduleConfig, $defaultConfig, $config);
    }

    /**
     * 获取Request请求对象
     * @return Request|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getRequest(): ?Request
    {
        if (!Context::has(ServerRequestInterface::class)) {
            return null;
        }

        $request = $this->container->get(RequestInterface::class);
        $uploadFiles = $request->getUploadedFiles() ?? [];

        $files = [];
        foreach ($uploadFiles as $k => $v) {
            $files[$k] = $v->toArray();
        }

        $req = new Request(
            $request->getQueryParams(),
            $request->getParsedBody(),
            [],
            $request->getCookieParams(),
            $files,
            $request->getServerParams(),
            $request->getBody()->getContents()
        );
        $req->headers = new HeaderBag($request->getHeaders());
        return $req;
    }

}