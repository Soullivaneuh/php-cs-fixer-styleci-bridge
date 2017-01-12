<?php

namespace SLLH\StyleCIBridge\Tests;

use SLLH\StyleCIBridge\ConfigBridge;

/**
 * @group legacy
 * We have to add this because of refused PR: https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/2149
 * This can be removed while dropping php-cs-fixer 1.x support.
 *
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class ConfigBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultConfig()
    {
        $config = ConfigBridge::create(__DIR__.'/Fixtures/configs/default');

        if (method_exists($config, 'getRules')) {
            $this->assertArraySubset(array(
                'align_double_arrow' => true,
                'long_array_syntax' => true,
                'linebreak_after_opening_tag' => true,
                'ordered_imports' => true,
                'psr0' => false,
                'unalign_double_arrow' => false,
                'unalign_equals' => false,
            ), $config->getRules());
        } else { // PHP-CS-Fixer 1.x BC
            $expectedFixers = array(
                'align_double_arrow',
                'newline_after_open_tag',
                'ordered_use',
                'long_array_syntax',
                'linebreak_after_opening_tag',
                '-psr0',
                '-unalign_double_arrow',
                '-unalign_equals',
            );
            foreach ($expectedFixers as $expectedFixer) {
                $this->assertTrue(
                    in_array($expectedFixer, $config->getFixers()),
                    'The configuration must have the following fixer: "'.$expectedFixer.'".'
                );
            }
        }

        $this->assertAttributeContains('tmp', 'exclude', $config->getFinder());
        $this->assertAttributeContains('autoload.php', 'notNames', $config->getFinder());
    }

    public function testNonePreset()
    {
        $config = ConfigBridge::create(__DIR__.'/Fixtures/configs/none');

        if (method_exists($config, 'getRules')) {
            $this->assertSame(array(), $config->getRules());
        } else { // PHP-CS-Fixer 1.x BC
            $this->assertArraySubset(array(), $config->getFixers());
        }
    }

    public function testNonePresetWithRules()
    {
        $config = ConfigBridge::create(__DIR__.'/Fixtures/configs/none_with_rules');

        if (method_exists($config, 'getRules')) {
            $this->assertSame(array(
                'align_double_arrow' => true,
                'ordered_imports' => true,
            ), $config->getRules());
        } else { // PHP-CS-Fixer 1.x BC
            $this->assertArraySubset(array(
                'align_double_arrow',
                'ordered_use',
            ), $config->getFixers());
        }
    }
}
