<?php
include('config.php');
header('Access-Control-Allo-Origin:*');
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');
header('ContentType:application/json; charset=utf-8');
$post = json_decode(file_get_contents("php://input"), true);
if ($post['accion']=='consultar')
{
    $sentecia=sprintf("Select * from contacto");
    $rs=mysqli_query($mysqli,$sentecia);
    if (mysqli_num_rows($rs)>0){
        while($row = mysqli_fetch_array(result: $rs)){
            $datos[] = array(
                'codigo' =>  $row['cod_contacto'],
                'nombre' =>  $row['nom_contacto'],
                'apellido' =>  $row['ape_contacto'],
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

if ($post['accion']=='insertar')
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

if ($post['accion']=='actualizar')
{
    $sentecia=sprintf("UPDATE contacto SET nom_contacto='%s',ape_contacto='%s',email_contacto='%s',telefono_contacto='%s' WHERE cod_contacto='%s' " ,$post['nombre'], $post['apellido'], $post['mail'], $post['telefono'],$post['codigo']);
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