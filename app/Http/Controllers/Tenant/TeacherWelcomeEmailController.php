<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeacherWelcomeEmail;
use App\Mail\TeacherWelcome;

/**
 * Class TeacherWelcomeEmailController.
 *
 * @package App\Http\Controllers\Tenant
 */
class TeacherWelcomeEmailController extends Controller
{
    public function show(ShowTeacherWelcomeEmail $request)
    {
//        $invoice = App\Invoice::find(1);
//        return new App\Mail\InvoicePaid($invoice);
        return new TeacherWelcome();
//
//        dump('asd');
//        return view();
    }
}
