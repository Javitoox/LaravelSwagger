<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
     /**
     * @OA\Post(
     *      path="/user",
     *      operationId="create",
     *      tags={"Users"},
     *      summary="Create new user",
     *      description="Create new user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreUser")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User created successfully. For user info: GET user/{nickUsuario}/{passUsuario}"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="User already exists or validate errors"
     *      )
     * )
     */
    //Recurso user
    //-Crear usuario
    public function create(Request $request) {                                        
        $errores = validarDatosUsuario($request);
        if (count($errores)>0) {
            return response()->json($errores, 400);
        } else{
            $conexion = crearConexionBD();
            $alta = alta_usuario($conexion,$request);
            cerrarConexionBD($conexion);
            if($alta){
                return response()->json("User created successfully. For user info: 
                GET user/{nickUsuario}/{passUsuario}", 201);
            }else{
                return response()->json("User already exists", 400);
            }
        }
    }

    /**
     * @OA\Post(
     *      path="/user/{dniUsuario}/{nickJugador}/{idVideojuego}",
     *      operationId="set",
     *      tags={"Users"},
     *      summary="Asign player",
     *      description="Asign player",
     *      @OA\Parameter(
     *          name="dniUsuario",
     *          description="Dni of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="nickJugador",
     *          description="Nick of player",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="idVideojuego",
     *          description="Id of videogame",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Action successfully"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User or player not found"
     *      )
     * )
     */
    //-Asignar seguimiento
    public function set($dniUsuario, $nickJugador, 
    $idVideojuego) {
        $conexion = crearConexionBD();
        $resultado_jugador=obten_dni_jugador($conexion, $nickJugador, $idVideojuego);
        cerrarConexionBD($conexion);
        if(isset($resultado_jugador) && $resultado_jugador!=""){
            $conexion = crearConexionBD();
            $asignacion=asignar_seguimiento_usuario($conexion, $dniUsuario, 
            $resultado_jugador["DNIJUGADOR"]);
            cerrarConexionBD($conexion);
            if($asignacion){
                return response()->json("Action successfully", 201);
            }else{
                return response()->json("User not found", 404);
            }
        }else{
            return response()->json("Player not found", 404);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/{nickUsuario}/{passUsuario}",
     *      operationId="data",
     *      tags={"Users"},
     *      summary="Get data user",
     *      description="Get data user",
     *      @OA\Parameter(
     *          name="nickUsuario",
     *          description="Nick of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="passUsuario",
     *          description="Pass of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     *     )
     */
    //-Consultar datos del usuario
    public function data($nickUsuario, $passUsuario) {
        $conexion = crearConexionBD();
        $resultado=consultar_usuario($conexion, $nickUsuario, $passUsuario);
        cerrarConexionBD($conexion);
        if(isset($resultado) && $resultado!=""){
            $res=array();
            $res["DNI"]=$resultado["DNIUSUARIO"];
            $res["NOMBRE"]=$resultado["NOMBRECOMPLETOUSUARIO"];
            $res["NICK"]=$resultado["NICKUSUARIO"];
            $res["EMAIL"]=$resultado["EMAILUSUARIO"];
            $res["FECHA_NACIMIENTO"]=$resultado["FECHANACIMIENTOUSUARIO"];
            $res["TELEFONO"]=$resultado["NUMTELEFONOUSUARIO"];
            $res["CONTRASEÑA"]=$resultado["PASSUSUARIO"];
            return response()->json($res, 200);
        }else{
            return response()->json("User not found", 404);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/{dniUsuario}",
     *      operationId="getAll",
     *      tags={"Users"},
     *      summary="Get tracings",
     *      description="Get tracings",
     *      @OA\Parameter(
     *          name="dniUsuario",
     *          description="Dni of user",
     *          required=true,
     *          in="path",
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
     *          description="Incorret value for order"
     *      )
     *     )
     */
    //-Consultar seguimientos
    public function getAll(Request $request, $dniUsuario) {
        $search = $request->input('search'); //Query param
        $order = $request->input('order'); //Query param
        $conexion = crearConexionBD();
        $resultado=consultar_seguimientos($conexion, $dniUsuario);
        cerrarConexionBD($conexion);
        $jugadores_array= array();
        foreach($resultado as $jug){
            if(isset($search)){
                if(strpos(" ".$jug["NOMBREVIRTUALJUGADOR"], $search))
                    $jugadores_array[]=$jug["NOMBREVIRTUALJUGADOR"]."(".$jug["OID_V"]."): ".$jug["OPINION"];
            }else{
                $jugadores_array[]=$jug["NOMBREVIRTUALJUGADOR"]."(".$jug["OID_V"]."): ".$jug["OPINION"];
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

    /**
     * @OA\Delete(
     *      path="/user/{dniUsuario}/{nickJugador}/{idVideojuego}",
     *      operationId="delete",
     *      tags={"Users"},
     *      summary="Delete tracing",
     *      description="Delete tracing",
     *      @OA\Parameter(
     *          name="dniUsuario",
     *          description="Dni of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="nickJugador",
     *          description="Nick of player",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="idVideojuego",
     *          description="Id of videogame",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Delete successfully"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User or player not found"
     *      )
     * )
     */
    //-Eliminar seguimiento
    public function delete($dniUsuario, $nickJugador, 
    $idVideojuego) {
        $conexion = crearConexionBD();
        $resultado_jugador=obten_dni_jugador($conexion, $nickJugador, $idVideojuego);
        cerrarConexionBD($conexion);
        if(isset($resultado_jugador) && $resultado_jugador!=""){
            $conexion = crearConexionBD();
            $asignacion=eliminar_seguimiento($conexion, $dniUsuario, 
            $resultado_jugador["DNIJUGADOR"]);
            cerrarConexionBD($conexion);
            if($asignacion){
                return response()->json("Delete successfully", 201);
            }else{
                return response()->json("User not found", 404);
            }
        }else{
            return response()->json("Player not found", 404);
        }
    }

    /**
     * @OA\Post(
     *      path="/user/{opinion}/{dniUsuario}/{nickJugador}/{idVideojuego}",
     *      operationId="createComment",
     *      tags={"Users"},
     *      summary="Add comment",
     *      description="Add comment",
     *      @OA\Parameter(
     *          name="opinion",
     *          description="Comment",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="dniUsuario",
     *          description="Dni of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="nickJugador",
     *          description="Nick of player",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="idVideojuego",
     *          description="Id of videogame",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Action successfully"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User or player not found"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validate errors"
     *      )
     * )
     */
    //-Añadir opinion
    public function createComment($opinion, $dniUsuario, 
    $nickJugador, $idVideojuego) {
        $conexion = crearConexionBD();
        $resultado_jugador=obten_dni_jugador($conexion, $nickJugador, $idVideojuego);
        cerrarConexionBD($conexion);
        if(isset($resultado_jugador) && $resultado_jugador!=""){
            $errores = validarOpinion($opinion);
            if (count($errores)>0) {
                return response()->json($errores, 400);
            }else{
                $conexion = crearConexionBD();
                $ayade=ayade_opinion($conexion, $dniUsuario, $resultado_jugador["DNIJUGADOR"], $opinion);
                cerrarConexionBD($conexion);
                if($ayade){
                    return response()->json("Action successfully", 201);
                }else{
                    return response()->json("User not found", 404);
                }
            }
        }else{
            return response()->json("Player not found", 404);
        }
    }

    /**
     * @OA\Put(
     *      path="/user/{dniUsuario}/{newPass}",
     *      operationId="updatePass",
     *      tags={"Users"},
     *      summary="Update pass",
     *      description="Update pass",
     *      @OA\Parameter(
     *          name="dniUsuario",
     *          description="Dni of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="newPass",
     *          description="New pass",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Pass update successfully"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Update error or validate errors"
     *      )
     * )
     */
    //-Cambiar contraseña
    public function updatePass($dniUsuario, $newPass) {
        $errores = validarPass($newPass);
        if (count($errores)>0) {
            return response()->json($errores, 400);
        }else{
            $conexion = crearConexionBD();
            $cambio=change_pass($conexion, $dniUsuario, $newPass);
            cerrarConexionBD($conexion);
            if($cambio){
                $conexion = crearConexionBD();
                change_c_pass($conexion, $dniUsuario, $newPass);
                cerrarConexionBD($conexion);
                return response()->json("Pass update successfully", 201);
            }else{
                return response()->json("Update error", 400);
            }
        }
    }

    /**
     * @OA\Put(
     *      path="/user/{dniUsuario}/{nombreUsuario}/{nickUsuario}/{emailUsuario}/{telefonoUsuario}",
     *      operationId="updateProfile",
     *      tags={"Users"},
     *      summary="Update profile",
     *      description="Update profile",
     *      @OA\Parameter(
     *          name="dniUsuario",
     *          description="Dni of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="nombreUsuario",
     *          description="Name of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="nickUsuario",
     *          description="Nick of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="emailUsuario",
     *          description="Email of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="telefonoUsuario",
     *          description="Number phone of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Profile update successfully"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Update error or validate errors"
     *      )
     * )
     */
    //-Cambiar perfil
    public function updateProfile($dniUsuario, $nombreUsuario, $nickUsuario, $emailUsuario, 
    $telefonoUsuario) {
        $errores = validarPerfil($nombreUsuario, $nickUsuario, $emailUsuario, $telefonoUsuario);
        if (count($errores)>0) {
            return response()->json($errores, 400);
        }else{
            $conexion = crearConexionBD();
            $cambio=change_profile($conexion, $nombreUsuario, $nickUsuario, $emailUsuario, $telefonoUsuario,
            $dniUsuario);
            cerrarConexionBD($conexion);
            if($cambio){
                return response()->json("Profile update successfully", 201);
            }else{
                return response()->json("Update error", 400);
            }
        }
    }
}
