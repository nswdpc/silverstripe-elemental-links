<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\ElementContent;
use SilverStripe\Forms\FieldList;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\DropdownField;
use gorriecoe\Link\Models\Link;

class ElementalLinks extends ElementContent
{

    private static $icon = 'font-icon-thumbnails';

    private static $table_name = 'ElementalLinks';

    private static $title = 'Links list';

    private static $description = "Display a list of links";

    private static $singular_name = 'Links Element';

    private static $plural_name = 'Links Elements';

    private static $inline_editable = false;

    private static $subtypes = [
        'cards' => 'Three column cards',
        'feature-tile' => 'Feature tile',
        'link-list' => 'Link list'
    ];

    private static $db = [
        'Subtype' => 'Varchar'
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

    public function getCmsFields()
    {
        $fields = parent::getCmsFields();

        $fields->removeByName(['ElementLinks']);

        $fields->addFieldsToTab(
            'Root.Main',
            [
                DropdownField::create(
                    'Subtype',
                    _t(__CLASS__ . '.LISTTYPE','List type'),
                    $this->owner->config()->subtypes
                ),
                LinkField::create(
                    'ElementLinks',
                    _t(__CLASS__ . '.LINKS','Links'),
                    $this
                )->setSortColumn('Sort')
            ]
        );


        return $fields;
    }

}
