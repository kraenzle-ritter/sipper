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
        $results = $this->xml->xpath('//arelda:dateiRef[text()="'.$dateiRef.'"]/..');
        if (empty($results)) {
            throw new \InvalidArgumentException("No document found for dateiRef: $dateiRef");
        }
        return $results[0];
    }

    public function getDateiByDateiRef(string $dateiRef) : SimpleXMLElement
    {
        $results = $this->xml->xpath('//arelda:datei[@id="'.$dateiRef.'"]');
        if (empty($results)) {
            throw new \InvalidArgumentException("No datei found for dateiRef: $dateiRef");
        }
        return $results[0];
    }

    public function getPathByDateiRef(string $dateiRef) : string
    {
        $paths =  $this->xml->xpath('//arelda:datei[@id="'.$dateiRef.'"]/ancestor-or-self::*/arelda:name/text()');

        return join(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * Get document title by filename
     * @param string $filename The name of the file
     * @return string The title of the document that contains this file, or empty string if not found
     */
    public function getDokumentTitelByFilename(string $filename) : string
    {
        // First, find the datei element with the given filename
        $dateiElements = $this->xml->xpath('//arelda:datei[arelda:name="'.$filename.'"]');
        
        if (empty($dateiElements)) {
            return '';
        }
        
        // Get the ID of the first matching datei element
        $dateiId = (string) $dateiElements[0]['id'];
        
        // Find the dokument that references this datei ID
        $dokumentElements = $this->xml->xpath('//arelda:dokument[arelda:dateiRef="'.$dateiId.'"]');
        
        if (empty($dokumentElements)) {
            return '';
        }
        
        // Register namespace for the dokument element and get the title
        $dokumentElements[0]->registerXPathNamespace('arelda', 'http://bar.admin.ch/arelda/v4');
        $titelElements = $dokumentElements[0]->xpath('arelda:titel');
        return !empty($titelElements) ? (string) $titelElements[0] : '';
    }

    /**
     * Compose the identifier of the parent for a dossier, which has to exist in Anton
     */
    public static function getParentIdentifier(SimpleXMLElement $dossier, string $prefix = '') : string
    {
        return $prefix . (string) $dossier->xpath('parent::*')[0]->nummer;
    }

}
