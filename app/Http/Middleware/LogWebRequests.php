<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogWebRequests
{
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $isLivewire = str_contains($path, 'livewire');
        $isInscription = str_contains($path, 'inscription');

        if ($isLivewire || $isInscription) {
            Log::info('HTTP IN', [
                'method' => $request->getMethod(),
                'path' => $path,
                'ip' => $request->ip(),
                'ua' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
                'x-livewire' => $request->headers->get('x-livewire'),
                'x-csrf-token-len' => strlen((string) $request->headers->get('x-csrf-token')),
                'session_has_token' => $request->session()->has('_token'),
                'content_type' => $request->headers->get('content-type'),
                'content_length' => (int) $request->headers->get('content-length', 0),
            ]);

            // Log a small preview of the body (avoid huge payloads)
            try {
                $raw = $request->getContent();
                if ($raw !== '' && $raw !== null) {
                    Log::debug('HTTP BODY PREVIEW', [
                        'path' => $path,
                        'preview' => substr($raw, 0, 2048),
                    ]);
                }
            } catch (\Throwable $e) {
                // ignore body read errors
            }
        }

        $response = $next($request);

        if ($isLivewire || $isInscription) {
            Log::info('HTTP OUT', [
                'method' => $request->getMethod(),
                'path' => $path,
                'status' => $response->getStatusCode(),
                'content_type' => $response->headers->get('content-type'),
            ]);
        }

        return $response;
    }
}





