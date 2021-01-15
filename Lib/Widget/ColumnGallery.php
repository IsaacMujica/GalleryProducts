<?php
/**
 * DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED 
 * DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED 
 * DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED 
 * DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED 
 * DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED 
 * DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED  DERPECATED 
 */
namespace FacturaScripts\Plugins\CloudGallery\Lib\Widget;

use FacturaScripts\Core\Lib\Widget AS CoreWidget;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ColumnItem
 *
 * @author Artex Trading sa     <jcuello@artextrading.com>
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 */
class ColumnGallery extends CoreWidget\VisualItem
{

    /**
     * Additional text that explains the field to the user
     *
     * @var string
     */
    public $description;

    /**
     * State and alignment of the display configuration
     * (left|right|center|none)
     *
     * @var string
     */
    public $display;

    /**
     * Indicates the security level of the column
     *
     * @var int
     */
    public $level;

    /**
     *
     * @var int
     */
    public $numcolumns;

    /**
     *
     * @var int
     */
    public $order;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $titleurl;

    /**
     * Field display object configuration
     *
     * @var BaseWidget
     */
    public $widget;

    /**
     *
     * @param array $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->description = isset($data['description']) ? $data['description'] : '';
        $this->display = isset($data['display']) ? $data['display'] : 'left';
        $this->level = isset($data['level']) ? (int) $data['level'] : 0;
        $this->numcolumns = isset($data['numcolumns']) ? (int) $data['numcolumns'] : 0;
        $this->order = isset($data['order']) ? (int) $data['order'] : 0;
        $this->title = isset($data['title']) ? $data['title'] : $this->name;
        $this->titleurl = isset($data['titleurl']) ? $data['titleurl'] : '';
        $this->loadWidget($data['children']);
    }

    /**
     *
     * @param object $model
     * @param bool   $onlyField
     *
     * @return string
     */
    public function edit($model, $onlyField = false)
    {
        if ($this->hidden()) {
            return $this->widget->inputHidden($model);
        }

        $editHtml = $onlyField ? $this->widget->edit($model) : $this->widget->edit($model, $this->title, $this->description, $this->titleurl);

        $divClass = $this->numcolumns > 0 ? $this->css('col-md-') . $this->numcolumns : $this->css('col-md');
        $divID = empty($this->id) ? '' : ' id="' . $this->id . '"';
        return '<div' . $divID . ' class="' . $divClass . '">'
            . $editHtml
            . '</div>';
    }

    /**
     * Returns CSS percentage width
     *
     * @return string
     */
    public function htmlWidth()
    {
        if ($this->numcolumns < 1 || $this->numcolumns > 11) {
            return '100%';
        }

        return \round((100.00 / 12 * $this->numcolumns), 5) . '%';
    }

    /**
     *
     * @return boolean
     */
    public function hidden()
    {
        if ($this->display === 'none') {
            return true;
        }

        return $this->getLevel() < $this->level;
    }

    /**
     *
     * @param object  $model
     * @param Request $request
     */
    public function processFormData(&$model, $request)
    {
        $this->widget->processFormData($model, $request);
    }

    /**
     *
     * @param object $model
     *
     * @return string
     */
    public function tableCell($model)
    {
        /*$return = '<pre>'.var_dump($description ?? '').'</pre>';
        $return .= '<pre>'.var_dump($title ?? '').'</pre>';
        $return .= '<pre>'.var_dump($titleurl ?? '').'</pre>';
        $return .= '<pre>'.var_dump($widget ?? '').'</pre>';
        return $return;*/
        return $this->hidden() ? '' : $this->widget->tableCell($model, $this->display);
    }

    /**
     *
     * @param object $model
     *
     * @return string
     */
    public function cardGalleryValue($model)
    {
        # $return = '<pre>'.var_dump($model ?? '').'</pre>';
        # $return .= '<pre>'.var_dump(static::$i18n->trans($this->title) ?? '').'</pre>';
        # $return .= '<pre>'.var_dump($titleurl ?? '').'</pre>';
        # $return .= '<pre>'.var_dump($widget ?? '').'</pre>';
        # return $return;
        return $this->hidden() ? '' : $this->widget->cardGalleryValue($model, static::$i18n->trans($this->title), $this->display);
    }

    /**
     *
     * @return string
     */
    public function tableHeader()
    {
        if ($this->hidden()) {
            return '';
        }

        if (empty($this->titleurl)) {
            return '<th class="text-' . $this->display . '">' . static::$i18n->trans($this->title) . '</th>';
        }

        return '<th class="text-' . $this->display . '">'
            . '<a href="' . $this->titleurl . '">' . static::$i18n->trans($this->title) . '</a>'
            . '</th>';
    }

    /**
     *
     * @param array $children
     */
    protected function loadWidget($children)
    {
        foreach ($children as $child) {
            if ($child['tag'] !== 'widget') {
                continue;
            }

            $className = CoreWidget\VisualItemLoadEngine::getNamespace() . 'Widget' . \ucfirst($child['type']);
            if (\class_exists($className)) {
                $this->widget = new $className($child);
                break;
            }

            $defaultWidget = CoreWidget\VisualItemLoadEngine::getNamespace() . 'WidgetText';
            $this->widget = new $defaultWidget($child);
            break;
        }
    }
}
