<?php

 function alta_usuario($conexion,$nuevoUsuario) { 
	$fechaNacimiento = date('d/m/Y', strtotime($nuevoUsuario["fechaNacimientoUsuario"]));
    try {
		$stmt = $conexion->prepare("CALL INSERTAR_USUARIOS(:dniUsuario,:nombreCompletoUsuario,:nickUsuario,:emailUsuario,:fechaNacimientoUsuario,:numTelefonoUsuario,:passUsuario,:confirmPassUsuario)");
		$dni=$nuevoUsuario['dniUsuario'];
		$nombre=$nuevoUsuario['nombreCompletoUsuario'];
		$nick=$nuevoUsuario['nickUsuario'];
		$email=$nuevoUsuario['emailUsuario'];
		$telef=$nuevoUsuario['numTelefonoUsuario'];
		$pass=$nuevoUsuario['passUsuario'];
		$confirm=$nuevoUsuario['confirmPassUsuario'];
		$stmt->bindParam(':dniUsuario',$dni);
		$stmt->bindParam(':nombreCompletoUsuario',$nombre);
		$stmt->bindParam(':nickUsuario',$nick);
		$stmt->bindParam(':emailUsuario',$email);
		$stmt->bindParam(':fechaNacimientoUsuario',$fechaNacimiento);
		$stmt->bindParam(':numTelefonoUsuario',$telef);
		$stmt->bindParam(':passUsuario',$pass);
		$stmt->bindParam(':confirmPassUsuario',$confirm);
		$stmt->execute();
		return true;
	}catch(PDOException $e ) {
		return false;
	}
 }
 
 function asignar_seguimiento_usuario($conexion,$dniUsuario,$dniJugador){
 	try{
		$stmt=$conexion->prepare("CALL INSERTAR_SEGUIMIENTOS(:dniUsuario,:dniJugador,NULL)");
		$stmt->bindParam(':dniUsuario',$dniUsuario);
		$stmt->bindParam(':dniJugador',$dniJugador);
		$stmt->execute();
		return true;
 	}catch(PDOException $e){
		return false;
 	}
 }

 function obten_dni_jugador($conexion,$nombreVirtualJugador,$oid_v){
	try{
		$consulta = "SELECT * FROM JUGADORES WHERE nombreVirtualJugador=:nombreVirtualJugador AND 
		oid_v=:oid_v";
	   $stmt = $conexion->prepare($consulta);
	   $stmt->bindParam(':nombreVirtualJugador',$nombreVirtualJugador);
	   $stmt->bindParam(':oid_v',$oid_v);
	   $stmt->execute();
	   return $stmt->fetch();
   }catch(PDOException $e) {
	   return null;
   }
}
 
 function consultar_usuario($conexion,$nickUsuario,$passUsuario) {
	try{
	 	$consulta = "SELECT * FROM USUARIOS WHERE nickUsuario=:nickUsuario AND 
	 	passUsuario=:passUsuario";
		$stmt = $conexion->prepare($consulta);
		$stmt->bindParam(':nickUsuario',$nickUsuario);
		$stmt->bindParam(':passUsuario',$passUsuario);
		$stmt->execute();
		return $stmt->fetch();
	}catch(PDOException $e) {
		return null;
    }
 }

 function consultar_seguimientos($conexion, $dniUsuario) {
	try{
		$consulta = "SELECT * FROM JUGADORES NATURAL JOIN SEGUIMIENTOS WHERE dniUsuario=:dniUsuario ";
		$stmt = $conexion->prepare($consulta);
		$stmt->bindParam(':dniUsuario',$dniUsuario);
		$stmt->execute();
		return $stmt;
	}catch(PDOException $e){
		return null;
	}   
 }

 function eliminar_seguimiento($conexion, $dniUser, $dniJugador){
	try{
		$consulta = "DELETE from seguimientos where dniusuario = :dniUser and dnijugador = :dniJugador and opinion is null";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':dniUser',$dniUser);
		$stmt->bindParam(':dniJugador',$dniJugador);
		$stmt->execute();
		return true;
 	}catch(PDOException $e){
 		return false;
 	}
 }
 
 function ayade_opinion($conexion, $dniusuario, $dnijugador, $opinion){
	try{
		$consulta = "CALL INSERTAR_SEGUIMIENTOS(:dniusuario,:dnijugador,:opinion)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':dniusuario',$dniusuario);
		$stmt->bindParam(':dnijugador',$dnijugador);
		$stmt->bindParam(':opinion', $opinion);
		$stmt->execute();
		return true;
 	}catch(PDOException $e){
 		return false;
	}
 }
 
function change_pass($conexion, $userDni, $newPass){
      	 	try{
       	 	    $consulta = "UPDATE USUARIOS SET PASSUSUARIO =: newpass WHERE DNIUSUARIO =: dni";
			    $stmt = $conexion->prepare($consulta);
			    $stmt->bindParam(':dni',$userDni);
				$stmt->bindParam(':newpass',$newPass);
				$stmt->execute();
				return true;
     	   }catch(PDOException $e){
			    return false;
     	   }  
    }

function change_c_pass($conexion, $userDni,$pass){
      	 	try{
       	 	    $consulta = "UPDATE USUARIOS SET CONFIRMPASSUSUARIO = :pass WHERE DNIUSUARIO = :dni";
				$stmt = $conexion->prepare($consulta);
				$stmt->bindParam(':dni',$userDni);
				$stmt->bindParam(':pass',$pass);
				$stmt->execute();
				return true;
     	   }catch(PDOException $e){
			    return false;
     	   }  
	}
	
function change_profile($conexion, $nombre, $nick, $mail, $numt, $dni){
      	 	try{
       	 	    $consultaNombre = "UPDATE USUARIOS SET NOMBRECOMPLETOUSUARIO = :nombre WHERE DNIUSUARIO = :dni";
				$stmt = $conexion->prepare($consultaNombre);
				$stmt->bindParam(':dni',$dni);
				$stmt->bindParam(':nombre',$nombre);
				$stmt->execute();
				
				$consultaNick = "UPDATE USUARIOS SET NICKUSUARIO = :nick WHERE DNIUSUARIO = :dni";
				$stmtN = $conexion->prepare($consultaNick);
				$stmtN->bindParam(':dni',$dni);
				$stmtN->bindParam(':nick',$nick);
				$stmtN->execute();
				
				
				$consultaMail = "UPDATE USUARIOS SET EMAILUSUARIO = :mail WHERE DNIUSUARIO = :dni";
				$stmtM = $conexion->prepare($consultaMail);
				$stmtM->bindParam(':dni',$dni);
				$stmtM->bindParam(':mail',$mail);
				$stmtM->execute();
				
				$consultaTel = "UPDATE USUARIOS SET NUMTELEFONOUSUARIO = :num WHERE DNIUSUARIO = :dni";
				$stmtT = $conexion->prepare($consultaTel);
				$stmtT->bindParam(':dni',$dni);
				$stmtT->bindParam(':num',$numt);
				$stmtT->execute();
				return true;
     	   }catch(PDOException $e){
			   return false;
     	   }  
	}
	
?>

