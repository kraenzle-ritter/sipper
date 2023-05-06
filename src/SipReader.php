<?php

namespace KraenzleRitter\Sipper;

use SimpleXMLElement;

class SipReader
{
    public $xml;

    public function __construct(string $metadata_xml) {
        $this->xml = simplexml_load_string($metadata_xml);
        $this->xml->registerXPathNamespace('arelda', 'http://bar.admin.ch/arelda/v4');
    }

    public function getDokumente()
    {
        return $this->xml->xpath('//arelda:dokument');
    }

    public function getDokumentByDateiRef(string $dateiRef) : SimpleXMLElement
    {
        return $this->xml->xpath('//arelda:dateiRef[text()="'.$dateiRef.'"]/..')[0];
    }

    public function getDateiByDateiRef(string $dateiRef) : SimpleXMLElement
    {
        return $this->xml->xpath('//arelda:datei[@id="'.$dateiRef.'"]')[0];
    }

    public function getPathByDateiRef(string $dateiRef) : string
    {
        $paths =  $this->xml->xpath('//arelda:datei[@id="'.$dateiRef.'"]/ancestor-or-self::*/arelda:name/text()');

        return join(DIRECTORY_SEPARATOR, $paths);
    }

}
