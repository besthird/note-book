<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Kernel\Oauth\WeChatFactory;
use App\Service\Dao\UserDao;
use App\Service\Instance\JwtInstance;
use App\Service\Redis\UserCollection;
use Hyperf\Di\Annotation\Inject;

class UserService extends Service
{
    /**
     * @Inject
     * @var WeChatFactory
     */
    protected $factory;

    /**
     * @Inject
     * @var UserDao
     */
    protected $dao;

    public function login(string $code)
    {
        $app = $this->factory->create();

        $session = $app->auth->session($code);

        $user = di()->get(UserCollection::class)->getUser($session['openid']);

        if (empty($user)) {
            throw new BusinessException(ErrorCode::USER_NOT_REGIST);
        }

        $token = JwtInstance::instance()->encode($user);

        return [$token, $user];
    }

    public function regist($code, $encrypted_data, $iv)
    {
        $app = $this->factory->create();

        $session = $app->auth->session($code);

        $userInfo = $app->encryptor->decryptData($session['session_key'], $iv, $encrypted_data);

        $user = $this->dao->create($userInfo);

        $token = JwtInstance::instance()->encode($user);

        return [$token, $user];
    }
}
