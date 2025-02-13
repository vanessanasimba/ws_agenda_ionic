<?php
include('config.php');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');
header('ContentType:application/json; charset=utf-8');
$post = json_decode(file_get_contents("php://input"), true);

if ($post['accion']=='login'){
    $sentecia = sprintf("Select * from persona where ci_persona='%s' and clave_persona='%s' and bloqueo = 0",$post['usuario'],$post['clave']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){

        while($row = mysqli_fetch_array(result: $rs)){
            $datos= array(
                'codigo' =>  $row['cod_persona'],
                'nombre' => $row['nom_persona']." ".$row['ape_persona']
            );
        }
        $respuesta = json_encode(array('estado'=>true,'persona'=>$datos));
    }else{
        $respuesta = json_encode(array('estado'=>false, 'mensaje'=>"Error de usuario o clave, o se encuetntra bloqueado"));
    }
    echo $respuesta;
}

if ($post['accion']=='listar'){
    $sentecia = sprintf("Select * from persona");
    $rs=mysqli_query($mysqli, $sentecia);
    if (mysqli_num_rows($rs)>0){
        while($row = mysqli_fetch_array(result: $rs)){
            $datos[] = array(
                'codigo' =>  $row['cod_persona'],
                'nombre' => $row['nom_persona']." ".$row['ape_persona'],
                'ci' => $row['ci_persona'],
                'telefono' => $row['telefono_persona'],
                'direccion' => $row['direccion_persona'],
                'clave' => $row['clave_persona']
            );
        }
        $respuesta = json_encode(array('estado'=>true, 'persona'=>$datos));
    }else{
        $respuesta = json_encode(array('estado'=>false, 'mensaje'=>"Error de usuario o clave"));
    }
    echo $respuesta;
}

if ($post['accion']=='vcedula')
{
    $sentecia=sprintf("select cod_persona from persona where ci_persona=%s",$post['cedula']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"Cedula existente en el sistema"));
    }else{
        $respuesta = json_encode(array('estado'=>false));
    }
    echo $respuesta;
}

if ($post['accion']=='cuenta')
{
    $sentecia=sprintf("INSERT INTO persona(ci_persona, nom_persona, ape_persona, clave_persona, correo_persona) VALUES ('%s','%s','%s','%s','%s')",$post['cedula'], $post['nombre'], $post['apellido'], $post['clave'], $post['correo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"datos guardados"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error'));
    }
    echo $respuesta;
}

if ($post['accion']=='datoPerfil')
{
    $sentecia=sprintf("select * from persona where cod_persona='%s'",$post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        $row = mysqli_fetch_assoc(result:$rs);
        $datos = array(
            'codigo' =>  $row['cod_persona'],
            'nombre' =>  $row['nom_persona'],
            'apellido' =>  $row['ape_persona'],
            'cedula' =>  $row['ci_persona'],
            'clave' => $row['clave_persona'],
            'correo' => $row['correo_persona']
        );
        $respuesta = json_encode(array('estado'=>true,'persona'=>$datos));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Datos no encontrados'));
    }
    echo $respuesta;
}

if ($post['accion']=='actualizar')
{
    $sentecia=sprintf("UPDATE persona SET ci_persona='%s',nom_persona='%s',ape_persona='%s',correo_persona='%s' WHERE cod_persona='%s' " ,$post['cedula'], $post['nombre'], $post['apellido'], $post['correo'],$post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"datos actulizados"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar'));
    }
    echo $respuesta;
}

if ($post['accion']=='bloqueo')
{
    $sentecia=sprintf("UPDATE persona SET bloqueo=1 WHERE ci_persona=%s",$post['cedula']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"Usuario bloqueado"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar'));
    }
    echo $respuesta;
}

if ($post['accion']=='recuperar')
{
    $sentecia=sprintf("UPDATE persona SET bloqueo=0, clave_persona='%s'  WHERE ci_persona=%s",$post['clave'],$post['cedula']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"Contraseña recuperda"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar'));
    }
    echo $respuesta;
}

if ($post['accion']=='consultar')
{
    $sentecia=sprintf("Select * from contacto where  persona_cod_persona='%s'",$post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        while($row = mysqli_fetch_array(result: $rs)){
            $datos[] = array(
                'codigo' =>  $row['cod_contacto'],
                'nombre' =>  $row['nom_contacto']." ".$row['ape_contacto'],
                'telefono' =>  $row['telefono_contacto'],
                'persona' =>  $row['persona_cod_persona']
            );
        }
        $respuesta = json_encode(array('estado'=>true,'contactos'=>$datos));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'No existe regitros'));
    }
    echo $respuesta;
}

if ($post['accion']=='dato')
{
    $sentecia=sprintf("select * from contacto where cod_contacto=%s",$post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        $row = mysqli_fetch_assoc(result:$rs);
        $dato = array(
            'codigo' =>  $row['cod_contacto'],
            'nombre' =>  $row['nom_contacto'],
            'apellido' =>  $row['ape_contacto'],
            'correo' => $row['email_contacto'],
            'telefono' => $row['telefono_contacto'],
            'persona' => $row['persona_cod_persona']
        );
        $respuesta = json_encode(array('estado'=>true,'contacto'=>$dato));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Datos no encontrados'));
    }
    echo $respuesta;
}

if ($post['accion']=='nuevoc')
{
    $sentecia=sprintf("INSERT INTO contacto(nom_contacto, ape_contacto, telefono_contacto, email_contacto, persona_cod_persona) VALUES ('%s','%s','%s','%s','%s')",$post['nombre'], $post['apellido'],$post['telefono'], $post['mail'], $post['persona']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"datos guardados"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error'));
    }
    echo $respuesta;
}

if ($post['accion']=='vtelefono')
{
    $sentecia=sprintf("select cod_contacto from contacto where telefono_contacto='%s' and  persona_cod_persona=%s",$post['telefono'],$post['persona']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"Telefono existente en el sistema"));
    }else{
        $respuesta = json_encode(array('estado'=>false));
    }
    echo $respuesta;
}

if ($post['accion']=='dcontacto')
{
    $sentecia=sprintf("select * from contacto where cod_contacto=%s",$post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        $row = mysqli_fetch_assoc(result:$rs);
        $dato = array(
            'codigo' =>  $row['cod_contacto'],
            'nombre' =>  $row['nom_contacto'],
            'apellido' =>  $row['ape_contacto'],
            'correo' => $row['email_contacto'],
            'telefono' => $row['telefono_contacto'],
            'persona' => $row['persona_cod_persona']
        );
        $respuesta = json_encode(array('estado'=>true,'contacto'=>$dato));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Datos no encontrados'));
    }
    echo $respuesta;
}

if ($post['accion']=='acontacto')
{
    $sentecia=sprintf("UPDATE contacto SET nom_contacto='%s',ape_contacto='%s',telefono_contacto='%s' ,email_contacto='%s' WHERE cod_contacto='%s' " ,$post['nombre'], $post['apellido'], $post['telefono'], $post['mail'],$post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"datos actulizados"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar'));
    }
    echo $respuesta;
}

if ($post['accion']=='eliminar')
{
    $sentecia=sprintf("DELETE FROM contacto where cod_contacto='%s'", $post['codigo']);
    $rs=mysqli_query($mysqli,$sentecia);
    if ($rs){
        $respuesta = json_encode(array('estado'=>true,'mensaje'=>"Dato eliminado correctamente"));
    }else{
        $respuesta = json_encode(array('estado'=>false,'mensaje'=>'Error no se elimino'));
    }
    echo $respuesta;
}



?>