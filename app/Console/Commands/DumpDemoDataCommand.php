<?php


namespace App\Console\Commands;


use Module\Vendor\Command\BaseDumpDemoDataCommand;

class DumpDemoDataCommand extends BaseDumpDemoDataCommand
{
    public function handle()
    {
        $data = [
            'inserts' => $this->buildInsert(
                ['config', ['key', 'value'], function ($item) {
                    return in_array($item['key'], [

                        'siteName',
                        'siteDescription',
                        'siteKeywords',
                        'siteLogo',
                        'siteSlogan',
                        'siteDomain',

                        'Blog_Name',
                        'Blog_Slogan',
                        'Blog_Avatar',
                        'Blog_ContactQQ',
                        'Blog_ContactWeibo',
                        'Blog_ContactWechat',

                        'Blog_AboutContent',
                    ]);
                }],
                'blog',
                'blog_category',
                'banner',
                'partner',
                'nav'
            ),
            'updates' => $this->buildUpdate(),
        ];
        $this->buildDump($data);
    }
}
