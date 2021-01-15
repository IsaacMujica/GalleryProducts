<?php

namespace FacturaScripts\Plugins\CloudGallery;


use FacturaScripts\Core\App\AppSettings;
use FacturaScripts\Core\Base\InitClass;
use FacturaScripts\Core\Base\DataBase;
use FacturaScripts\Dinamic\Lib;
use FacturaScripts\Dinamic\Model;

/**
 * Description of Init
 *
 * @author Isaac Mujica <isaacmujicarivas@gmail.com>
 */

class Init extends InitClass
{
    public function init()
    {
    	#
    }

    public function update()
    {
        $this->validateFileData();
    }

    private function validateFileData()
    {
    	$DatosArchivo = new Model\DatosArchivo();
    	$GrupoArchivo = new Model\GrupoArchivo();
    	$DatosArchivo->checkData();
		$GrupoArchivo->checkData();
    }
}
