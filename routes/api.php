<?php

include_once("includes/gestionBD.php");
include_once("includes/gestionarUsuarios.php");
include_once("includes/gestionJugadores.php");
include_once("tools/validate.php");
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Base uri
Route::middleware('auth:api');

//Recurso user
//-Crear usuario
Route::post('user', 'UserController@create');

//-Asignar seguimiento
Route::post('user/{dniUsuario}/{nickJugador}/{idVideojuego}', 'UserController@set');

//-Consultar datos del usuario
Route::get('user/{nickUsuario}/{passUsuario}', 'UserController@data');

//-Consultar seguimientos
Route::get('user/{dniUsuario}', 'UserController@getAll');

//-Eliminar seguimiento
Route::delete('user/{dniUsuario}/{nickJugador}/{idVideojuego}', 'UserController@delete');

//-Añadir opinion
Route::post('user/{opinion}/{dniUsuario}/{nickJugador}/{idVideojuego}', 'UserController@createComment');

//-Cambiar contraseña
Route::put('user/{dniUsuario}/{newPass}', 'UserController@updatePass');

//-Cambiar perfil
Route::put('user/{dniUsuario}/{nombreUsuario}/{nickUsuario}/{emailUsuario}/{telefonoUsuario}',
'UserController@updateProfile');

//Recurso jugadores
//-Listar todos los jugadores o mejores jugadores
Route::get('jugadores', 'PlayersController@getAll');