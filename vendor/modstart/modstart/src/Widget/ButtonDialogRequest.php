<?php


namespace ModStart\Widget;



class ButtonDialogRequest extends AbstractWidget
{
    public static function getAssets()
    {
        return [
            'style' => '.ub-button-dialog-request{display:inline-block;}',
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
            return $ins->render();
        }
        throw new \Exception('ButtonDialogRequest error ' . join(',', $methods) . ' ');
    }

    
    public static function make(...$arguments)
    {
        $ins = new static();
        $ins->type($arguments[0]);
        $ins->text($arguments[1]);
        $ins->url($arguments[2]);
        return $ins;
    }

    public function size($size)
    {
        switch ($size) {
            case 'big':
                return $this->attr(($this->attr ? $this->attr : '') . ' data-dialog-width="90%" data-dialog-height="90%"');
        }
        return $this;
    }

    public function render()
    {
        if ($this->disabled) {
            return '<a href="javascript:;" class="btn ub-button-dialog-request btn-' . $this->type . '">' . $this->text . '</a>';
        } else {
            return '<a href="javascript:;" ' . ($this->confirm ? 'data-confirm="' . $this->confirm . '"' : '') . ' data-dialog-request="' . $this->url . '" class="btn ub-button-dialog-request btn-' . $this->type . '" ' . ($this->attr ? $this->attr : '') . '>' . $this->text . '</a>';
        }
    }
}
