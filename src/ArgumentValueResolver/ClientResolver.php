<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\ValueObject\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ClientResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Client::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield Client::fromRequest($request);
    }
}
