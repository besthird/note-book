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

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        return $this->response->success($input);
    }
}
