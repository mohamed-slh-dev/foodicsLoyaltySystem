<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use JWTAuth;


class AssignGuard extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard != null){
            auth()->shouldUse($guard);
           // return response()->json(['error' => $guard]);
            $token = $request->header('token');
            $request->headers->set('token', $token);
            $request->headers->set('Authorization', 'Bearer '.$token);
            try{
              $user = JWTAuth::parseToken()->authenticate();
            }catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
                return response()->json([
                    'data' => '',
                    'error' => 'true',
                    'message' => 'Unauthenticated'
                ]);
            }catch(\Tymon\JWTAuth\Exceptions\JWTException $e){                
                return response()->json([
                    'data' => '',
                    'error' => 'true',
                    'message' => $e->getMessage()
                ]);
                
            }

            return $next($request);
        }else{
          return response()->json([
              'status'=>'false',
              'msg' => 'Unauthenticated!'
              ]);
        }
          
     
       
    }
}
