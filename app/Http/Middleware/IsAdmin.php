<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 检查用户是否登录
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        // 检查用户是否是管理员
        if (!auth()->user()->is_admin) {
            abort(403, '无权访问此页面');
        }
        
        return $next($request);
    }
}