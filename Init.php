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
        if (isset($_GET['action']) && isset($_GET['plugin']))
            if ($_GET['action'] === 'disable' && $_GET['plugin'] === 'CloudGallery') $this->checkIndexFile();
    }

    public function update()
    {
        $this->checkIndexFile();
        $this->validateFileData();
    }

    private function validateFileData()
    {
    	$DatosArchivo = new Model\DatosArchivo();
    	$GrupoArchivo = new Model\GrupoArchivo();
    	$DatosArchivo->checkData();
		$GrupoArchivo->checkData();
    }

    private function checkIndexFile()
    {
        $indexRoute = \FS_FOLDER . DIRECTORY_SEPARATOR . 'index.php';
        $indexFile  = file($indexRoute);
        $changed    = false;

        foreach ($indexFile as $line => $text) {
            if (strstr($text, '$router = new \FacturaScripts\Core\App\AppRouter()')) {
                $indexFile[$line] = str_replace('$router = new \FacturaScripts\Core\App\AppRouter()', '$router = new \FacturaScripts\Plugins\CloudGallery\Lib\AppRouter()', $text);
                $changed = true;
            } elseif (strstr($text, '$router = new \FacturaScripts\Plugins\CloudGallery\Lib\AppRouter()')) {
                $indexFile[$line] = str_replace('$router = new \FacturaScripts\Plugins\CloudGallery\Lib\AppRouter()', '$router = new \FacturaScripts\Core\App\AppRouter()', $text);
                $changed = true;
            }
        }

        if ($changed) {
            $indexFile = join($indexFile);
            file_put_contents($indexRoute,$indexFile);
            return true;
        }
    }
}
