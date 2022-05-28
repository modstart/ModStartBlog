<?php

namespace ModStart\Misc\Html;


use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class Purifier
{

    
    protected $files;

    
    protected $config;

    
    protected $purifier;

    
    public function __construct(Filesystem $files, Repository $config)
    {
        $this->files = $files;
        $this->config = $config;

        $this->setUp();
    }

    public static function cleanHtml($dirty, $config = null)
    {
        static $instance = null;
        if (null === $instance) {
            $app = app();
            $defaultConfig = (include __DIR__ . '/config.php');
            $instance = new Purifier($app['files'], new \Illuminate\Config\Repository($defaultConfig));
        }
        return $instance->clean($dirty, $config);
    }

    
    private function setUp()
    {
        $this->checkCacheDirectory();

                $config = HTMLPurifier_Config::createDefault();

                if (!$this->config->get('finalize')) {
            $config->autoFinalize = false;
        }

        $config->loadArray($this->getConfig());

                if ($definitionConfig = $this->config->get('settings.custom_definition')) {
            $this->addCustomDefinition($definitionConfig, $config);
        }

                if ($elements = $this->config->get('settings.custom_elements')) {
            if ($def = $config->maybeGetRawHTMLDefinition()) {
                $this->addCustomElements($elements, $def);
            }
        }

                if ($attributes = $this->config->get('settings.custom_attributes')) {
            if ($def = $config->maybeGetRawHTMLDefinition()) {
                $this->addCustomAttributes($attributes, $def);
            }
        }

                $this->purifier = new HTMLPurifier($this->configure($config));
    }

    
    private function addCustomDefinition(array $definitionConfig, $configObject = null)
    {
        if (!$configObject) {
            $configObject = HTMLPurifier_Config::createDefault();
            $configObject->loadArray($this->getConfig());
        }

                $configObject->set('HTML.DefinitionID', $definitionConfig['id']);
        $configObject->set('HTML.DefinitionRev', $definitionConfig['rev']);

                if (!isset($definitionConfig['debug']) || $definitionConfig['debug']) {
            $configObject->set('Cache.DefinitionImpl', null);
        }

                if ($def = $configObject->maybeGetRawHTMLDefinition()) {
                        if (!empty($definitionConfig['attributes'])) {
                $this->addCustomAttributes($definitionConfig['attributes'], $def);
            }

                        if (!empty($definitionConfig['elements'])) {
                $this->addCustomElements($definitionConfig['elements'], $def);
            }
        }

        return $configObject;
    }

    
    private function addCustomAttributes(array $attributes, $definition)
    {
        foreach ($attributes as $attribute) {
                        $required = !empty($attribute[3]) ? true : false;
            $onElement = $attribute[0];
            $attrName = $required ? $attribute[1] . '*' : $attribute[1];
            $validValues = $attribute[2];

            $definition->addAttribute($onElement, $attrName, $validValues);
        }

        return $definition;
    }

    
    private function addCustomElements(array $elements, $definition)
    {
        foreach ($elements as $element) {
                        $name = $element[0];
            $contentSet = $element[1];
            $allowedChildren = $element[2];
            $attributeCollection = $element[3];
            $attributes = isset($element[4]) ? $element[4] : null;

            if (!empty($attributes)) {
                $definition->addElement($name, $contentSet, $allowedChildren, $attributeCollection, $attributes);
            } else {
                $definition->addElement($name, $contentSet, $allowedChildren, $attributeCollection);
            }
        }
    }

    
    private function checkCacheDirectory()
    {
        $cachePath = $this->config->get('cachePath');

        if ($cachePath) {
            if (!$this->files->isDirectory($cachePath)) {
                $this->files->makeDirectory($cachePath, $this->config->get('cacheFileMode', 0755));
            }
        }
    }

    
    protected function configure(HTMLPurifier_Config $config)
    {
        return HTMLPurifier_Config::inherit($config);
    }

    
    protected function getConfig($config = null)
    {
        $default_config = [];
        $default_config['Core.Encoding'] = $this->config->get('encoding');
        $default_config['Cache.SerializerPath'] = $this->config->get('cachePath');
        $default_config['Cache.SerializerPermissions'] = $this->config->get('cacheFileMode', 0755);

        if (!$config) {
            $config = $this->config->get('settings.default');
        } elseif (is_string($config)) {
            $config = $this->config->get('settings.' . $config);
        }

        if (!is_array($config)) {
            $config = [];
        }

        $config = $default_config + $config;

        return $config;
    }

    
    public function clean($dirty, $config = null)
    {
        if (is_array($dirty)) {
            return array_map(function ($item) use ($config) {
                return $this->clean($item, $config);
            }, $dirty);
        }

        return $this->purifier->purify($dirty, $this->getConfig($config));
    }

    
    public function getInstance()
    {
        return $this->purifier;
    }
}
