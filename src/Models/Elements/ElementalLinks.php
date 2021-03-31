<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\FieldList;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\DropdownField;
use gorriecoe\Link\Models\Link;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class ElementalLinks extends BaseElement
{

    private static $icon = 'font-icon-thumbnails';

    private static $table_name = 'ElementalLinks';

    private static $title = 'Links list';

    private static $description = "Display a list of links";

    private static $singular_name = 'Links Element';

    private static $plural_name = 'Links Elements';

    private static $inline_editable = false;

    private static $subtypes = [
        'cards' => 'Cards',
        'feature-tile' => 'Feature tile',
        'link-list' => 'Link list'
    ];

    private static $card_columns = [
        '2' => 'Two',
        '3' => 'Three',
        '4' => 'Four',
    ];

    private static $card_styles = [
        'title' => 'Title only',
        'title-abstract' => 'Title and abstract',
        'title-image-abstract' => 'Title, image, abstract',
    ];

    private static $db = [
        'HTML' => 'HTMLText',
        'Subtype' => 'Varchar(64)',
        'CardColumns' => 'Varchar(64)',
        'CardStyle' => 'Varchar(64)',
    ];

    private static $many_many = [
        'ElementLinks' => Link::class
    ];

    private static $many_many_extraFields = [
        'ElementLinks' => [
            'Sort' => 'Int'
        ]
    ];

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Links');
    }

    public function getCMSFields()
    {
        $fields = parent::getCmsFields();

        $fields->removeByName(['ElementLinks']);

        $subType = DropdownField::create('Subtype',_t(__CLASS__ . '.LISTTYPE','List type'),$this->owner->config()->subtypes);
        $subType->setEmptyString('none');

        $cardColumns = DropdownField::create('CardColumns',_t(__CLASS__ . '.CARDCOLUMNS','Card columns'),$this->owner->config()->card_columns);
        $cardColumns->setEmptyString('none');
        $cardColumns->displayIf('Subtype')->isEqualTo('cards');

        $cardStyle = DropdownField::create('CardStyle',_t(__CLASS__ . '.CARDSTYLE','Card style'),$this->owner->config()->card_styles);
        $cardStyle->setEmptyString('none');
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
                    _t(__CLASS__ . '.LINKS','Links'),
                    $this
                )->setSortColumn('Sort')
            ]
        );

        return $fields;
    }

    public function getColumns()
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

        return false;
    }

}
