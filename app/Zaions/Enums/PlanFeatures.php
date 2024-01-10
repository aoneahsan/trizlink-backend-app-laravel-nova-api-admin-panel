<?php

namespace App\Zaions\Enums;

enum PlanFeatures: string
{
  case workspace = 'workspace';
  case shortLinks = 'shortLinks';
  case linkInBio = 'linkInBio';
  case members = 'members';
  case shortLinksFolder = 'shortLinksFolder';
  case linksInBioFolder = 'linksInBioFolder';
  case redirects = 'redirects';
  case brandedLinks = 'brandedLinks';
  case autoBrandedLinks = 'autoBrandedLinks';
  case customBackHalves = 'customBackHalves';
  case linkClicks = 'linkClicks';
  case mobileDeepLinks = 'mobileDeepLinks';
  case bulkShortening = 'bulkShortening';
  case qrCodes = 'qrCodes';
  case customLogo = 'customLogo';
  case colorOptions = 'colorOptions';
  case frames = 'frames';
  case patternCustomizations = 'patternCustomizations';
  case downloadFormat = 'downloadFormat';
  case scans = 'scans';
  case quantity = 'quantity';
  case branding = 'branding';
  case preDesignedThemes = 'preDesignedThemes';
  case customBackgroundColors = 'customBackgroundColors';
  case buttonAndFontStyles = 'buttonAndFontStyles';
  case buttonThumbnails = 'buttonThumbnails';
  case profileAndBackgroundImageUpload = 'profileAndBackgroundImageUpload';
  case linkInBioSocialIcons = 'linkInBioSocialIcons';
  case linkInBioQRCode = 'linkInBioQRCode';
}
