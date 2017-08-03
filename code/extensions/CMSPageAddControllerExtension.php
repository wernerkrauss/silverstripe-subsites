<?php

namespace SilverStripe\Subsites\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Subsites\Model\Subsite;

class CMSPageAddControllerExtension extends Extension
{
    public function updatePageOptions(&$fields)
    {
        $fields->push(HiddenField::create('SubsiteID', 'SubsiteID', Subsite::currentSubsiteID()));
    }
}
