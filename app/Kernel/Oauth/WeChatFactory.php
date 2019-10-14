<?php


namespace App\Kernel\Oauth;

use Hyperf\Utils\ApplicationContext;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\ServiceContainer;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\Guzzle\HandlerStackFactory;
use Overtrue\Socialite\Providers\AbstractProvider;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WeChatFactory
{
    public function __construct(ContainerInterface $container)
    {
        $app = Factory::miniProgram($config);

        // 设置 HttpClient，当前设置没有实际效果，在数据请求时会被 guzzle_handler 覆盖，但不保证 EasyWeChat 后面会修改这里。
        $config = $app['config']->get('http', []);
        $config['handler'] = $container->get(HandlerStackFactory::class)->create();
        $app->rebind('http_client', new Client($config));

        // 重写 Handler
        $app['guzzle_handler'] = new CoroutineHandler();

        // 设置 OAuth 授权的 Guzzle 配置
        AbstractProvider::setGuzzleOptions([
            'http_errors' => false,
            'handler' => HandlerStack::create(new CoroutineHandler()),
        ]);
    }

    public function create()
    {

    }
}