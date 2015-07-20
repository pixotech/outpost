<?php

namespace Outpost\Assets;

use Outpost\Responders\Exceptions\UnrecognizedRequestException;
use Outpost\Responders\Responder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class AssetResponder extends Responder {

  /**
   * @return Response
   * @throws UnrecognizedRequestException
   */
  public function invoke() {
    if (!$this->isAssetRequest()) throw new UnrecognizedRequestException();
    try {
      return new BinaryFileResponse($this->getAssetFile(), 200);
    }
    catch (\OutOfBoundsException $e) {
      $this->getLog()->error($e->getMessage() ?: get_class($e), ['outpost' => 'assets']);
      return new Response(null, 404);
    }
    catch (\Exception $e) {
      $this->getLog()->error($e->getMessage() ?: get_class($e), ['outpost' => 'assets']);
      return new Response(null, 500);
    }
  }

  public function getAssetFile() {
    $key = $this->getRequestedAssetKey();
    $asset = $this->getSite()->getAssetMarker($key);
    $this->getSite()->clearAssetMarker($key);
    $file = $this->getSite()->getAssetFile($asset);
    return $file;
  }

  public function getAssetPathRegex() {
    $dir = preg_quote($this->getAssetsDirectoryName());
    return "|^/{$dir}/([a-f0-9]{32})\.(.+)$|";
  }

  public function getAssetsDirectoryName() {
    return '_assets';
  }

  public function getRequestedAssetKey() {
    return preg_match($this->getAssetPathRegex(), $this->getRequestPath(), $m) ? $m[1] : null;
  }

  public function isAssetRequest() {
    return preg_match($this->getAssetPathRegex(), $this->getRequestPath());
  }
}