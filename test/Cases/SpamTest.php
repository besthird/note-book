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
}
