<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return static function (App $app): void {
    $customErrorHandler = function (
        Request $_request,
        Throwable $exception,
        bool $_displayErrorDetails,
        bool $_logErrors,
        bool $_logErrorDetails
    ) use ($app): Psr\Http\Message\ResponseInterface {
        $response = $app->getResponseFactory()->createResponse();

        $status = 500;
        $message = "An unexpected error occurred";

        if ($exception instanceof InvalidArgumentException) {
            $status = 400;
            $message = $exception->getMessage();
        }

        $payload = [
            'error' => [
                'message' => $message
            ]
        ];

        $response->getBody()->write(
            json_encode(
                $payload,
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    };

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
};
