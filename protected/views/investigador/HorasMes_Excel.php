<?php
header("Content-Type: application/vnd.ms-excel");
header("content-disposition: attachment;filename=Reporte_Investigadores_".date('d_m_Y').".xls");
$investi = new Investigador();
$registros= $investi->obtenerHorasInvestigador($id, null);
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th width="150">Código del Proyecto</th>
                    <th width="100">Horas</th>
                    <th width="200">Inicio</th>
                    <th width="200">Fin</th>
                    <th width="200">Tipos de Hora</th>
                </tr>
            </thead>
            <tfoot></tfoot>
            <tbody>
                <?php
                ?>
                <tr>
                <th> <?php echo $registros["Código del Proyecto"]; ?> </th>
                 <th> <?php echo $registros["Horas"]; ?> </th>
                  <th> <?php echo $registros["Inicio"]; ?> </th>
                   <th> <?php echo $registros["Fin"]; ?> </th>
                    <th> <?php echo $registros["Tipos de Hora"]; ?> </th>
                </tr>
            </tbody>
        </table>
    </body>
</html>