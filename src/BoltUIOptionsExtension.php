<?php

namespace Bolt\Extension\Snijder\BoltUIOptions;

use Bolt\Asset\File\Stylesheet;
use Bolt\Controller\Zone;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\Snijder\BoltUIOptions\Controller\UIOptionsController;
use Bolt\Extension\Snijder\BoltUIOptions\Provider\UIOptionsProvider;
use Bolt\Menu\MenuEntry;

/**
 * Class BoltUIOptionsExtension.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BoltUIOptionsExtension extends SimpleExtension
{
    /**
     * @var string
     */
    private $backendURL = 'bolt-ui-options';

    /**
     * {@inheritdoc}
     */
    public function getServiceProviders()
    {
        return [
           $this,
           new UIOptionsProvider($this->getConfig()),
       ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerMenuEntries()
    {
        $menu = new MenuEntry('bolt-ui-options', $this->backendURL);
        $menu->setLabel('UI options')
            ->setIcon('fa:columns')
            ->setPermission('settings')
        ;

        return [
            $menu,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return [
            'templates' => ['namespace' => 'UIOptions'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
           'uioption' => [[$this->container['ui.options.twig.function'], 'renderUIOption']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        return [
          '/extend/'.$this->backendURL => new UIOptionsController(
              $this->container['twig'],
              $this->container['ui.options.config'],
              $this->container['filesystem'],
              $this->container['url_generator'],
              $this->container['session'],
              sprintf('config://extensions/%s.%s.yml', strtolower($this->getName()), strtolower($this->getVendor())),
              'theme://theme.yml'
          ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        $asset = new Stylesheet();

        $asset->setFileName('ui-options.css')
            ->setZone(Zone::BACKEND)
            ->setLate(true)
        ;

        return [
            $asset,
        ];
    }
}
