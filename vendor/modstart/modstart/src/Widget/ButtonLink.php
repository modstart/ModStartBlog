<?php


namespace ModStart\Widget;



class ButtonLink extends AbstractWidget
{
    public static function getAssets()
    {
        return [
            'style' => '',
        ];
    }

    public static function __callStatic($name, $arguments)
    {
        $methods = ['muted', 'warning', 'danger', 'success', 'primary'];
        if (in_array($name, $methods)) {
            $ins = new static();
            $ins->type($name);
            $ins->text($arguments[0]);
            $ins->url($arguments[1]);
            $ins->disabled(empty($arguments[2]) ? false : true);
            return $ins;
        }
        throw new \Exception('ButtonAjaxRequest error ' . join(',', $methods) . ' ');
    }

    public function render()
    {
        if ($this->disabled) {
            return '<a href="javascript:;" ' . ($this->blank ? 'target="_blank"' : '') . ' class="btn btn-' . $this->type . '">' . $this->text . '</a>';
        } else {
            return '<a href="' . $this->url . '" ' . ($this->blank ? 'target="_blank"' : '') . ' class="btn btn-' . $this->type . '">' . $this->text . '</a>';
        }
    }

}
