<?php
function validarDatosUsuario($nuevoUsuario){
        $errores=array();
	
	//Validación NIF
	if($nuevoUsuario["dniUsuario"]=="") 
		$errores[] = "El NIF no puede estar vacío.";
	else if(!preg_match("/^[0-9]{8}[A-Z]$/", $nuevoUsuario["dniUsuario"])){
		$errores[] = "El NIF debe contener 8 números y una letra mayúscula: " . $nuevoUsuario["dniUsuario"]. ".";
	}
	//Validación Nombre			
	if($nuevoUsuario["nombreCompletoUsuario"]=="") 
		$errores[] = "El nombre no puede estar vacío.";
	
	//Validación Nick			
	if($nuevoUsuario["nickUsuario"]=="") 
		$errores[] = "El nick no puede estar vacío.";	
	
	//Validación Email
	if($nuevoUsuario["emailUsuario"]==""){ 
		$errores[] = "El email no puede estar vacío.";
	}else if(!filter_var($nuevoUsuario["emailUsuario"], FILTER_VALIDATE_EMAIL)){
		$errores[] = "El email es incorrecto: " . $nuevoUsuario["emailUsuario"]. ".";
	}
	
	//Validación Fecha Nacimiento
	$fechaNacimiento = getFechaFormateada($nuevoUsuario["fechaNacimientoUsuario"]);
	$fechaActual =  getFechaFormateada('now');

	if($nuevoUsuario["fechaNacimientoUsuario"]==""){ 
		$errores[] = "La fecha de nacimiento no puede estar vacía.";
	}else if($fechaNacimiento > $fechaActual){
		$errores[] = "La fecha de nacimiento " . $fechaNacimiento. " no puede ser posterior a la fecha actual ". $fechaActual . ".";
	}

	//Validación Número Telefónico
	if($nuevoUsuario["numTelefonoUsuario"]==""){ 
		$errores[] = "El número de teléfono no puede estar vacío.";
	}else if(!preg_match('/^[0-9]{9}$/', $nuevoUsuario["numTelefonoUsuario"])){
		$errores[] = "El número de teléfono es incorrecto: " . $nuevoUsuario["numTelefonoUsuario"]. ".";
	}
		
	//Validación Contraseña
	if(!isset($nuevoUsuario["passUsuario"]) || strlen($nuevoUsuario["passUsuario"]) < 8){
		$errores [] = "Contraseña no válida: debe tener al menos 8 caracteres.";
	}else if(!preg_match("/[a-z]+/", $nuevoUsuario["passUsuario"]) || 
		!preg_match("/[A-Z]+/", $nuevoUsuario["passUsuario"]) || !preg_match("/[0-9]+/", $nuevoUsuario["passUsuario"])){
		$errores[] = "Contraseña no válida: debe contener letras mayúsculas y minúsculas y dígitos.";
	
	//Validación Confirmación contraseña
	}else if($nuevoUsuario["passUsuario"] != $nuevoUsuario["confirmPassUsuario"]){
		$errores[] = "La confirmación de contraseña no coincide con la contraseña.";
	}
	return $errores;
	}

	function getFechaFormateada($fecha){ 
		$fechaNacimientoUsuario = date('Y/m/d', strtotime($fecha));	
		return $fechaNacimientoUsuario;
	}
	
	function validarOpinion($opinion){
		$errores=array();
		
		if($opinion=="" || $opinion==" "){
			$errores[] = "La opinion no puede ser vacía.";
		}else if(!preg_match("/^[^$%&|<>#()¬·{}~;ºª]*$/",  $opinion)){
			$errores[] = "La opinion no debe contener caracteres especiales: " . $opinion. ".";
		}

		return $errores;
	}

	function validarPass($passUsuario){
		$errores=array();

		if(!isset($passUsuario) || strlen($passUsuario) < 8){
			$errores [] = "Contraseña no válida: debe tener al menos 8 caracteres.";
		}else if(!preg_match("/[a-z]+/", $passUsuario) || 
			!preg_match("/[A-Z]+/", $passUsuario) || !preg_match("/[0-9]+/", $passUsuario)){
			$errores[] = "Contraseña no válida: debe contener letras mayúsculas y minúsculas y dígitos.";
		}

		return $errores;
	}

	function validarPerfil($nombreUsuario, $nickUsuario, $emailUsuario, $telefonoUsuario){
		$errores=array();
		
		if($nombreUsuario=="") 
			$errores[] = "El nombre no puede estar vacío.";
		
		if($nickUsuario=="") 
			$errores[] = "El nick no puede estar vacío.";	

		if($emailUsuario==""){ 
			$errores[] = "El email no puede estar vacío.";
		}else if(!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)){
			$errores[] = "El email es incorrecto: " . $emailUsuario. ".";
		}

		if($telefonoUsuario==""){ 
			$errores[] = "El número de teléfono no puede estar vacío.";
		}else if(!preg_match('/^[0-9]{9}$/', $telefonoUsuario)){
			$errores[] = "El número de teléfono es incorrecto: " . $telefonoUsuario. ".";
		}
		
		return $errores;
	}

?>