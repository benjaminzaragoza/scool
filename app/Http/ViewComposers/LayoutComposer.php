<?php

namespace App\Http\ViewComposers;

use App\Models\User;
use Auth;
use Illuminate\View\View;

/**
 * Class LayoutComposer.
 *
 * @package App\Http\ViewComposers
 */
class LayoutComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::user() && Auth::user()->isSuperAdmin()) {
            $view->with('users', User::all()->filter(function($user) {
                return $user->canBeImpersonated();
            })->values());
        }
    }
}
