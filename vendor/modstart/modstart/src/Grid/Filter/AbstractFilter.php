<?php

namespace ModStart\Grid\Filter;

use Illuminate\Support\Facades\View;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Util\IdUtil;
use ModStart\Grid\Filter;
use ModStart\Grid\Filter\Field\Text;
use ModStart\Grid\Grid;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFluentAttribute;


abstract class AbstractFilter
{
    use HasFluentAttribute;

    private $fluentAttributes = [
        'hookRendering',
    ];
    private $hookRendering;

    
    private $grid;

    
    protected $id;
    
    protected $label;
    
    protected $column;
    
    protected $field;
    
    protected $defaultValue;
    
    protected $query = 'where';
    
    private $tableFilter;
    
    private $autoHide = false;

    
    public function __construct($column, $label = '')
    {
        $this->id = IdUtil::generate('GridFilter');
        $this->column = $column;
        $this->label = $label;

        $this->field = new Text($this);
        $this->field->label($this->label);
    }

    public function name()
    {
        $class = explode('\\', get_called_class());
        return lcfirst(end($class));
    }

    
    public function setTableFilter(GridFilter $filter)
    {
        $this->tableFilter = $filter;
    }

    public function condition($searchInfo)
    {
        if (isset($searchInfo['eq']) && $searchInfo['eq'] !== '') {
            return $this->buildCondition($this->column, '=', $searchInfo['eq']);
        }
        return null;
    }

    
    public function field($value = null)
    {
        if (null === $value) {
            return $this->field;
        }
        $this->field = $value;
    }

    
    public function defaultValue($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    
    public function autoHide($autoHide = null)
    {
        if (null === $autoHide) {
            return $this->autoHide;
        }
        $this->autoHide = $autoHide;
        return $this;
    }

    
    public function column()
    {
        return $this->column;
    }

    
    protected function buildCondition()
    {
        $column = explode('.', $this->column);
        if (count($column) == 1 || $this->grid->isDynamicModel()) {
            return [$this->query => func_get_args()];
        }
        return call_user_func_array([$this, 'buildRelationCondition'], func_get_args());
    }

    
    protected function buildRelationCondition()
    {
        $args = func_get_args();
        list($relation, $args[0]) = explode('.', $this->column);
        return ['whereHas' => [$relation, function ($relation) use ($args) {
            call_user_func_array([$relation, $this->query], $args);
        }]];
    }

    
    public function variables()
    {
        $variables = [
            'id' => $this->id,
            'column' => $this->column,
            'label' => $this->label,
            'field' => $this->field,
            'defaultValue' => $this->defaultValue,
            'autoHide' => $this->autoHide,
        ];
        if (method_exists($this->field, 'variables')) {
            $variables = array_merge($variables, $this->field()->variables());
        }
        return $variables;
    }

    
    public function render()
    {
        if ($this->hookRendering instanceof \Closure) {
            $ret = call_user_func($this->hookRendering, $this);
            if (null !== $ret) {
                return $ret;
            }
        }
        $class = explode('\\', get_called_class());
        $fieldClass = explode('\\', get_class($this->field));
        $view = 'modstart::core.grid.filter.'
            . lcfirst(end($class)) . '-' . lcfirst(end($fieldClass));
        return View::make($view, $this->variables())->render();
    }

    
    public function grid($grid = null)
    {
        if (null === $grid) {
            return $this->grid;
        }
        $this->grid = $grid;
        return $this;
    }

    public function __call($name, $arguments)
    {
        if ($this->isFluentAttribute($name)) {
            return $this->fluentAttribute($name, $arguments);
        }
        throw new \Exception('AbstractFilter __call error : ' . $name);
    }

}
