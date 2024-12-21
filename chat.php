<?php 
include("libreria/conexion.php");


function formatDate($date) {
    return date('g:i a, j M Y', strtotime($date));
}


$con = new Conexion();


if ($con->enlace->connect_error) {
    die("Error de conexión: " . $con->enlace->connect_error);
}


$query = "SELECT * FROM ( SELECT * FROM chat ORDER BY id DESC LIMIT 3) sub ORDER BY id ASC";
$run = $con->enlace->query($query);


if (!$run) {
    echo "Error en la consulta: " . $con->enlace->error;
} else {

    while($row = $run->fetch_array()) :
?>
        <div id="chat_data">
            <span class="btn btn-default btn-xs"><span class="glyphicon glyphicon-user"></span>
            <b><?php echo $row['name']; ?></b></span>
            <span style="float:right;"><?php echo formatDate($row['date']); ?></span>
            <p style="color:gray;padding-left:20px;"><em><?php echo $row['msg']; ?></em></p>
        </div>
<?php 
    endwhile;
}


$con->enlace->close();
?>
