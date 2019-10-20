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

use App\Request\NoteSearchRequest;
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

    public function index(NoteSearchRequest $request)
    {
        $offset = (int) $request->input('offset');
        $limit = (int) $request->input('limit');

        $userId = JwtInstance::instance()->getId();
        if (empty($userId)) {
            return $this->response->success([]);
        }

        $result = $this->service->search($userId, $offset, $limit);

        return $this->response->success($result);
    }

    public function save(SaveNoteRequest $request, int $id)
    {
        $text = $request->input('text');

        $userId = JwtInstance::instance()->build()->getId();

        $result = $this->service->save($id, $userId, $text);

        return $this->response->success($result);
    }

    public function delete(int $id)
    {
        $userId = JwtInstance::instance()->build()->getId();

        $result = $this->service->delete($id, $userId);

        return $this->response->success($result);
    }
}
