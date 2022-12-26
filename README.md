# hyperf-wechat

微信 SDK for Hyperf， 基于 w7corp/easywechat

由于 easywechat6 使用了 symfony/http-client作为请求库，hyperf提供了ClassMap替换能力，此包替换EasyWechat底层InteractWithHttpClient中的HttpClient对象实例，支持协程。

## 安装

```shell script
composer require lujihong/easywechat6 
```

## 配置

1. 发布配置文件

```shell script
php ./bin/hyperf.php vendor:publish lujihong/easywechat6
```

2. 修改根目录下的 `config/autoload/wechat.php` 中对应的参数即可。
3. 每个模块都支持多账号，默认为 `default`。

## 使用

接收普通消息例子：

```php
Router::addRoute(['GET', 'POST', 'HEAD'], '/wechat', 'App\Controller\WeChatController@serve');
```

> 注意：微信服务端认证的时候是 `GET`, 接收用户消息时是 `POST` ：

```php
<?php
declare(strict_types=1);

namespace App\Controller;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Hyperf\EasyWechat\EasyWechat;
use function response;
use ReflectionException;

class WeChatController
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     * @throws BadRequestException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function serve()
    {
        $app = EasyWechat::officialAccount();
        $server = $app->getServer();
        
        $server->with(function ($message, \Closure $next) {
            return '谢谢关注！';
            
            // 自定义逻辑
            // return $next($message);
        });
        
        // response转换
        return response($server->serve());
    }
}
```

##### EasyWechat中已对request及cache对象替换，更方便使用

```php
  use Hyperf\EasyWechat\EasyWechat;
  
  $officialAccount = EasyWechat::officialAccount(); //公众号
  $pay = EasyWechat::pay(); //微信支付
  $miniApp = EasyWechat::miniApp(); //小程序
  $openPlatform = EasyWechat::openPlatform(); //开放平台
  $work = EasyWechat::work(); //企业微信
  $openWork = EasyWechat::openWork(); //企业微信开放平台
  
  // `foo` 为配置文件中的名称，默认为 `default`。`[]` 可覆盖账号配置
  EasyWeChat::officialAccount('foo', []);
```

更多详细的用法，请参考：https://easywechat.com

## License
MIT
