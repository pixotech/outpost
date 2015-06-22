<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) include_once __DIR__ . '/../vendor/autoload.php';

include_once __DIR__ . '/Environments/MockEnvironment.php';
include_once __DIR__ . '/Images/ImageTestCase.php';
include_once __DIR__ . '/Images/IsDimensionsConstraint.php';
include_once __DIR__ . '/Images/MockImage.php';
include_once __DIR__ . '/Images/TestImageFile.php';
include_once __DIR__ . '/Structures/Trees/TestNode.php';
include_once __DIR__ . '/Web/MockClient.php';
include_once __DIR__ . '/Web/Requests/MockRequest.php';
include_once __DIR__ . '/Web/Requests/MockCacheableRequest.php';
