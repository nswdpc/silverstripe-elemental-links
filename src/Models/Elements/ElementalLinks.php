<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use UncleCheese\DisplayLogic\Forms\Wrapper;

/**
 * Links element
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
        'feature-tile' => 'Feature tile',
        'link-list' => 'Link list'
    ];

    /**
     * @var array
     */
    private static $card_columns = [
        '2' => 'Two',
        '3' => 'Three',
        '4' => 'Four',
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
        'CardColumns' => 'Varchar(64)',
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
     * @inheritdoc
     */
    public function getCMSFields()
    {
        $fields = parent::getCmsFields();

        $fields->removeByName(['ElementLinks']);

        $subType = DropdownField::create(
            'Subtype',
            _t(
                __CLASS__ . '.LISTTYPE',
               'List type'
            ),
            $this->owner->config()->subtypes
        )->setEmptyString('none');

        $cardColumns = DropdownField::create(
            'CardColumns',
            _t(
                __CLASS__ . '.CARDCOLUMNS',
               'Card columns'
            ),
            $this->owner->config()->card_columns
        )->setEmptyString('none');

        $cardColumns->displayIf('Subtype')->isEqualTo('cards');

        $cardStyle = DropdownField::create(
            'CardStyle',
            _t(
                __CLASS__ . '.CARDSTYLE',
               'Card style'
            ),
            $this->owner->config()->card_styles
        )->setEmptyString('none');
        $cardStyle->displayIf('Subtype')->isEqualTo('cards');

        $fields->addFieldsToTab(
            'Root.Main',
            [
                HTMLEditorField::create(
                    'HTML',
                    _t(
                        __CLASS__ . '.HTML',
                        'Content'
                    )
                )->setRows(6),
                $subType,
                $cardColumns,
                $cardStyle,
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

        return $fields;
    }

    /**
     * Return column string, by default based off nswds
     */
    public function getColumns() : ?string
    {
        $columns = $this->owner->CardColumns;

        if ($columns == 2) {
            return "nsw-col-sm-6";
        }
        if ($columns == 3) {
            return "nsw-col-md-4";
        }
        if ($columns == 4) {
            return "nsw-col-sm-6 nsw-col-md-4 nsw-col-lg-3";
        }

        return null;
    }

}
