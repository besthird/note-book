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

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Service\Dao\UserDao;
use Firebase\JWT\JWT;
use Hyperf\Utils\Traits\StaticInstance;

class JwtInstance
{
    use StaticInstance;

    const KEY = 'NoteBook';

    /**
     * @var int
     */
    public $id;

    /**
     * @var User
     */
    public $user;

    public function encode(User $user)
    {
        $this->id = $user->id;
        $this->user = $user;

        return JWT::encode(['id' => $user->id], self::KEY);
    }

    public function decode(string $token): self
    {
        try {
            $decoded = (array) JWT::decode($token, self::KEY, ['HS256']);
        } catch (\Throwable $exception) {
            return $this;
        }

        if ($id = $decoded['id'] ?? null) {
            $this->id = $id;
            $this->user = di()->get(UserDao::class)->first($id);
        }

        return $this;
    }

    public function build(): self
    {
        if (empty($this->id)) {
            throw new BusinessException(ErrorCode::TOKEN_INVALID);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        if ($this->user === null && $this->id) {
            $this->user = di()->get(UserDao::class)->first($this->id);
        }
        return $this->user;
    }
}
