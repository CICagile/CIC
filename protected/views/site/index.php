<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h2>Menú Principal</h2>
<h3>Elija una categoría:</h3>

<ul>
    <li><?php echo CHtml::link('Módulo de Proyectos',array('proyectos/admin')) ?></li> 
    <br/>
    <li><?php echo CHtml::link('Módulo de Asistentes',array('asistente/admin')) ?></li>
    <br/>
    <li><?php echo CHtml::link('Módulo de Investigadores',array('investigador/admin')) ?></li>
    <br/>
    <li>Gestión del Sistema
        <ul>
            <li><?php echo CHtml::link('Objetivos de Proyecto',array('objetivoproyecto/admin')) ?></li>
            <li><?php echo CHtml::link('Adscripción de Proyectos',array('adscrito/admin')) ?></li>
            <li><?php echo CHtml::link('Tipos de Proyectos',array('tipoproyecto/admin')) ?></li>
            <li><?php echo CHtml::link('Sector Beneficiado',array('sectorbeneficiado/admin')) ?></li>
            <li><?php echo CHtml::link('Convenio',array('convenio/admin')) ?></li>
        </ul>
    </li>
</ul>
