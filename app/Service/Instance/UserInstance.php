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
use Hyperf\Utils\Traits\StaticInstance;

class UserInstance
{
    use StaticInstance;

    public $models = [];

    public function init(array $ids)
    {
        $ids = array_diff($ids, array_keys($this->models));

        $models = User::findManyFromCache($ids);

        /** @var User $model */
        foreach ($models as $model) {
            $this->models[$model->id] = $model;
        }
    }

    public function getModel($id): ?User
    {
        return $this->models[$id] ?? null;
    }
}
