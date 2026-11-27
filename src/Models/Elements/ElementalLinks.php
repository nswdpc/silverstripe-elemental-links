<?php

namespace NSWDPC\Elemental\Models\LinksBlock;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\LinkField\Models\Link as CoreLink;
use SilverStripe\LinkField\Form\MultiLinkField as CoreMultiLinkField;
use NSWDPC\GridHelper\Models\Configuration;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

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
 * @method \SilverStripe\ORM\HasManyList<\SilverStripe\LinkField\Models\Link> CoreElementLinks()
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
    private static string $class_description = "Display a list of links";

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

    private static array $has_many = [
        'CoreElementLinks' => CoreLink::class . '.Owner'
    ];

    private static array $owns = [
        'CoreElementLinks'
    ];

    private static array $cascade_deletes = [
        'CoreElementLinks',
    ];

    private static array $cascade_duplicates = [
        'CoreElementLinks',
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
     * Template method calling getElementLinks()
     */
    public function ElementLinks(): \SilverStripe\Model\List\SS_List
    {
        return $this->getElementLinks();
    }

    /**
     * Override default list value returned to ensure sorted by the many_many relation field
     */
    public function getElementLinks(): \SilverStripe\Model\List\SS_List
    {
        return $this->getDerivedElementLinksList();
    }

    /**
     * Template method for getDerivedElementLinksList()
     */
    public function DerivedElementLinksList(): \SilverStripe\Model\List\SS_List
    {
        return $this->getDerivedElementLinksList();
    }

    /**
     * Returns the relevant list of links
     */
    public function getDerivedElementLinksList(): \SilverStripe\Model\List\SS_List
    {

        return $this->CoreElementLinks()->sort(['Sort' => 'ASC']);
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
                CoreMultiLinkField::create(
                    'CoreElementLinks',
                    _t('nswds.LINKS_NEW', 'Links (new)')
                )
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
