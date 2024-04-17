<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request, and make sure the user is administrator
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {
            if(Auth::user()->role_as == '1') //to check if user is admin role
            {
                return $next($request);
            }
            elseif (Auth::user()->role_as == '2')
            {
                session()->flash('type','danger');
                return redirect('/welcome')->with('message',"Access Denied! You don't have administrative permission!");
            }

        }
        else
        {
            session()->flash('type','danger');
            return redirect()->route('login')->with('message','Please Login First');
        }
    }
}
