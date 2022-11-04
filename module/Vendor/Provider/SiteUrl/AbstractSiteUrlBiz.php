<?php


namespace Module\Vendor\Provider\SiteUrl;


abstract class AbstractSiteUrlBiz
{
    abstract public function name();

    abstract public function title();

    abstract public function urlBuildBatch($nextId, $param = []);
                                                                }
