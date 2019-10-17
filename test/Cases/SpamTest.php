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

use App\Service\Client\SpamClient;
use Hyperf\Contract\ConfigInterface;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class SpamTest extends HttpTestCase
{
    public function testGetSpamToken()
    {
        $token = di()->get(SpamClient::class)->getToken();

        $this->assertNotEmpty($token);
    }

    public function testSpamSpam()
    {
        $res = di()->get(SpamClient::class)->spam('习近平');
        $this->assertSame(! di()->get(ConfigInterface::class)->get('app.switch.spam'), $res);

        $res = di()->get(SpamClient::class)->spam('您好');
        $this->assertTrue($res);
    }
}
