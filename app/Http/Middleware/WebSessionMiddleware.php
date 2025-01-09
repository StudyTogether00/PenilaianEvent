<?php

namespace App\Http\Middleware;

use App\Http\Controllers\FE\RouteController;
use Closure;
use Illuminate\Http\Request;

class WebSessionMiddleware extends RouteController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->checksession($request)) {
            return redirect("Login");
        }
        return $next($request);
    }
}
