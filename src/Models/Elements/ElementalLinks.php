<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use NSWDPC\GridHelper\Models\Configuration;
use Silverstripe\Core\Injector\Injector;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\UnsavedRelationList;
use UncleCheese\DisplayLogic\Forms\Wrapper;

/**
 * Links element
 *
 * CardColumns functionality is provided by
 * NSWDPC\GridHelper\Extensions\ElementChildGridExtension
 *
 * @author Mark
 * @author James
 */
class ElementalLinks extends BaseElement
{

    /**
     * @inheritdoc
     */
    private static $icon = 'font-icon-thumbnails';

    /**
     * @inheritdoc
     */
    private static $table_name = 'ElementalLinks';

    /**
     * @inheritdoc
     */
    private static $title = 'Links list';

    /**
     * @inheritdoc
     */
    private static $description = "Display a list of links";

    /**
     * @inheritdoc
     */
    private static $singular_name = 'Links Element';

    /**
     * @inheritdoc
     */
    private static $plural_name = 'Links Elements';

    /**
     * @inheritdoc
     */
    private static $inline_editable = false;

    /**
     * @var array
     */
    private static $subtypes = [
        'cards' => 'Cards',
        'carousel' => 'Carousel',
        'feature-tile' => 'Feature tile',
        'link-list' => 'Link list'
    ];

    /**
     * @var array
     */
    private static $card_styles = [
        'title' => 'Title only',
        'title-abstract' => 'Title and abstract',
        'title-image-abstract' => 'Title, image, abstract',
    ];

    /**
     * @inheritdoc
     */
    private static $db = [
        'HTML' => 'HTMLText',
        'Subtype' => 'Varchar(64)',
        'CardStyle' => 'Varchar(64)',
    ];

    /**
     * @inheritdoc
     */
    private static $many_many = [
        'ElementLinks' => Link::class
    ];

    /**
     * @inheritdoc
     */
    private static $many_many_extraFields = [
        'ElementLinks' => [
            'Sort' => 'Int'
        ]
    ];

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Links');
    }

    /**
     * Override default list value returned to ensure sorted by the many_many relation field
     * @return ManyManyList|UnsavedRelationList
     */
    public function ElementLinks() {
        $links = $this->getManyManyComponents('ElementLinks');
        $links = $links->orderBy("\"ElementalLinks_ElementLinks\".\"Sort\" ASC");
        return $links;
    }

    /**
     * Getter for ElementLinks
     */
    public function getElementLinks() {
        return $this->ElementLinks();
    }

    /**
     * Get the grid configurator model from nswdpc/silverstripe-grid-helpers module
     */
    protected function getConfigurator() : Configuration
    {
        return Injector::inst()->get(Configuration::class);
    }

    /**
     * @inheritdoc
     */
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
                        __CLASS__ . '.HTML',
                        'Content'
                    )
                )->setRows(4),
                LinkField::create(
                    'ElementLinks',
                    _t(
                        __CLASS__ . '.LINKS',
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
                __CLASS__ . '.LISTTYPE',
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
                __CLASS__ . '.CARDCOLUMNS',
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
                __CLASS__ . '.CARDSTYLE',
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
