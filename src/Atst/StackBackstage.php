<?php

namespace Atst;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StackBackstage implements HttpKernelInterface
{
    protected $path;

    public function __construct(HttpKernelInterface $app, $path)
    {
        $this->app = $app;
        $this->path = $path;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $path = realpath($this->path);

        if (false !== $path  && is_readable($path)) {
            return new Response(file_get_contents($path), 503);
        }

        return $this->app->handle($request, $type, $catch);
    }
}


