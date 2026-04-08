<?php

declare(strict_types=1);

namespace NSWDPC\Elemental\Models\LinksBlock;

use SilverStripe\ORM\DataExtension;

/**
 * Provide reverse association for ElementLinks many_many
 *
 * @author James
 * @method \SilverStripe\ORM\ManyManyList<\NSWDPC\Elemental\Models\LinksBlock\ElementalLinks> ElementalLinks()
 * @extends \SilverStripe\ORM\DataExtension<static>
 */
class LinkExtension extends DataExtension
{

    /**
     * @inheritdoc
     */
    private static array $belongs_many_many = [
        'ElementalLinks' => ElementalLinks::class . '.ElementLinks'
    ];
}
