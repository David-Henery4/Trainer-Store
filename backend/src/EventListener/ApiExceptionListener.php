<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class ApiExceptionListener
{
  public function __invoke(ExceptionEvent $event): void
  {
    $request = $event->getRequest();

    if (!str_starts_with($request->getPathInfo(), '/api')) {
      return;
    }

    $exception = $event->getThrowable();

    if ($exception instanceof HttpExceptionInterface) {
      $statusCode = $exception->getStatusCode();
      $message = $exception->getMessage();
    } else {
      $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
      $message = 'Internal server error';
    }

    $event->setResponse(
      new JsonResponse(
        ['error' => $message],
        $statusCode
      )
    );
  }
}
