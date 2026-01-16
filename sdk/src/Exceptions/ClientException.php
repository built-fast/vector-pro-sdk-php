<?php

declare(strict_types=1);

namespace VectorPro\Sdk\Exceptions;

use Exception;

/**
 * Exception thrown when the Vector Pro API returns an error.
 */
final class ClientException extends Exception
{
    /**
     * The HTTP status code from the API response.
     */
    private int $statusCode;

    /**
     * The response body from the API.
     *
     * @var array<string, mixed>
     */
    private array $responseBody;

    /**
     * Create a new ClientException instance.
     *
     * @param  string  $message  The exception message
     * @param  int  $statusCode  The HTTP status code
     * @param  array<string, mixed>  $responseBody  The response body
     */
    public function __construct(string $message, int $statusCode = 0, array $responseBody = [])
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the response body.
     *
     * @return array<string, mixed>
     */
    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    /**
     * Get all validation errors.
     *
     * @return array<string, array<int, string>>
     */
    public function getValidationErrors(): array
    {
        if (! isset($this->responseBody['errors']) || ! is_array($this->responseBody['errors'])) {
            return [];
        }

        $errors = [];
        foreach ($this->responseBody['errors'] as $field => $fieldErrors) {
            if (is_array($fieldErrors)) {
                /** @var array<int, string> $fieldErrors */
                $errors[(string) $field] = array_values($fieldErrors);
            } elseif (is_string($fieldErrors)) {
                $errors[(string) $field] = [$fieldErrors];
            }
        }

        return $errors;
    }

    /**
     * Get the first validation error message.
     */
    public function firstError(): ?string
    {
        foreach ($this->getValidationErrors() as $fieldErrors) {
            if (count($fieldErrors) > 0) {
                return $fieldErrors[0];
            }
        }

        return null;
    }

    /**
     * Get validation errors for a specific field.
     *
     * @return array<int, string>
     */
    public function errorsFor(string $field): array
    {
        return $this->getValidationErrors()[$field] ?? [];
    }

    /**
     * Check if a specific field has validation errors.
     */
    public function hasErrorFor(string $field): bool
    {
        $errors = $this->getValidationErrors();

        return isset($errors[$field]) && count($errors[$field]) > 0;
    }

    /**
     * Check if this is an authentication error (401).
     */
    public function isAuthenticationError(): bool
    {
        return $this->statusCode === 401;
    }

    /**
     * Check if this is an authorization error (403).
     */
    public function isAuthorizationError(): bool
    {
        return $this->statusCode === 403;
    }

    /**
     * Check if this is a not found error (404).
     */
    public function isNotFoundError(): bool
    {
        return $this->statusCode === 404;
    }

    /**
     * Check if this is a validation error (422).
     */
    public function isValidationError(): bool
    {
        return $this->statusCode === 422;
    }

    /**
     * Check if this is a server error (5xx).
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }
}
