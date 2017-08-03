<?php

namespace SilverStripe\Subsites\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\Subsites\Model\Subsite;

class ErrorPageSubsite extends DataExtension
{
    /**
     * Alter file path to generated a static (static) error page file to handle error page template on different sub-sites.
     *
     * @see Error::get_filepath_for_errorcode()
     *
     * FIXME since {@link Subsite::currentSubsite()} partly relies on Session, viewing other sub-site (including main site) between
     * opening ErrorPage in the CMS and publish ErrorPage causes static error page to get generated incorrectly.
     *
     * @param $statusCode
     * @param null $locale
     *
     * @return string
     */
    public function alternateFilepathForErrorcode($statusCode, $locale = null)
    {
        $static_filepath = Config::inst()->get($this->owner->ClassName, 'static_filepath');
        $subdomainPart = '';

        // Try to get current subsite from session
        $subsite = Subsite::currentSubsite();

        // since this function is called from Page class before the controller is created, we have to get subsite from domain instead
        if (!$subsite) {
            $subsiteID = Subsite::getSubsiteIDForDomain();
            if ($subsiteID != 0) {
                $subsite = DataObject::get_by_id(Subsite::class, $subsiteID);
            } else {
                $subsite = null;
            }
        }

        if ($subsite) {
            $subdomain = $subsite->domain();
            $subdomainPart = "-{$subdomain}";
        }

        if (singleton(SiteTree::class)->hasExtension('Translatable') && $locale && $locale != Translatable::default_locale()) {
            $filepath = $static_filepath."/error-{$statusCode}-{$locale}{$subdomainPart}.html";
        } else {
            $filepath = $static_filepath."/error-{$statusCode}{$subdomainPart}.html";
        }

        return $filepath;
    }
}
