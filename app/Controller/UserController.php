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

namespace App\Controller;

use App\Request\LoginRequest;
use App\Request\RegistRequest;
use App\Service\Formatter\UserFormatter;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;

class UserController extends Controller
{
    /**
     * @Inject
     * @var UserService
     */
    protected $service;

    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        [$token, $user] = $this->service->login($input['code']);

        return $this->response->success([
            'token' => $token,
            'user' => UserFormatter::instance()->base($user),
        ]);
    }

    public function regist(RegistRequest $request)
    {
        $input = $request->validated();

        [$token, $user] = $this->service->regist($input['code'], $input['encrypted_data'], $input['iv']);

        return $this->response->success([
            'token' => $token,
            'user' => UserFormatter::instance()->base($user),
        ]);
    }
}
