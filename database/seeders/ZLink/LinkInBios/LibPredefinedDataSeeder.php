<?php

namespace Database\Seeders\ZLink\LinkInBios;

use App\Models\Default\User;
use App\Models\ZLink\LinkInBios\LibPredefinedData;
use App\Zaions\Enums\LibPreDefinedDataModalEnum;
use Illuminate\Database\Seeder;

class LibPredefinedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        $user = User::where('email', env('ADMIN_EMAIL'))->first();

        $perDefinedDataArray = [
            // Messenger Platform
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => "email",
                "title" => "Email",
                "icon" => "emailLogo",
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::whatsapp->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "whatsApp",
                "icon" => "whatsAppLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::messenger->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "Messenger",
                "icon" => "messengerLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::call->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "Call",
                "icon" => "callLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::sms->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "SMS",
                "icon" => "smsLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::telegram->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "Telegram",
                "icon" => "telegramLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::skype->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "Skype",
                "icon" => "skypeLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::wechat->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "WeChat",
                "icon" => "wechatLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::line->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "Line",
                "icon" => "lineLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::viber->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::messengerPlatform->name,
                "title" => "Viber",
                "icon" => "viberLogo",
                "isActive" => true
            ],

            // Music Platform
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::spotify->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Spotify",
                "icon" => "spotifyLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::soundCloud->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Sound Cloud",
                "icon" => "soundCloudLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::googleMusic->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Google Music",
                "icon" => "googleMusicLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::appleMusic->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Apple Music",
                "icon" => "appleMusicLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::youtube->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Youtube",
                "icon" => "youtubeLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::deezer->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Deezer",
                "icon" => "deezerLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::amazonMusic->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Amazon Music",
                "icon" => "amazonMusicLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::napster->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::musicPlatform->name,
                "title" => "Napster",
                "icon" => "napsterLogo",
                "isActive" => true
            ],

            // Social Platform
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::tiktok->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Tiktok",
                "icon" => "tiktokLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::facebook->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Facebook",
                "icon" => "facebookLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::instagram->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Instagram",
                "icon" => "instagramLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::twitter->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Twitter",
                "icon" => "twitterLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::linkedin->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Linkedin",
                "icon" => "linkedinLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::slack->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Slack",
                "icon" => "slackLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::youtube->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Youtube",
                "icon" => "youtubeLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::pinterest->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::socialPlatform->name,
                "title" => "Pinterest",
                "icon" => "pinterestLogo",
                "isActive" => true
            ],

            // form fields
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::title->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Title",
                "icon" => "headingIcon",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::firstName->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "First Name",
                "icon" => "userIcon_1",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::lastName->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Last Name",
                "icon" => "userIcon_2",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::email->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Email",
                "icon" => "emailLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::phone->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Phone",
                "icon" => "callLogo",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::text->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Text",
                "icon" => "textIcon",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::date->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Date",
                "icon" => "calenderIcon",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::website->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::formField->name,
                "title" => "Website",
                "icon" => "linkIcon",
                "isActive" => true
            ],

            // Blocks
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::button->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Button",
                "icon" => "buttonBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::text->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Text",
                "icon" => "textBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::countdown->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Timer",
                "icon" => "timerBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::card->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Card",
                "icon" => "cardClipBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::carousel->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Carousel",
                "icon" => "carouselBlock",
                "isActive" => true
            ],
            // [
            //     'uniqueId' => uniqid(),
            //     'userId' => $user->id,
            //     "type" => LibPreDefinedDataModalEnum::RSS->name,
            //     "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
            //     "title" => "RSS",
            //     "icon" => "RssBlock",
            //     "isActive" => true
            // ], //  once Sir Ahsan explain how to complete them then only finish them.
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::audio->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Audio",
                "icon" => "audioBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::video->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Video",
                "icon" => "videoBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::calendar->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Calendar",
                "icon" => "calenderBlock",
                "isActive" => true
            ],
            // [
            //     'uniqueId' => uniqid(),
            //     'userId' => $user->id,
            //     "type" => LibPreDefinedDataModalEnum::magento->name,
            //     "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
            //     "title" => "Magento",
            //     "icon" => "magento",
            //     "isActive" => true
            // ], //  once Sir Ahsan explain how to complete them then only finish them.
            // [
            //     'uniqueId' => uniqid(),
            //     'userId' => $user->id,
            //     "type" => LibPreDefinedDataModalEnum::wordpress->name,
            //     "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
            //     "title" => "Wordpress",
            //     "icon" => "wordpress",
            //     "isActive" => true
            // ], //  once Sir Ahsan explain how to complete them then only finish them.
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::map->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Maps",
                "icon" => "map",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::music->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Music",
                "icon" => "music",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::QAndA->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Q&A",
                "icon" => "QAndABlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::messenger->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Messenger",
                "icon" => "messengerBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::form->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Form",
                "icon" => "formBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => "social",
                "title" => "Social",
                "icon" => "socialBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => "VCard",
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Vcard",
                "icon" => "vcardBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::Iframe->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Iframe",
                "icon" => "IframeBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::spacing->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Spacing",
                "icon" => "spacingBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::separator->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Separator",
                "icon" => "separatorBlock",
                "isActive" => true
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "type" => LibPreDefinedDataModalEnum::avatar->name,
                "preDefinedDataType" => LibPreDefinedDataModalEnum::blocks->name,
                "title" => "Avatar",
                "icon" => "avatarBlock",
                "isActive" => true
            ],
        ];

        $preDefinedThemes = [
            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#0077ff',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'gradient',
                    'bgGradientColors' => [
                        'startColor' => '#ffd634',
                        'endColor' => '#ef129e',
                        'direction' => 0,
                    ],
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'gradient',
                    'bgGradientColors' => [
                        'startColor' => '#4e0f87',
                        'endColor' => '#a834ff',
                        'direction' => 0,
                    ],
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#00789e',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#0f0078',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#03bc71',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#ff0000',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#ffcb00',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#0077ff',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#8c34ff',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#93d9f7',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#cf3d3d',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#000000',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],

            [
                'uniqueId' => uniqid(),
                'userId' => $user->id,
                "background" => [
                    'bgType' => 'solidColor',
                    'bgSolidColor' => '#ffffff',
                ],
                "preDefinedDataType" => LibPreDefinedDataModalEnum::themes->name,
                "isActive" => true
            ],
        ];

        foreach ($preDefinedThemes as  $value) {
            LibPredefinedData::create($value);
        }

        foreach ($perDefinedDataArray as  $value) {
            LibPredefinedData::create($value);
        }
    }
}
