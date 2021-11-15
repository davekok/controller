<?php

declare(strict_types=1);

namespace davekok\controller;

use davekok\http\HttpReader;
use davekok\http\HttpRequest;
use davekok\http\HttpRequestHandler;
use davekok\http\HttpResponse;
use davekok\http\HttpStatus;
use davekok\http\HttpWriter;
use davekok\lalr1\ParserException;
use davekok\lalr1\EmptySolutionParserException;
use davekok\stream\Activity;
use davekok\stream\ReaderException;
use Throwable;

class MainController
{
    public function __construct(private ControllerConfig $config) {}

    public function handleRequest(HttpRequest $request): ?HttpResponse
    {
        return match ($request->method) {
            "HEAD"    => $this->handleHeadRequest($request),
            "GET"     => $this->handleGetRequest($request),
            "OPTIONS" => $this->handleOptionsRequest($request),
            default   => null,
        };
    }

    public function handleHeadRequest(HttpRequest $request): HttpResponse
    {
        return new HttpResponse(
            status:  HttpStatus::OK,
            headers: [
                "Content-Type"   => 'text/html; charset="UTF-8"',
                "Content-Length" => strlen($this->config->body),
            ],
        );
    }

    public function handleOptionsRequest(HttpRequest $request): HttpResponse
    {
        return new HttpResponse(
            status:  HttpStatus::NO_CONTENT,
            headers: ["Allow" => "OPTIONS, GET, HEAD, POST"],
        );
    }

    public function handleGetRequest(HttpRequest $request): ?HttpResponse
    {
        if (isset($request->headers["Connection"]) && $request->headers["Connection"] === "upgrade" && isset($request->headers["Upgrade"])) {
            return $this->handleUpgradeRequeset($request);
        }
        return new HttpResponse(
            status:  HttpStatus::OK,
            headers: ["Content-Type" => 'text/html; charset="UTF-8"'],
            body:    $this->config->body,
        );
    }

    public function handleUpgradeRequest(HttpRequest $request): ?HttpResponse
    {
        // TODO:
        return null;
        // return match ($request->headers["Upgrade"]) {
        //     "websocket" => $this->handleWebSocketUpgradeRequest($request),
        // };
    }
}
