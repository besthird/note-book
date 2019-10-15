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

namespace App\Service\Dao;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Service\Service;

class UserDao extends Service
{
    /**
     * @param $id
     * @param bool $throw
     * @return null|User
     */
    public function first($id, $throw = true)
    {
        $model = User::findFromCache($id);
        if (empty($model) && $throw) {
            throw new BusinessException(ErrorCode::USER_NOT_EXIST);
        }

        return $model;
    }

    /**
     * @param $openId
     * @return null|User
     */
    public function firstByOpenId($openId)
    {
        return User::query()->where('openid', $openId)->first();
    }

    /**
     * @param $userInfo = [
     *     'nickName' => '', // 昵称
     *     'avatarUrl' => '', // 头像
     *     'gender' => 0, // 性别
     *     'openId' => '', // OpenId
     * ]
     * @return User
     */
    public function create($userInfo)
    {
        $model = User::query()->where('openid', $userInfo['openId'])->first();
        if (empty($model)) {
            $model = new User();
            $model->openid = $userInfo['openId'];
        }

        $model->nickname = $userInfo['nickName'];
        $model->avatar = $userInfo['avatarUrl'];
        $model->gender = $userInfo['gender'];
        $model->save();

        return $model;
    }
}
