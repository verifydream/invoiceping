<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFreemiumLimit
{
    public function handle(Request $request, Closure $next)
    {
        $limit = config('services.freemium.limit', 30);
        $invoiceCount = $request->user()->invoices()->count();

        if ($invoiceCount >= $limit) {
            return back()->with('error', "Free plan limit reached ({$limit} invoices). Upgrade to Pro for unlimited.");
        }

        return $next($request);
    }
}
