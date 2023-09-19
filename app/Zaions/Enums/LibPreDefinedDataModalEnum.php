<?php

namespace App\Zaions\Enums;


enum LibPreDefinedDataModalEnum: string
{
    // Modal types
  case themes = 'themes';
  case block = 'block';
  case musicPlatform = 'musicPlatform';
  case messengerPlatform = 'messengerPlatform';
  case socialPlatform = 'socialPlatform';
  case formField = 'formField';
  case blocks = 'blocks';

  public static function getValues(): array
  {
    return [
      self::block,
      self::musicPlatform,
      self::messengerPlatform,
      self::socialPlatform,
      self::formField,
      self::blocks,
    ];
  }

    // global
  case youtube = 'youtube';
  case email = 'email';
  case text = 'text';
  case messenger = 'messenger';

    // Lib messenger platform 
  case whatsapp = 'whatsapp';
  case call = 'call';
  case sms = 'sms';
  case telegram = 'telegram';
  case skype = 'skype';
  case wechat = 'wechat';
  case line = 'line';
  case viber = 'viber';

    // Lib Music platform
  case spotify = 'spotify';
  case soundCloud = 'soundCloud';
  case googleMusic = 'googleMusic';
  case appleMusic = 'appleMusic';
  case deezer = 'deezer';
  case amazonMusic = 'amazonMusic';
  case napster = 'napster';

    // Lib Social Platform
  case tiktok = 'tiktok';
  case facebook = 'facebook';
  case instagram = 'instagram';
  case twitter = 'twitter';
  case linkedin = 'linkedin';
  case slack = 'slack';
  case pinterest = 'pinterest';

    // Lib form fields
  case title = 'title';
  case firstName = 'firstName';
  case lastName = 'lastName';
  case phone = 'phone';
  case date = 'date';
  case website = 'website';

    // Lib Blocks
  case button = 'button';
  case countdown = 'countdown';
  case card = 'card';
  case carousel = 'carousel';
  case RSS = 'RSS';
  case audio = 'audio';
  case video = 'video';
  case calendar = 'calendar';
  case magento = 'magento';
  case wordpress = 'wordpress';
  case map = 'map';
  case music = 'music';
  case QAndA = 'QAndA';
  case form = 'form';
  case Iframe = 'Iframe';
  case spacing = 'spacing';
  case separator = 'separator';
  case avatar = 'avatar';
}
