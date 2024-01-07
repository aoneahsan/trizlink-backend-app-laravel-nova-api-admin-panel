<?php

namespace App\Zaions\Enums;

enum PlansLimitFeaturesEnum: string
{
  case linkManagement = 'linkManagement';
  case qrCodes = 'qrCodes';
  case linkInBio = 'linkInBio';
  case customDomainEssentials = 'customDomainEssentials';
  case AnalyticsAndReporting = 'AnalyticsAndReporting';
  case campaignManagement = 'campaignManagement';
  case dataDelivery = 'dataDelivery';
  case platformAccess = 'platformAccess';
  case adminFeatures = 'adminFeatures';
  case customerSuccess = 'customerSuccess';
}
