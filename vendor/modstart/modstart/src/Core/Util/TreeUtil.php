<?php

namespace ModStart\Core\Util;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ModStart\Core\Dao\ModelUtil;

class TreeUtil
{
    
    static $CHILD_KEY = '_child';

    
    public static function setChildKey($key)
    {
        self::$CHILD_KEY = $key;
    }

    
    public static function modelToTree($model, $fieldsMap = [], $keyId = 'id', $keyPid = 'pid', $keySort = 'sort', $where = [])
    {
        $models = ModelUtil::all($model, $where);
        $nodes = [];
        foreach ($models as &$model) {
            $node = [];
            $node[$keyId] = $model[$keyId];
            $node[$keyPid] = $model[$keyPid];
            $node[$keySort] = $model[$keySort];
            foreach ($fieldsMap as $k => $v) {
                if (is_numeric($k)) {
                    $node[$v] = $model[$v];
                } else {
                    $node[$k] = $model[$v];
                }
            }
            $nodes[] = $node;
        }
        return self::nodesToTree($nodes, 0, $keyId, $keyPid, $keySort);
    }

    
    public static function modelToTreeByParentId($pid, $model, $fieldsMap = [], $keyId = 'id', $keyPid = 'pid', $keySort = 'sort')
    {
        $models = [];

        $m = ModelUtil::get($model, [$keyId => $pid]);
        if (empty($m)) {
            return [];
        }
        $topPid = $m[$keyPid];
        $models[] = $m;

        $ms = ModelUtil::all($model, [$keyPid => $pid]);
        do {
            $parentIds = [];
            foreach ($ms as &$m) {
                $parentIds[] = $m[$keyId];
                $models[] = $m;
            }
            if (empty($parentIds)) {
                $ms = null;
            } else {
                $ms = ModelUtil::model($model)->whereIn($keyPid, $parentIds)->get()->toArray();
            }
        } while (!empty($ms));

        $nodes = [];
        foreach ($models as &$model) {
            $node = [];
            $node[$keyId] = $model[$keyId];
            $node[$keyPid] = $model[$keyPid];
            $node[$keySort] = $model[$keySort];
            foreach ($fieldsMap as $k => $v) {
                $node[$k] = $model[$v];
            }
            $nodes[] = $node;
        }
        return self::nodesToTree($nodes, $topPid, $keyId, $keyPid, $keySort);
    }

    
    public static function modelNodeDeleteAble($model, $id, $pidName = 'pid', $where = [])
    {
        return !ModelUtil::exists($model, array_merge($where, [$pidName => $id]));
    }

    
    public static function modelNodeChangeAble($model, $id, $fromPid, $toPid, $idName = 'id', $pidName = 'pid', $where = [])
    {
        if ($fromPid == $toPid) {
            return true;
        }

        $_toPid = $toPid;

        while ($m = ModelUtil::get($model, array_merge($where, [$idName => $_toPid]))) {
            if ($m[$idName] == $id) {
                return false;
            }
            $_toPid = $m[$pidName];
        }

        return true;
    }

    
    public static function nodesToTree(&$nodes, $pid = 0, $idName = 'id', $pidName = 'pid', $sortName = 'sort', $sortDirection = 'asc')
    {
        if ($sortName && $sortDirection) {
            $nodes = ArrayUtil::sortByKey($nodes, $sortName, $sortDirection);
        }
        $items = [];
        foreach ($nodes as $v) {
            $items[$v[$idName]] = $v;
        }
        $tree = [];
        foreach ($items as $item) {
            if (isset($items[$item[$pidName]])) {
                $items[$item[$pidName]][self::$CHILD_KEY][] = &$items[$item[$idName]];
            } else {
                $tree[] = &$items[$item[$idName]];
            }
        }
        return array_values(array_filter($tree, function ($o) use ($pidName, $pid) {
            return $o[$pidName] == $pid;
        }));
    }

    
    public static function treeToListWithIndent(&$tree, $keyId = 'id', $keyTitle = 'title', $level = 0, $keepKeys = [])
    {
        $options = array();
        foreach ($tree as &$r) {
            $item = array(
                'id' => $r[$keyId],
                'title' => str_repeat('├', $level) . htmlspecialchars($r[$keyTitle]),
            );
            if (!empty($keepKeys)) {
                foreach ($keepKeys as $k) {
                    $item[$k] = $r[$k];
                }
            }
            $options[] = $item;
            if (!empty($r[self::$CHILD_KEY])) {
                $options = array_merge($options, self::treeToListWithIndent($r[self::$CHILD_KEY], $keyId, $keyTitle, $level + 1, $keepKeys));
            }
        }
        return $options;
    }

    
    public static function treeToListWithLevel(&$tree, $keyId = 'id', $keyTitle = 'title', $keyPid = 'pid', $level = 0, $fieldsMap = [])
    {
        $options = array();
        foreach ($tree as &$r) {
            $option = array('id' => $r[$keyId], 'title' => $r[$keyTitle], 'level' => $level, 'pid' => $r[$keyPid],);
            if (!empty($fieldsMap)) {
                foreach ($fieldsMap as $k => $v) {
                    $option[$k] = $r[$v];
                }
            }
            $options[] = $option;
            if (!empty($r[self::$CHILD_KEY])) {
                $options = array_merge($options, self::treeToListWithLevel($r[self::$CHILD_KEY], $keyId, $keyTitle, $keyPid, $level + 1, $fieldsMap));
            }
        }
        return $options;
    }

    
    public static function treeToTitleIdMap(&$tree, $keyId = 'id', $keyTitle = 'title', $keyPid = 'pid', $join = '-', $prefix = [])
    {
        $map = array();
        foreach ($tree as &$r) {
            $map[join($join, array_merge($prefix, [$r[$keyTitle]]))] = $r[$keyId];
            if (!empty($r[self::$CHILD_KEY])) {
                $map = array_merge($map, self::treeToTitleIdMap($r[self::$CHILD_KEY], $keyId, $keyTitle, $keyPid, $join, array_merge($prefix, [$r[$keyTitle]])));
            }
        }
        return $map;
    }

    
    public static function nodesChildrenIds(&$nodes, $id, $idName = 'id', $pidName = 'pid')
    {
        $ids = [];
        foreach ($nodes as &$li) {
            if ($li[$pidName] == $id) {
                $ids[] = $li[$idName];
                $childIds = self::nodesChildrenIds($nodes, $li[$idName], $idName, $pidName);
                if (!empty($childIds)) {
                    $ids = array_merge($ids, $childIds);
                }
            }
        }
        return $ids;
    }

    
    public static function treeChildrenIds($tree, $idName = 'id', $pidName = 'pid')
    {
        $ids = [];
        foreach ($tree as $item) {
            $ids[] = $item[$idName];
            if (!empty($item[self::$CHILD_KEY])) {
                $ids = array_merge($ids, self::treeChildrenIds($item[self::$CHILD_KEY], $idName, $pidName));
            }
        }
        return $ids;
    }

    
    public static function treeNodeChildrenIds(&$tree, $id, $idName = 'id', $pidName = 'pid')
    {
        foreach ($tree as $item) {
            if ($item[$idName] == $id) {
                $ids[] = $id;
                if (!empty($item[self::$CHILD_KEY])) {
                    $ids = array_merge($ids, self::treeChildrenIds($item[self::$CHILD_KEY], $idName, $pidName));
                }
                return $ids;
            }
            if (!empty($item[self::$CHILD_KEY])) {
                $ids = self::treeNodeChildrenIds($item[self::$CHILD_KEY], $id, $idName, $pidName);
                if (!empty($ids)) {
                    return $ids;
                }
            }
        }
        return [];
    }

    
    public static function treeChain(&$tree, $id, $pk_name = 'id', $pid_name = 'pid', $chain = [], $level = 0)
    {
                        foreach ($tree as $item) {
            
            if (!$item[$pid_name]) {
                $chain = [];
            }
            if ($item[$pk_name] == $id) {
                $chain[] = $item;
                return $chain;
            }
            if (!empty($item[self::$CHILD_KEY])) {
                $chain[] = ArrayUtil::removeKeys($item, [self::$CHILD_KEY]);
                $results = self::treeChain($item[self::$CHILD_KEY], $id, $pk_name, $pid_name, $chain, $level + 1);
                if (!empty($results)) {
                    return $results;
                }
                array_pop($chain);
            }
        }
        return [];
    }

    
    public static function nodesChain(&$nodes, $id, $pk_name = 'id', $pid_name = 'pid')
    {
        $chain = [];
        $limit = 0;
        $found = true;
        while ($found && $limit++ < 999) {
            $found = false;
            foreach ($nodes as $li) {
                if ($li[$pk_name] == $id) {
                    $found = true;
                    $id = $li[$pid_name];
                    $chain[] = $li;
                    break;
                }
            }
        }
        return array_reverse($chain);
    }

    
    public static function nodesChainWithItems(&$nodes, $id, $idName = 'id', $pidName = 'pid', $titleName = 'title', $itemName = '_items')
    {
        $categoryChain = self::nodesChain($nodes, $id);
        if (empty($categoryChain)) {
            $categoryChain[] = [
                $idName => -1,
                $pidName => 0,
                $titleName => 'ROOT',
            ];
        }
        foreach ($categoryChain as $k => $v) {
            $categoryChain[$k][$itemName] = array_values(array_filter($nodes, function ($o) use ($v, $pidName) {
                return $o[$pidName] == $v[$pidName];
            }));
        }
        $categoryChainNext = array_values(array_filter($nodes, function ($o) use ($id, $pidName) {
            return $o[$pidName] == $id;
        }));
        if (!empty($categoryChainNext) && $id > 0) {
            $categoryChain[] = [
                $idName => -1,
                $pidName => $id,
                $titleName => 'NEXT',
                $itemName => $categoryChainNext,
            ];
        }
        return $categoryChain;
    }

    
    public static function itemsMergeLevel($items, $idName = 'id', $pidName = 'pid', $sortName = 'sort', $pid = 0, $level = 1, $newItems = null)
    {
        if (!($items instanceof Collection)) {
            $items = collect($items);
        }
        if ($level == 1) {
            $items = $items->sortBy($sortName)->values();
        }
        if (null === $newItems) {
            $newItems = collect();
        }
        $items->each(function ($item) use ($idName, $pidName, $sortName, $pid, $level, $items, $newItems) {
            if ($item instanceof Model) {
                if (!isset($item->_level)) {
                    if ($item->{$pidName} == $pid) {
                        $item->_level = $level;
                        $newItems->push($item);
                        if ($level < 99) {
                            self::itemsMergeLevel($items, $idName, $pidName, $sortName, $item->{$idName}, $level + 1, $newItems);
                        }
                    }
                }
            } else {
                if (!property_exists($item, '_level')) {
                    if ($item->{$pidName} == $pid) {
                        $item->_level = $level;
                        $newItems->push($item);
                        if ($level < 99) {
                            self::itemsMergeLevel($items, $idName, $pidName, $sortName, $item->{$idName}, $level + 1, $newItems);
                        }
                    }
                }
            }
        });
        return $newItems;
    }

    
    public static function modelItemAddAble(Model $model, $pid, $idName = 'id')
    {
        return $model->newQuery()->where([$idName => $pid])->exists();
    }

    
    public static function modelItemDeleteAble(Model $model, $id, $pidName = 'pid')
    {
        return !$model->newQuery()->where([$pidName => $id])->exists();
    }

    
    public static function modelItemChangeAble(Model $model, $id, $fromPid, $toPid, $idName = 'id', $pidName = 'pid')
    {
        if ($fromPid == $toPid) {
            return true;
        }
        $columns = [$idName, $pidName];
        $_toPid = $toPid;
        while ($m = $model->newQuery()->where([$idName => $_toPid])->first($columns)) {
            if ($m->{$idName} == $id) {
                return false;
            }
            $_toPid = $m->{$pidName};
        }
        return true;
    }

}
