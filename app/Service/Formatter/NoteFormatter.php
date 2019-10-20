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

namespace App\Service\Formatter;

use App\Model\Note;
use App\Service\Instance\UserInstance;

class NoteFormatter extends Formatter
{
    public function base(Note $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'text' => $model->text,
            'created_at' => $model->created_at->toDateTimeString(),
            'created_date' => $model->created_at->toDateString(),
        ];
    }

    public function formatArray($models)
    {
        $ids = [];
        /** @var Note $model */
        foreach ($models as $model) {
            $ids[] = $model->user_id;
        }

        $instance = UserInstance::instance();
        $instance->init($ids);

        $result = [];
        foreach ($models as $model) {
            $item = self::base($model);
            if ($user = $instance->getModel($model->user_id)) {
                $item['user'] = UserFormatter::instance()->base($user);
            }

            $result[] = $item;
        }

        return $result;
    }
}
