<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2015-2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\CloudGallery\Model;

use FacturaScripts\Core\Model\Base;

/**
 * Merchandise transport agency.
 *
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 * @author Artex Trading sa     <jcuello@artextrading.com>
 */
class DatosArchivo extends Base\ModelClass
{

    use Base\ModelTrait;

    /**
     * Primary key. Varchar(8).
     *
     * @var string
     */
    public $coddatosarchivo;

    /**
     * File description.
     *
     * @var string
     */
    public $description;

    /**
     * File extension.
     *
     * @var string
     */
    public $extension;

    /**
     * Default icon file
     *
     * @var string
     */
    public $icon;

    /**
     * Icon file type (fa = a font awesome icon, ip = a custom image type)
     *
     * @var string
     */
    public $iconType;

    /**
     * Mime file type
     *
     * @var string
     */
    public $mime;

    /**
     * Reset the values of all model properties.
     */
    public function clear()
    {
        parent::clear();
        $this->icon     = 'fas fa-file-alt';
        $this->iconType = 'fa';
    }

    /**
     * Returns the name of the column that is the primary key of the model.
     *
     * @return string
     */
    public static function primaryColumn()
    {
        return 'coddatosarchivo';
    }

    /**
     * Returns the name of the table that uses this model.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'datos_archivos';
    }

    /**
     * 
     * @return bool
     */
    public function test()
    {
        return parent::test();
    }

    /**
     * 
     * @return html string
     */
    public function gethtmliconfa(): string
    {
        return 'true';
    }

    /**
     * 
     * @return html string
     */
    public function gethtmliconip(): string
    {
        return 'true';
    }

    /**
     * 
     * @return html string
     */
    public function checkData()
    {
        $data         = FS_FOLDER . DIRECTORY_SEPARATOR . 'Plugins' . DIRECTORY_SEPARATOR . 'CloudGallery' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'TableDefaultData' . DIRECTORY_SEPARATOR . self::tableName() . '.json';
        $data         = json_decode(file_get_contents($data));
        $columns      = explode('|', $data->columns);
        $primaryCheck = new $this;

        foreach ($data->values as $row) {
            $this->clear();
            foreach (explode('|', $row) as $key => $value)
                $this->{$columns[$key]} = $value;

            $primaryCheck->loadFromCode('', [new \FacturaScripts\Core\Base\DataBase\DataBaseWhere('extension',$this->extension)]);

            $this->{self::primaryColumn()} = is_null($primaryCheck->{self::primaryColumn()}) ? $this->{self::primaryColumn()} : $primaryCheck->{self::primaryColumn()};

            $this->save();
        }
    }
}
