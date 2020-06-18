<?php
/**
 * Plugin
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    RENT&RIDE
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
 
namespace Plugins\VehicleTaxes\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleTaxes\Model\VehicleTypeTax;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleTaxes\Transformers\VehicleTypeTaxTransformer;

/**
 * VehicleTaxes resource representation.
 * @Resource("VehicleTaxes")
 */
class VehicleTypeTaxesController extends Controller
{
    /**
     * VehicleTaxesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all taxes
     * Get a JSON representation of all the taxes.
     *
     * @Get("/taxes?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the taxes list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort taxes by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTaxes.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('tax', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_taxes = VehicleTypeTax::with($enabled_includes)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_taxes, (new VehicleTypeTaxTransformer)->setDefaultIncludes($enabled_includes));
    }
}
