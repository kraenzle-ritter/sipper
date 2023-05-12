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

    public function getDossiers()
    {
        return $this->xml->xpath('//arelda:dossier');
    }

    public function getFirstLevelDossiers()
    {
        return $this->xml->xpath('//arelda:ordnungssystemposition/arelda:dossier');
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

    /**
     * Compose the identifier of the parent for a dossier, which has to exist in Anton
     */
    public static function getParentIdentifier(SimpleXMLElement $dossier, string $prefix = '') : string
    {
        return $prefix . (string) $dossier->xpath('parent::*')[0]->nummer;
    }

}
