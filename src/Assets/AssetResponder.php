<?php

namespace Outpost\Assets;

use Outpost\Routing\Responder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class AssetResponder extends Responder {

  protected $assets;

  public function __construct(AssetManagerInterface $assets) {
    $this->assets = $assets;
  }

  public function __invoke($assetId) {
    if (false !== $pos = strrpos($assetId, '.')) $assetId = substr($assetId, 0, $pos);
    try {
      $file = $this->assets->getRequestedAssetFile($assetId);
      $mimeType = 'image/jpeg';
      return new BinaryFileResponse($file, 200, ['Content-Type' => $mimeType]);
    }
    catch (\OutOfBoundsException $e) {
      return new Response(null, 404);
    }
    catch (\Exception $e) {
      return new Response(null, 500);
    }
  }
}