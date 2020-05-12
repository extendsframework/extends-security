<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ProblemDetails;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class UnauthorizedProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsFramework\Security\Framework\ProblemDetails\UnauthorizedProblemDetails::__construct()
     */
    public function testGetters(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/foo/bar');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $problemDetails = new UnauthorizedProblemDetails($request);

        $this->assertSame('/problems/authentication/unauthorized', $problemDetails->getType());
        $this->assertSame('Unauthorized', $problemDetails->getTitle());
        $this->assertSame('Failed to authenticate request.', $problemDetails->getDetail());
        $this->assertSame(401, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
    }
}
