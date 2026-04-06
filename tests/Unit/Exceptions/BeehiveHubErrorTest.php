<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Exceptions;

use BeehiveHub\SDK\Exceptions\BeehiveHubAPIError;
use BeehiveHub\SDK\Exceptions\BeehiveHubAuthenticationError;
use BeehiveHub\SDK\Exceptions\BeehiveHubError;
use BeehiveHub\SDK\Exceptions\BeehiveHubNetworkError;
use BeehiveHub\SDK\Exceptions\BeehiveHubNotFoundError;
use BeehiveHub\SDK\Exceptions\BeehiveHubRateLimitError;
use BeehiveHub\SDK\Exceptions\BeehiveHubValidationError;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BeehiveHubErrorTest extends TestCase
{
    public function testIsRuntimeException(): void
    {
        $error = new BeehiveHubError('Something went wrong');

        $this->assertInstanceOf(RuntimeException::class, $error);
    }

    public function testConstructorSetsMessage(): void
    {
        $error = new BeehiveHubError('Something went wrong');

        $this->assertSame('Something went wrong', $error->getMessage());
    }

    public function testConstructorSetsAllProperties(): void
    {
        $error = new BeehiveHubError('Error', 422, 'VALIDATION_FAILED', ['field' => 'name']);

        $this->assertSame(422, $error->statusCode);
        $this->assertSame('VALIDATION_FAILED', $error->errorCode);
        $this->assertSame(['field' => 'name'], $error->details);
    }

    public function testConstructorDefaultsToNulls(): void
    {
        $error = new BeehiveHubError('Error');

        $this->assertNull($error->statusCode);
        $this->assertNull($error->errorCode);
        $this->assertNull($error->details);
    }

    public function testToArrayStructure(): void
    {
        $error  = new BeehiveHubError('Error msg', 400, 'ERR_CODE', ['x' => 1]);
        $result = $error->toArray();

        $this->assertSame([
            'message'    => 'Error msg',
            'statusCode' => 400,
            'code'       => 'ERR_CODE',
            'details'    => ['x' => 1],
        ], $result);
    }

    public function testToArrayWithNullValues(): void
    {
        $result = (new BeehiveHubError('Oops'))->toArray();

        $this->assertNull($result['statusCode']);
        $this->assertNull($result['code']);
        $this->assertNull($result['details']);
    }

    public function testAuthenticationErrorDefaultMessage(): void
    {
        $error = new BeehiveHubAuthenticationError();

        $this->assertSame('Invalid API key or authentication failed', $error->getMessage());
        $this->assertSame(401, $error->statusCode);
    }

    public function testSubclassesExtendBeehiveHubError(): void
    {
        $this->assertInstanceOf(BeehiveHubError::class, new BeehiveHubAPIError('e'));
        $this->assertInstanceOf(BeehiveHubError::class, new BeehiveHubValidationError('e'));
        $this->assertInstanceOf(BeehiveHubError::class, new BeehiveHubNotFoundError('e'));
        $this->assertInstanceOf(BeehiveHubError::class, new BeehiveHubRateLimitError('e'));
        $this->assertInstanceOf(BeehiveHubError::class, new BeehiveHubNetworkError('e'));
        $this->assertInstanceOf(BeehiveHubError::class, new BeehiveHubAuthenticationError());
    }
}
