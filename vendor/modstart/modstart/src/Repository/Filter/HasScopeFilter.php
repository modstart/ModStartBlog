<?php


namespace ModStart\Repository\Filter;


use Illuminate\Support\Facades\Input;

trait HasScopeFilter
{
    
    protected $scopeFilters = [];
    
    protected $scopeDefault = null;
    
    protected $scopeAddedParam = [];

    
    public function scopeFilter($name, $title, \Closure $callback = null)
    {
        $filter = new ScopeFilter();
        call_user_func($callback, $filter);
        array_push($this->scopeFilters, [
            'name' => $name,
            'title' => $title,
            'filter' => $filter,
        ]);
        return $this;
    }

    
    public function scopeDefault($name)
    {
        $this->scopeDefault = $name;
        return $this;
    }

    
    public function scopeParam()
    {
        $scopeValue = $this->scopeValue();
        if (null === $scopeValue) {
            return [];
        }
        return [
            '_scope' => $scopeValue,
        ];
    }

    public function scopeValue()
    {
        return Input::get('_scope', $this->scopeDefault);
    }

    
    public function scopeAddedParam($param = null)
    {
        if (is_null($param)) {
            return $this->scopeAddedParam;
        }
        $this->scopeAddedParam = $param;
        return $this;
    }

    public function scopeExecuteQueries(&$query)
    {
        $scope = Input::get('_scope', $this->scopeDefault);
        if (empty($scope)) {
            return;
        }
        foreach ($this->scopeFilters as $scopeFilter) {
            if ($scopeFilter['name'] == $scope) {
                
                $filter = $scopeFilter['filter'];
                $filter->executeQueries($query);
            }
        }
    }
}
