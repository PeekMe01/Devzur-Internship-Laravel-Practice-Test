<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCartCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(count(Auth::user()->cart->products)==0){
            $notification = [
                'message' => 'You must at least have something in your cart.',
                'alert-type' => 'error'
            ];
            return redirect()->route('shop')->with($notification);
        }
        return $next($request);
    }
}
