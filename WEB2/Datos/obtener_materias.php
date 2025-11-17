<?php
header('Content-Type: application/json');
require 'conexion.php';
$alumno_id = isset($_POST['id']) ? (int)$_POST['id']:0;
if($alumno_id>0){
    try{
        //Usar una consulta preparada para prevenir inyección SQL
        $sql="select m.id_asignatura,m.nombre,m.creditos,m.horario,m.salon,CONCAT(p.nombre, ' ', p.apellido) AS profesor from asignaturas m  inner join maestros p on m.id_asignatura=p.id_asignatura where m.id_asignatura=:id";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id',$alumno_id,PDO::PARAM_INT);
        $stmt->execute();

        $info_cliente =$stmt->fetch(PDO::FETCH_ASSOC);
        if($info_cliente){
            echo json_encode($info_cliente);
        }else{
            echo json_encode(['error'=> 'Información de las materias no encontrada']);
        }
    }catch(PDOException $e){
        echo json_encode(['error'=>'Error al obtener la información' . $e->getMessage()]);

    }

}else{
    echo json_encode(['error'=> 'ID de materia no válido.']);
}
?>