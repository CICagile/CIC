<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Lista de Proyectos',
);

$this->menu=array(	
	array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);
?>

<h1>Lista de Proyectos</h1>
<?php
    
    $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'area-grid',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
                array(
                      'header' => 'Codigo',
                      'name' => 'codigo', 
                ),
                array(
                      'header' => 'Nombre del Proyecto',
                      'name' => 'nombre', 
                ),  				
		array(
                      'header' => 'Fecha inicio',
                      'name' => 'inicio',                       
                ),
                array(
                      'header' => 'Fecha fin',
                      'name' => 'fin',                      
                ),             
        ),
    'filter'=>$filtersForm,
));
?>