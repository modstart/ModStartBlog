<?php


namespace ModStart\Grid\Concerns;


use ModStart\Grid\GridFilter;

trait HasGridFilter
{
    
    protected $gridFilterJoins = [];

    
    protected $gridFilter;

    private function setupGridFilter()
    {
        $this->gridFilter = new GridFilter($this->model);
    }

    
    public function getGridFilter()
    {
        return $this->gridFilter;
    }

    public function gridFilterJoins()
    {
        return $this->gridFilterJoins;
    }

    
    public function gridFilterJoinAdd($mode, $table, $first, $operator = null, $second = null)
    {
        $this->gridFilterJoins[] = [
            $mode, $table, $first, $operator, $second
        ];
        return $this;
    }

}
