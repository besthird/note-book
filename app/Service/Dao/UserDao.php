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

use App\Model\User;
use App\Service\Service;

class UserDao extends Service
{
    /**
     * @param $userInfo = [
     *     'nickName' => '', // 昵称
     *     'avatarUrl' => '', // 头像
     *     'gender' => 0, // 性别
     *     'openId' => '', // OpenId
     * ]
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
