<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminContentMultilanguage extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#mw-admin-container';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        // $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function addLanguage(Browser $browser, $locale) {

        if (is_lang_supported($locale)) {
            return true;
        }

        $browser->visit(route('admin.multilanguage.index'));
        $browser->waitForText('Add new language');
        $browser->select('.js-dropdown-text-language', $locale);
        $browser->pause(3000);
        $browser->click('.js-add-language');
        $browser->pause(2000);
        $browser->waitForText($locale);

    }

    public function fillTitle(Browser $browser, $title, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($title, $locale) {
            $browser->fillInput('title', $title, $locale);
        });
    }

    public function fillUrl(Browser $browser, $url, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($url, $locale) {
            $browser->fillInput('url', $url, $locale);
        });
    }

    public function fillContent(Browser $browser, $content, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($content, $locale) {
            $browser->fillMwEditor('content', $content, $locale);
        });
    }

    public function fillContentBody(Browser $browser, $contentBody, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($contentBody, $locale) {
            $browser->fillMwEditor('content_body', $contentBody, $locale);
        });
    }

    /**
     * SEO META
     */
    public function fillContentMetaTitle(Browser $browser, $title, $locale)
    {
        $browser->scrollTo('.js-card-search-engine');
        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#seo-settings'))->isDisplayed()) {
            $browser->script('$(".js-card-search-engine a.btn").click();');
            $browser->pause(1000);
        }

        $browser->within(new AdminMultilanguageFields, function ($browser) use ($title, $locale) {
            $browser->fillInput('content_meta_title', $title, $locale);
        });
    }

    public function fillContentMetaKeywords(Browser $browser, $keywords, $locale)
    {
        $browser->scrollTo('.js-card-search-engine');
        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#seo-settings'))->isDisplayed()) {
            $browser->script('$(".js-card-search-engine a.btn").click();');
            $browser->pause(1000);
        }

        $browser->within(new AdminMultilanguageFields, function ($browser) use ($keywords, $locale) {
            $browser->fillInput('content_meta_keywords', $keywords, $locale);
        });
    }

    public function fillDescription(Browser $browser, $description, $locale)
    {
        $browser->scrollTo('.js-card-search-engine');
        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#seo-settings'))->isDisplayed()) {
            $browser->script('$(".js-card-search-engine a.btn").click();');
            $browser->pause(1000);
        }

        $browser->within(new AdminMultilanguageFields, function ($browser) use ($description, $locale) {
            $browser->fillMwEditor('description', $description, $locale);
        });
    }
}
