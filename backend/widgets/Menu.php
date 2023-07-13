<?php

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Menu extends Widget
{
    public $linkTemplate = '<a href="{url}" class="{class}">
            <i class="fa fa-{icon}"></i>
            <span class="title">{label}</span>
        </a>';
    public $submenuLinkTemplate = '<a href="{url}" class="{class}">
            <i class="fa fa-{icon}"></i>
            <span class="title">{label}</span>
            <span class="arrow"></span>
        </a>';

    public $linkNoIconTemplate = '<a href="{url}" class="{class}">
            <span class="title">{label}</span>
        </a>';
    public $labelNoIconTemplate = '<a href="javascript:;">{label}</a>';
    public $submenuTemplate = "\n<ul class='sub-menu'>\n{items}\n</ul>\n";

    public $items = [];
    public $options = [];

    /**
     * @var string the route used to determine if a menu item is active or not.
     * If not set, it will use the route of the current request.
     * @see params
     * @see isItemActive()
     */
    public $route;
    /**
     * @var array the parameters used to determine if a menu item is active or not.
     * If not set, it will use `$_GET`.
     * @see route
     * @see isItemActive()
     */
    public $params;

    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = $_GET;
        }
        
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        echo Html::tag($tag, $this->renderItems($this->items), $options);
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $lines = [];

        $tag = ArrayHelper::remove($options, 'tag', 'li');
        foreach ($items as $i => $item) {

            if (isset($item['rback_check']) && $item['rback_check'] && !Yii::$app->user->can($item['rbac_check'])) {
                continue;
            }
            //die("wwww");
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }

            if (!empty($item['items'])) {
                $menu = $this->renderItem($item, true);
                $menu .= strtr(
                    $this->submenuTemplate,
                    [
                        '{items}' => $this->renderItems($item['items']),
                    ]
                );
            }else{
                $menu = $this->renderItem($item);
            }
            $options = [];
            if ($this->isItemActive($item)) {
                $options['class'] = 'active';
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        //print_r($lines);
        //die("xxx");
        return implode("\n", $lines);
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @param boolean $sub define if this is a sub menu or not to call different link template
     * @return string the rendering result
     */
    protected function renderItem($item , $sub = false)
    {
        $template = ArrayHelper::getValue($item, 'template', $sub?$this->submenuLinkTemplate:$this->linkTemplate);
        return strtr(
            $template,
            [
                '{url}' => isset($item['url']) ? Url::to($item['url']) : 'javascript:;',
                '{label}' => $item['label'],
                '{icon}' => isset($item['icon']) ? $item['icon'] : 'angle-right',
                '{class}' => isset($item['class']) ? $item['class'] : '',
            ]
        );
      
    }

    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if (!isset($this->params[$name]) || $this->params[$name] != $value) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
}
