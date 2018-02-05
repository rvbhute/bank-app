<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->input('account_id');
        $active = DB::table('accounts')->where('id', $id)->value('active');

        if (!$active) {
            return response()->json(['message' => 'Account closed'], 403);
        }

        return $next($request);
    }
}
