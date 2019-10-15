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

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Note;
use App\Service\Dao\NoteDao;
use App\Service\Formatter\NoteFormatter;
use Hyperf\Di\Annotation\Inject;

class NoteService extends Service
{
    /**
     * @Inject
     * @var NoteDao
     */
    protected $dao;

    public function search($offset, $limit)
    {
        $models = $this->dao->find([], $offset, $limit);

        return NoteFormatter::instance()->formatArray($models);
    }

    public function save($id, $userId, $text)
    {
        if ($id > 0) {
            $note = $this->dao->first($id, true);
            if ($note->user_id != $userId) {
                throw new BusinessException(ErrorCode::USER_INVALID);
            }
        } else {
            $note = new Note();
            $note->user_id = $userId;
        }

        $note->text = $text;
        return $note->save();
    }
}
