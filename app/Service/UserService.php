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

namespace App\Service;

use App\Kernel\Oauth\WeChatFactory;
use Hyperf\Di\Annotation\Inject;

class UserService extends Service
{
    /**
     * @Inject
     * @var WeChatFactory
     */
    protected $factory;

    public function login(string $code)
    {
        $app = $this->factory->create();

        return $app->auth->session($code);
    }

    public function regist($code, $encrypted_data, $iv)
    {
        $app = $this->factory->create();

        $session = $app->auth->session($code);

        $decryptedData = $app->encryptor->decryptData($session['session_key'], $iv, $encrypted_data);

        return $decryptedData;
    }
}
