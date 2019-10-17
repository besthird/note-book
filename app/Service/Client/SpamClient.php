<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Service\Client;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Service\Service;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Cache\CacheManager;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\HandlerStackFactory;

class SpamClient extends Service
{
    /**
     * @var HandlerStack
     */
    protected $stack;

    /**
     * @Inject
     * @var CacheManager
     */
    protected $manager;

    /**
     * @Value(key="app.switch.spam")
     * @var bool
     */
    protected $spam = false;

    public function spam(string $content)
    {
        if ($this->spam === false) {
            return true;
        }

        $uri = '/rest/2.0/antispam/v2/spam?access_token=' . $this->getToken();
        $params = [
            'content' => $content,
        ];

        $str = $this->client()->post($uri, [
            'form_params' => $params,
        ])->getBody()->getContents();

        $result = json_decode($str, true);

        if (! isset($result['result']['reject'])) {
            return false;
        }

        if (! empty($result['result']['reject']) || ! empty($result['result']['review'])) {
            return false;
        }

        return true;
    }

    public function getToken(): string
    {
        return (string) $this->manager->call(function () {
            $uri = '/oauth/2.0/token';
            $params = [
                'grant_type' => 'client_credentials',
                'client_id' => env('BAIDU_SPAM_KEY'),
                'client_secret' => env('BAIDU_SPAM_SECRET'),
            ];

            $str = $this->client()->get($uri, [
                'query' => $params,
            ])->getBody()->getContents();

            $res = json_decode($str, true);

            if (! isset($res['access_token'])) {
                throw new BusinessException(ErrorCode::SPAM_TOKEN_FETCH_FAILED);
            }

            return $res['access_token'];
        }, 'spam:token', 2590000);
    }

    protected function client()
    {
        return make(Client::class, [[
            'handler' => $this->getHandlerStack(),
            'base_uri' => 'https://aip.baidubce.com',
            'timeout' => 2,
        ]]);
    }

    protected function getHandlerStack(): HandlerStack
    {
        if ($this->stack instanceof HandlerStack) {
            return $this->stack;
        }

        return $this->stack = di()->get(HandlerStackFactory::class)->create();
    }
}
