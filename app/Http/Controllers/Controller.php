<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
     /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="UDC API",
     *      description="Api for control users and players",
     *      @OA\Contact(
     *          email="javikuka7@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url="http://localhost:8000/api",
     *      description="API Server"
     * )

     *
     * @OA\Tag(
     *     name="Users",
     *     description="Users"
     * )
     * 
     * @OA\Tag(
     *     name="Players",
     *     description="Players"
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
