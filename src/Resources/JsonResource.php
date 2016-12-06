<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;

class JsonResource extends HttpResource implements JsonResourceInterface
{
    protected $returnArrays = true;

    public function __invoke(SiteInterface $site)
    {
        return $this->decodeJson(parent::__invoke($site));
    }

    /**
     * @param string $json
     * @return mixed
     */
    protected function decodeJson($json)
    {
        $json = (string)$json;
        if (null === $data = json_decode($json, $this->returnArrays)) {
            if ($json !== '' && $json !== 'null') {
                throw new InvalidJsonException($json);
            }
        }
        return $data;
    }
}
