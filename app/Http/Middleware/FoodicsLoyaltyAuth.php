<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FoodicsLoyaltyAuth
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
        try {
            $headers = getallheaders();

            $token = explode(' ', $headers['Authorization'])[1];

            if ($token !== config('foodics.foodics_loyalty_token')) {
                return response()->json([], ResponseAlias::HTTP_UNAUTHORIZED);
            }

        } catch (\Exception $exception) {
            return response()->json([], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
