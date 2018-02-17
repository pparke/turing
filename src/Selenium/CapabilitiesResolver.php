<?php

namespace Silverorange\Turing\Selenium;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Lmc\Steward\ConfigProvider;
use Lmc\Steward\Selenium\CustomCapabilitiesResolverInterface;
use Lmc\Steward\Test\AbstractTestCase as StewardAbstractTestCase;

/**
 * @package   Turing
 * @copyright 2017-2018 silverorange
 */
class CapabilitiesResolver implements CustomCapabilitiesResolverInterface
{
    // {{{ protected properties

    /**
     * @var Lmc\Steward\ConfigProvider
     */
    protected $config;

    // }}}

    // {{{ public function __construct()

    public function __construct(ConfigProvider $config)
    {
        $this->config = $config;
    }

    // }}}
    // {{{ public function resolveDesiredCapabilities()

    public function resolveDesiredCapabilities(
        StewardAbstractTestCase $test,
        DesiredCapabilities $capabilities
    ) {
        // Run Chrome in headless mode
        if ($this->config->browserName === WebDriverBrowserType::CHROME &&
            getenv('SELENIUM_HEADLESS')
        ) {
            $chrome_options = new ChromeOptions();

            $width = (int)(getenv('SELENIUM_WIDTH') ?: 800);
            $height = (int)(getenv('SELENIUM_HEIGHT') ?: 600);

            // In headless Chrome 60+, window size cannot be changed run-time:
            // https://bugs.chromium.org/p/chromium/issues/detail?id=604324#c46
            $chrome_options->addArguments(['--headless', '--window-size=' . $width . ',' . $height ]);
            $capabilities->setCapability(
                ChromeOptions::CAPABILITY,
                $chrome_options
            );
        }

        return $capabilities;
    }

    // }}}
    // {{{ public function resolveRequiredCapabilities()

    public function resolveRequiredCapabilities(
        StewardAbstractTestCase $test,
        DesiredCapabilities $capabilities
    ) {
        return $capabilities;
    }

    // }}}
}
