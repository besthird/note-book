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

use App\Request\SaveNoteRequest;
use App\Service\Instance\JwtInstance;
use App\Service\NoteService;
use Hyperf\Di\Annotation\Inject;

class NoteController extends Controller
{
    /**
     * @Inject
     * @var NoteService
     */
    protected $service;

    public function save(SaveNoteRequest $request, int $id)
    {
        $text = $request->validated()['text'];

        $userId = JwtInstance::instance()->build()->getId();

        $result = $this->service->save($id, $userId, $text);

        return $this->response->success($result);
    }
}
