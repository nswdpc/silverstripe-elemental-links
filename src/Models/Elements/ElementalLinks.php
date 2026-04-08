<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use NSWDPC\GridHelper\Models\Configuration;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\UnsavedRelationList;

/**
 * Links element
 *
 * CardColumns functionality is provided by
 * NSWDPC\GridHelper\Extensions\ElementChildGridExtension
 *
 * @author Mark
 * @author James
 * @property ?string $HTML
 * @property ?string $Subtype
 * @property ?string $CardStyle
 * @method \SilverStripe\ORM\ManyManyList<\gorriecoe\Link\Models\Link> ElementLinks()
 * @mixin \NSWDPC\GridHelper\Extensions\ElementChildGridExtension
 */
class ElementalLinks extends BaseElement
{
    /**
     * @inheritdoc
     */
    private static string $icon = 'font-icon-thumbnails';

    /**
     * @inheritdoc
     */
    private static string $table_name = 'ElementalLinks';

    /**
     * @inheritdoc
     */
    private static string $title = 'Links list';

    /**
     * @inheritdoc
     */
    private static string $description = "Display a list of links";

    /**
     * @inheritdoc
     */
    private static string $singular_name = 'Links Element';

    /**
     * @inheritdoc
     */
    private static string $plural_name = 'Links Elements';

    /**
     * @inheritdoc
     */
    private static bool $inline_editable = false;

    private static array $subtypes = [
        'cards' => 'Cards',
        'carousel' => 'Carousel',
        'feature-tile' => 'Feature tile',
        'link-list' => 'Link list'
    ];

    private static array $card_styles = [
        'title' => 'Title only',
        'title-abstract' => 'Title and abstract',
        'title-image-abstract' => 'Title, image, abstract',
    ];

    /**
     * @inheritdoc
     */
    private static array $db = [
        'HTML' => 'HTMLText',
        'Subtype' => 'Varchar(64)',
        'CardStyle' => 'Varchar(64)',
    ];

    /**
     * @inheritdoc
     */
    private static array $many_many = [
        'ElementLinks' => Link::class
    ];

    /**
     * @inheritdoc
     */
    private static array $many_many_extraFields = [
        'ElementLinks' => [
            'Sort' => 'Int'
        ]
    ];

    /**
     * @inheritdoc
     */
    #[\Override]
    public function getType()
    {
        return _t(self::class . '.BlockType', 'Links');
    }

    /**
     * Override default list value returned to ensure sorted by the many_many relation field
     * @return ManyManyList|UnsavedRelationList
     */
    public function ElementLinks()
    {
        $links = $this->getManyManyComponents('ElementLinks');
        return $links->orderBy('"ElementalLinks_ElementLinks"."Sort" ASC');
    }

    /**
     * Getter for ElementLinks
     */
    public function getElementLinks()
    {
        return $this->ElementLinks();
    }

    /**
     * Get the grid configurator model from nswdpc/silverstripe-grid-helpers module
     */
    protected function getConfigurator(): Configuration
    {
        return Injector::inst()->get(Configuration::class);
    }

    /**
     * @inheritdoc
     */
    #[\Override]
    public function getCMSFields()
    {
        $fields = parent::getCmsFields();

        $fields->removeByName(['ElementLinks']);

        $fields->addFieldsToTab(
            'Root.Main',
            [
                HTMLEditorField::create(
                    'HTML',
                    _t(
                        self::class . '.HTML',
                        'Content'
                    )
                )->setRows(4),
                LinkField::create(
                    'ElementLinks',
                    _t(
                        self::class . '.LINKS',
                        'Links'
                    ),
                    $this
                )->setSortColumn('Sort')
            ]
        );

        // List type selector
        $subType = DropdownField::create(
            'Subtype',
            _t(
                self::class . '.LISTTYPE',
                'List type'
            ),
            $this->owner->config()->get('subtypes')
        )->setEmptyString('none');

        // Card column selection - via ElementChildGridExtension
        $options = $this->getConfigurator()->config()->get('card_columns');
        $options = is_array($options) ? array_unique($options) : [];

        $cardColumns = DropdownField::create(
            'CardColumns',
            _t(
                self::class . '.CARDCOLUMNS',
                'Card columns'
            ),
            $options
        )->setEmptyString('none');
        $cardColumns->displayIf('Subtype')
            ->isEqualTo('cards')
            ->orIf("Subtype")->isEqualTo("carousel");

        // Card style selector
        $cardStyle = DropdownField::create(
            'CardStyle',
            _t(
                self::class . '.CARDSTYLE',
                'Card style'
            ),
            $this->owner->config()->get('card_styles')
        )->setEmptyString('none');
        $cardStyle->displayIf('Subtype')
            ->isEqualTo('cards')
            ->orIf("Subtype")->isEqualTo("carousel");

        /**
         * via ElementChildGridExtension
         */
        $fields->addFieldsToTab(
            'Root.Display',
            [
                $subType,
                $cardColumns,
                $cardStyle
            ]
        );

        return $fields;
    }
}
