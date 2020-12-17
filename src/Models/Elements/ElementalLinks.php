<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\ElementContent;
use gorriecoe\Link\Models\Link;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\FieldList;

class ElementalLinks extends ElementContent
{

    private static $icon = 'font-icon-thumbnails';

    private static $table_name = 'ElementalLinks';

    private static $title = 'Links list';

    private static $description = "Display a list of links";

    private static $singular_name = 'Links Element';

    private static $plural_name = 'Links Elements';

    private static $inline_editable = false;

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
                LinkField::create(
                    'ElementLinks',
                    'Links',
                    $this
                )->setSortColumn('Sort')
            ]
        );

        return $fields;
    }

}
