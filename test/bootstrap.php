<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) include_once __DIR__ . '/../vendor/autoload.php';

include_once __DIR__ . '/Assets/MockAsset.php';
include_once __DIR__ . '/Environments/MockEnvironment.php';
include_once __DIR__ . '/MockSite.php';
include_once __DIR__ . '/Images/ImageTestCase.php';
include_once __DIR__ . '/Images/IsDimensionsConstraint.php';
include_once __DIR__ . '/Images/MockImage.php';
include_once __DIR__ . '/Images/TestImageFile.php';
include_once __DIR__ . '/Resources/MockCacheableResource.php';
include_once __DIR__ . '/Web/MockClient.php';
include_once __DIR__ . '/Web/Requests/MockRequest.php';
