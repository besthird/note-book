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

namespace App\Service\Redis;

use App\Model\User;
use App\Service\Dao\UserDao;
use Xin\RedisCollection\StringCollection;

class UserCollection extends StringCollection
{
    protected $prefix = 'user:openid:';

    public function getUser(string $openid): ?User
    {
        $id = $this->get($openid);
        $user = null;
        if (empty($id)) {
            $user = di()->get(UserDao::class)->firstByOpenId($openid);
            if ($user) {
                $id = $user->id;
                $this->set($openid, $id);
            }
        }

        if (empty($user) && $id) {
            $user = di()->get(UserDao::class)->first($id);
        }

        if (empty($user)) {
            $this->delete($openid);
            return null;
        }

        return $user;
    }

    public function redis()
    {
        return di()->get(\Redis::class);
    }
}
