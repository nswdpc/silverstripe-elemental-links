<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use SilverStripe\ORM\DataExtension;

/**
 * Provide reverse association for ElementLinks many_many
 *
 * @author James
 */
class LinkExtension extends DataExtension
{

    /**
     * @inheritdoc
     */
    private static $belongs_many_many = [
        'ElementalLinks' => ElementalLinks::class . '.ElementLinks'
    ];

}
