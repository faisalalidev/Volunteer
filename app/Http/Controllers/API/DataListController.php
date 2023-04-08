<?php

namespace App\Http\Controllers\API;

use ApiHelper;
use App\Http\Controllers\Controller;
use Botble\Api\Http\Resources\UserResource;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Api\Http\Requests\LoginRequest;
use Botble\Api\Http\Requests\RegisterRequest;
use Botble\Jk\Models\Jk;
use Botble\Jk\Repositories\Interfaces\JkInterface;
use Botble\Jk\Tables\JkTable;
use Botble\Region\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}