<?php
namespace app\helpers;

use yii\helpers\Html;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Dropdown as DropdownOriginal;

class Dropdown extends DropdownOriginal
{
    protected function renderItems($items)
    {
        $lines = [];
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (is_string($item)) {
                $lines[] = $item;
                continue;
            }
            if (!isset($item['label'])) {
                throw new InvalidConfigException("The 'label' option is required.");
            }

            $label = '';
            if (isset($item['icon'])) {
                $label .= ' ' . Html::tag('span', '', ['class' => 'glyphicon glyphicon-' . Html::encode($item['icon'])]);
            }

            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $label .= $encodeLabel ? Html::encode($item['label']) : $item['label'];

            if (isset($item['badge']) && $item['badge'] > 0) {
                $label .= ' ' . Html::tag('span', Html::encode($item['badge']), ['class' => 'badge pull-right']);
            }

            $options = ArrayHelper::getValue($item, 'options', []);
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
            $linkOptions['tabindex'] = '-1';
            $content = Html::a($label, ArrayHelper::getValue($item, 'url', '#'), $linkOptions);

            if (!empty($item['items'])) {
                unset($this->_containerOptions['id']);
                $this->renderItems($item['items']);
                Html::addCssClass($options, 'dropdown-submenu');
            }
            $lines[] = Html::tag('li', $content, $options);
        }

        return Html::tag('ul', implode("\n", $lines), $this->_containerOptions);
    }
}
