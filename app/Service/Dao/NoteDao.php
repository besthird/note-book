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
use App\Model\Note;
use App\Service\Service;

class NoteDao extends Service
{
    /**
     * @param $id
     * @param bool $throw
     * @return Note
     */
    public function first($id, $throw = true)
    {
        $model = Note::query()->find($id);
        if (empty($model) && $throw) {
            throw new BusinessException(ErrorCode::NOTE_NOT_EXIST);
        }

        return $model;
    }

    public function find($query, $offset = 0, $limit = 10)
    {
        $query = Note::query();

        return $query->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
    }
}
