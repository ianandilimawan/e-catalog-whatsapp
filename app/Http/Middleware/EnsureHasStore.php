<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasStore
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $allowedRoutes = [
                'admin.logout',
                'admin.stores.index',
                'admin.stores.create',
                'admin.stores.store',
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                $storeCount = \App\Models\Store::where('user_id', auth()->id())->count();
                
                if ($storeCount == 0) {
                    return redirect()->route('onboarding.store')
                        ->with('error', 'Silakan setup toko Anda terlebih dahulu.');
                }
            }
        }

        return $next($request);
    }
}
