<?php
/**
 * Created by PhpStorm.
 * User: h2 gaming
 * Date: 8/17/2019
 * Time: 11:05 AM
 */
namespace Modules\User\Controllers\Vendors;

use Modules\FrontendController;

class PayoutController extends FrontendController
{

    public function index(){
        return view('User::frontend.vendors.payout.index');
    }
}