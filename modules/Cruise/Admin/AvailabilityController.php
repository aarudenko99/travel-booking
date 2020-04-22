<?php
/**
 * Created by PhpStorm.
 * User: h2 gaming
 * Date: 7/30/2019
 * Time: 7:19 PM
 */
namespace Modules\Cruise\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceDate;

class AvailabilityController extends \Modules\Cruise\Controllers\AvailabilityController
{
    protected $spaceClass;
    /**
     * @var SpaceDate
     */
    protected $spaceDateClass;
    protected $indexView = 'Cruise::admin.availability';

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/cruise');
        $this->middleware('dashboard');
    }

}
