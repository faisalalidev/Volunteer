<?php

namespace App\Http\Controllers\API;

use ApiHelper;
use App\Http\Controllers\Controller;
use Botble\Api\Http\Resources\UserResource;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Api\Http\Requests\LoginRequest;
use Botble\Api\Http\Requests\RegisterRequest;
use Botble\Department\Models\Department;
use Botble\Jk\Models\Jk;
use Botble\Jk\Repositories\Interfaces\JkInterface;
use Botble\Jk\Tables\JkTable;
use Botble\Region\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataListController extends Controller
{
    public function __construct(JkInterface $jkRepository)
    {
        $this->jkRepository = $jkRepository;
    }

    public function jkList(BaseHttpResponse $response, Request $request)
    {
        $jk = Jk::get();
        if($request->region){
         $jk = $jk->where('region_id', $request->region);
        }
        return $response
            ->setData([
                'jk' => $jk
            ]);
    }
    public function regionList(BaseHttpResponse $response)
    {
        $region = Region::with('jk')->get();
        return $response
            ->setData([
                'region' => $region
            ]);
    }

    public function departmentList(BaseHttpResponse $response)
    {
        $department = Department::get();
        return $response
            ->setData([
                'department' => $department
            ]);
    }


    public function checkin(Request $request, BaseHttpResponse $response)
    {
        $data = $request->all();
        $checkin = DB::table('checkin')->insert($data);
        return $response
            ->setData([
                'checkin' => $checkin
            ]);

    }
}
