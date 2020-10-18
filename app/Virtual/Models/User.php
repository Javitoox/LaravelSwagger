<?php
/**
 * @OA\Schema(
 *      title="User",
 *      description="Data user",
 *      type="object",
 *      required={"dniUsuario","nombreCompletoUsuario","nickUsuario","emailUsuario",
 *      "fechaNacimientoUsuario","numTelefonoUsuario","passUsuario","confirmPassUsuario"}
 * )
 */

class User
{
    /**
     * @OA\Property(
     *      title="DNI",
     *      example="64767587G"
     * )
     *
     * @var string
     */
    public $DNI;

    /**
     * @OA\Property(
     *      title="NOMBRE",
     *      example="Pepe de la Torre"
     * )
     *
     * @var string
     */
    public $NOMBRE;

    /**
     * @OA\Property(
     *      title="NICK",
     *      example="Pepexxii"
     * )
     *
     * @var string
     */
    public $NICK;

    /**
     * @OA\Property(
     *      title="EMAIL",
     *      example="pepexii@gmail.com"
     * )
     *
     * @var string
     */
    public $EMAIL;

    /**
     * @OA\Property(
     *      title="FECHA_NACIMIENTO",
     *      example="2002-08-12"
     * )
     *
     * @var string
     */
    public $FECHA_NACIMIENTO;

    /**
     * @OA\Property(
     *      title="TELEFONO",
     *      example="645576789"
     * )
     *
     * @var string
     */
    public $TELEFONO;

    /**
     * @OA\Property(
     *      title="CONTRASEÑA",
     *      example="Constantina2020"
     * )
     *
     * @var string
     */
    public $CONTRASEÑA;
}
?>