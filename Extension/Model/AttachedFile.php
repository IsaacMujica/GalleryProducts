<?php
namespace FacturaScripts\Plugins\CloudGallery\Extension\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

class AttachedFile
{
	public function DatosArchivo($mime = '')
	{
		return function($mime) {
			$model = new DatosArchivo();
			$model->loadFromCode('', [new DataBaseWhere('mime', '%' . $mime . '%', 'LIKE')]);
			return $model;
		};
	}
   
	public function GrupoArchivo($extension = '')
	{
		return function($extension) {
			$model = new GrupoArchivo();
			$model->loadFromCode('', [new DataBaseWhere('extension', '%' . $extension . '%', 'LIKE')]);
			return $model;
		};
	}

	public function getExtension($fullPathFile = '')
	{
	   	$fullPathFile = FS_FOLDER . DIRECTORY_SEPARATOR . $fullPathFile;
	   	var_dump($fullPathFile);
		return function($fullPathFile) {
			return file_exists($fullPathFile) ? str_replace('.', '', pathinfo($fullPathFile, PATHINFO_EXTENSION)) : false;
		};
	}
}