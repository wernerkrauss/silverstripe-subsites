<?php

use SilverStripe\Forms\HiddenField;
use SilverStripe\Core\Extension;

class CMSPageAddControllerExtension extends Extension
{
    public function updatePageOptions(&$fields)
    {
        $fields->push(HiddenField::create('SubsiteID', 'SubsiteID', Subsite::currentSubsiteID()));
    }
}
