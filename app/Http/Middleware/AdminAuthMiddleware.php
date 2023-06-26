<?php

namespace App\Http\Middleware;

use App\Utils\Constants;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN) {
            return redirect('admin');
        }

        return $next($request);
    }
}
