<?php
/* @var $this UsuarioController */
/* @var $model Usuario */

$this->breadcrumbs=array(
	'Usuarios'=>array('admin'),
	$model->username=>array('view','id'=>$model->username),
	'Update',
);

$this->menu=array(
	array('label'=>'Crear Usuario', 'url'=>array('create')),
	array('label'=>'Ver Usuario', 'url'=>array('view', 'id'=>$model->idtbl_usuario)),
	array('label'=>'Gestionar Usuarios', 'url'=>array('admin')),
);
?>

<h1>Actualizar usuario <?php echo $model->username; ?></h1>

<?php 
//ponemos el password en null, para que no se muestre el hash en el campo de texto
$model->password = null;
echo $this->renderPartial('_form', array('model'=>$model)); 

?>