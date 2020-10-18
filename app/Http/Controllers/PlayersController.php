<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayersController extends Controller
{

    /**
     * @OA\Get(
     *      path="/jugadores",
     *      operationId="getAll",
     *      tags={"Players"},
     *      summary="Get list of players or best players",
     *      description="Returns list of players or best players",
     *      @OA\Parameter(
     *          name="bests",
     *          description="Best players",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          description="Search players",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="order",
     *          description="Order players",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Incorret value for order",
     *      )
     *     )
     */
    //Recurso jugadores
    //-Listar todos los jugadores o mejores jugadores
    public function getAll(Request $request) {
        $bests = $request->input('bests'); //Query param
        $search = $request->input('search'); //Query param
        $order = $request->input('order'); //Query param
        $conexion = crearConexionBD();
        if($bests == "true"){
            $jugadores = listar_mejores_jugadores($conexion);
        }else{
            $jugadores = obten_jugador($conexion);
        }
        cerrarConexionBD($conexion);
        $jugadores_array= array();
        foreach($jugadores as $jug){
            if(isset($search)){
                if(strpos(" ".$jug["NOMBREVIRTUALJUGADOR"], $search))
                    $jugadores_array[]=$jug["NOMBREVIRTUALJUGADOR"]."(".$jug["OID_V"].")";
            }else{
                $jugadores_array[]=$jug["NOMBREVIRTUALJUGADOR"]."(".$jug["OID_V"].")";
            }
        }
        if(isset($order)){
            if($order=="alfabetic")
                sort($jugadores_array);
            else if($order=="-alfabetic")
                rsort($jugadores_array);
            else
                return response()->json("Incorret value for order", 400);
        }
        return response()->json($jugadores_array, 200);
    }
}
