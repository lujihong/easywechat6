<?php
declare(strict_types=1);

use Hyperf\Utils\ApplicationContext;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as PsrResponseInterface;

if (!function_exists('response')) {
    /**
     * 转换为hyperf响应输出
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function response(ResponseInterface $response)
    {
        $psrResponse = ApplicationContext::getContainer()->get(PsrResponseInterface::class);
        $psrResponse = $psrResponse->withBody(new SwooleStream((string)$response->getBody()))
            ->withStatus($response->getStatusCode());

        foreach ($response->getHeaders() as $key => $item) {
            $psrResponse = $psrResponse->withHeader($key, $item);
        }

        return $psrResponse;
    }
}