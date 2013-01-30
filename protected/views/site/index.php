<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h2>Menú Principal</h2>
<h3>Elija una categoría:</h3>

<ul>
    <li><?php echo CHtml::link('Asistentes',array('asistente/admin')) ?></li>
    <p></p>
    <li><?php echo CHtml::link('Proyectos',array('proyectos/admin')) ?></li>    
</ul>
