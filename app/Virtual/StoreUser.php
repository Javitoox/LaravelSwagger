<?php
/**
 * @OA\Schema(
 *      title="StoreUser",
 *      description="Store a new User",
 *      type="object",
 *      required={"dniUsuario","nombreCompletoUsuario","nickUsuario","emailUsuario",
 *      "fechaNacimientoUsuario","numTelefonoUsuario","passUsuario","confirmPassUsuario"}
 * )
 */

class StoreUser
{
    /**
     * @OA\Property(
     *      title="dniUsuario",
     *      example="64767587G"
     * )
     *
     * @var string
     */
    public $dniUsuario;

    /**
     * @OA\Property(
     *      title="nombreCompletoUsuario",
     *      example="Pepe de la Torre"
     * )
     *
     * @var string
     */
    public $nombreCompletoUsuario;

    /**
     * @OA\Property(
     *      title="nickUsuario",
     *      example="Pepexxii"
     * )
     *
     * @var string
     */
    public $nickUsuario;

    /**
     * @OA\Property(
     *      title="emailUsuario",
     *      example="pepexii@gmail.com"
     * )
     *
     * @var string
     */
    public $emailUsuario;

    /**
     * @OA\Property(
     *      title="fechaNacimientoUsuario",
     *      example="2002-08-12"
     * )
     *
     * @var string
     */
    public $fechaNacimientoUsuario;

    /**
     * @OA\Property(
     *      title="numTelefonoUsuario",
     *      example="645576789"
     * )
     *
     * @var string
     */
    public $numTelefonoUsuario;

    /**
     * @OA\Property(
     *      title="passUsuario",
     *      example="Constantina2020"
     * )
     *
     * @var string
     */
    public $passUsuario;

    /**
     * @OA\Property(
     *      title="confirmPassUsuario",
     *      example="Constantina2020"
     * )
     *
     * @var string
     */
    public $confirmPassUsuario;
}
?>