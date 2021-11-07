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
use Psr\Log\LoggerInterface;
use Throwable;

class HttpController implements HttpRequestHandler
{
    public function __construct(
        private RouteControllerFactory $routeControllerFactory,
        private Activity $activity,
        private HttpReader $reader,
        private HttpWriter $writer,
    ) {
        // $this->activity->enableCrypto(true, STREAM_CRYPTO_METHOD_TLSv1_2_SERVER);
        $this->reader->receive($this);
    }

    public function handleRequest(HttpRequest|ParserException|ReaderException $request): void
    {
        if ($request instanceof EmptySolutionParserException) {
            $this->activity->close();
            return;
        }
        if ($request instanceof Throwable) {
            $this->writer->send(new HttpResponse(
                status: HttpStatus::INTERNAL_SERVER_ERROR,
                protocolVersion: $request->protocolVersion,
                body: $request->getMessage()
            ));
            $this->activity->andThenClose();
            return;
        }
        $response = match ($request->url->path) {
            "/" => $this->routeControllerFactory->createMainController()->handleRequest($request),
            default => null,
        } ?? new HttpResponse(
            status: HttpStatus::NOT_FOUND,
            body: "Not found"
        );
        $headers           = $response->headers;
        $headers["Date"]   = date("r");
        $headers["Server"] = "davekok/controller";
        if ($response->body !== null) {
            $headers["Content-Length"] = strlen($response->body);
            if (isset($headers["Content-Type"]) === false) {
                $headers["Content-Type"] = 'text/plain; charset="UTF-8"';
            }
        }

        $this->writer->send(new HttpResponse(
            status:          $response->status,
            protocolVersion: $request->protocolVersion,
            headers:         $headers,
            body:            $response->body,
        ));

        // get ready for next request
        $this->reader->receive($this);
    }
}
