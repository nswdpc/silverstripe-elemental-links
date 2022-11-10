<?php

namespace NSWDPC\Elemental\Models\LinksBlock\Tests;

use NSWDPC\Elemental\Models\LinksBlock\ElementalLinks;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\View\Requirements;

/**
 * Unit test to verify links block handling
 * @author James
 */
class ElementalLinksTest extends SapphireTest
{

    /**
     * @inheritdoc
     */
    protected $usesDatabase = true;

    /**
     * @inheritdoc
     */
    protected static $fixture_file = './ElementalLinksTest.yml';

    /**
     * Verify basic card columns handling
     */
    public function testCardColumns() {
        $element = $this->objFromFixture( ElementalLinks::class, 'four' );
        $this->assertEquals(4, $element->CardColumns );
    }
}
