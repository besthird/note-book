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

namespace HyperfTest\Cases;

use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class NoteTest extends HttpTestCase
{
    public function testNoteSave()
    {
        $res = $this->json('/note/1', [
            'text' => '首条消息' . uniqid(),
        ]);

        $this->assertSame(0, $res['code']);
    }

    public function testNoteIndex()
    {
        $res = $this->get('/note', [
            'offset' => '0',
            'limit' => '1',
        ]);

        $this->assertSame(0, $res['code']);
    }
}
