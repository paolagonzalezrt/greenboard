<?php

namespace App\Services\Translation\Exceptions;

use Exception;

class TranslationException extends Exception
{
    protected string $provider;
    protected ?array $context;

    public function __construct(
        string $message,
        string $provider = 'unknown',
        ?array $context = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->provider = $provider;
        $this->context = $context;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }
}
