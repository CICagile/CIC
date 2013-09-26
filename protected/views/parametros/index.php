<?php

/*
 * Se debe mejorar este menú. Por ahora sólo se usa para no mostrar estas opciones en
 * la página principal.
 */
?>

<h2>Gestión del Sistema</h2>
<ul>
    <li><?php echo CHtml::link('Objetivos de Proyecto',array('objetivoproyecto/admin')) ?></li><br>
    <li><?php echo CHtml::link('Adscripción de Proyectos',array('adscrito/admin')) ?></li><br>
    <li><?php echo CHtml::link('Tipos de Proyectos',array('tipoproyecto/admin')) ?></li><br>
    <li><?php echo CHtml::link('Sector Beneficiado',array('sectorbeneficiado/admin')) ?></li><br>
    <li><?php echo CHtml::link('Convenio',array('convenio/admin')) ?></li>
</ul>
