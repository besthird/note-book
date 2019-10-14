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

namespace App\Service\Instance;

use App\Model\User;
use Firebase\JWT\JWT;
use Hyperf\Utils\Traits\StaticInstance;

class JwtInstance
{
    use StaticInstance;

    const KEY = 'NoteBook';

    public function encode(User $user)
    {
        return JWT::encode(['id' => $user->id], self::KEY);
    }

    public function decode(string $token)
    {
        $decoded = JWT::decode($token, self::KEY, ['HS256']);

        var_dump($decoded);

        return $decoded;
    }
}
