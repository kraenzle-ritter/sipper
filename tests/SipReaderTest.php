<?php

namespace KraenzleRitter\Sipper\Tests;

use KraenzleRitter\Sipper\SipReader;
use PHPUnit\Framework\TestCase;

class SipReaderTest extends TestCase
{
    private string $metadata1_1;
    private string $metadata1_2;
    private string $metadata1_3;
    
    private SipReader $sipReader1_1;
    private SipReader $sipReader1_2;
    private SipReader $sipReader1_3;

    protected function setUp(): void
    {
        $this->metadata1_1 = file_get_contents(__DIR__ . '/examples/metadata_1-1.xml');
        $this->metadata1_2 = file_get_contents(__DIR__ . '/examples/metadata_1-2.xml');
        $this->metadata1_3 = file_get_contents(__DIR__ . '/examples/metadata_1-3.xml');
        
        $this->sipReader1_1 = new SipReader($this->metadata1_1);
        $this->sipReader1_2 = new SipReader($this->metadata1_2);
        $this->sipReader1_3 = new SipReader($this->metadata1_3);
    }

    public function testConstructorRegistersNamespace(): void
    {
        $sipReader = new SipReader($this->metadata1_2);
        $this->assertNotNull($sipReader->xml);
        
        // Test that XPath with namespace works
        $paketElements = $sipReader->xml->xpath('//arelda:paket');
        $this->assertCount(1, $paketElements);
    }

    public function testGetDokumente(): void
    {
        // Test with metadata_1-2
        $dokumente = $this->sipReader1_2->getDokumente();
        $this->assertGreaterThan(0, count($dokumente));
        
        // Test with metadata_1-3 (should have more documents)
        $dokumente1_3 = $this->sipReader1_3->getDokumente();
        $this->assertGreaterThan(0, count($dokumente1_3));
        
        // Test with metadata_1-1
        $dokumente1_1 = $this->sipReader1_1->getDokumente();
        $this->assertGreaterThan(0, count($dokumente1_1));
    }

    public function testGetDossiers(): void
    {
        // Test with metadata_1-2
        $dossiers = $this->sipReader1_2->getDossiers();
        $this->assertGreaterThan(0, count($dossiers));
        
        // Test with metadata_1-3
        $dossiers1_3 = $this->sipReader1_3->getDossiers();
        $this->assertGreaterThan(0, count($dossiers1_3));
        
        // Test with metadata_1-1
        $dossiers1_1 = $this->sipReader1_1->getDossiers();
        $this->assertGreaterThan(0, count($dossiers1_1));
    }

    public function testGetFirstLevelDossiers(): void
    {
        // Test with metadata_1-2
        $firstLevelDossiers = $this->sipReader1_2->getFirstLevelDossiers();
        $this->assertGreaterThan(0, count($firstLevelDossiers));
        
        // Test with metadata_1-3
        $firstLevelDossiers1_3 = $this->sipReader1_3->getFirstLevelDossiers();
        $this->assertGreaterThan(0, count($firstLevelDossiers1_3));
        
        // Test with metadata_1-1
        $firstLevelDossiers1_1 = $this->sipReader1_1->getFirstLevelDossiers();
        $this->assertGreaterThan(0, count($firstLevelDossiers1_1));
    }

    public function testGetDokumentByDateiRef(): void
    {
        // Test with metadata_1-2 - known dateiRef
        $dokument = $this->sipReader1_2->getDokumentByDateiRef('DAT0');
        $this->assertNotNull($dokument);
        $this->assertEquals('IdDok-91', (string)$dokument['id']);
        
        // Test with metadata_1-3 - same dateiRef should exist
        $dokument1_3 = $this->sipReader1_3->getDokumentByDateiRef('DAT0');
        $this->assertNotNull($dokument1_3);
    }

    public function testGetDateiByDateiRef(): void
    {
        // Test with metadata_1-2
        $datei = $this->sipReader1_2->getDateiByDateiRef('DAT0');
        $this->assertNotNull($datei);
        $this->assertEquals('DAT0', (string)$datei['id']);
        $this->assertEquals('Auftragsuebersicht.pdf', (string)$datei->name);
        
        // Test with metadata_1-3
        $datei1_3 = $this->sipReader1_3->getDateiByDateiRef('DAT21');
        $this->assertNotNull($datei1_3);
        $this->assertEquals('gpl2.pdf', (string)$datei1_3->name);
    }

    public function testGetPathByDateiRef(): void
    {
        // Test with metadata_1-2
        $path = $this->sipReader1_2->getPathByDateiRef('DAT0');
        $expectedPath = 'content' . DIRECTORY_SEPARATOR . 'DOS_01' . DIRECTORY_SEPARATOR . 'Auftragsuebersicht.pdf';
        $this->assertEquals($expectedPath, $path);
        
        // Test with metadata_1-3 - file in different folder
        $path1_3 = $this->sipReader1_3->getPathByDateiRef('DAT21');
        $expectedPath1_3 = 'content' . DIRECTORY_SEPARATOR . 'DOS_02' . DIRECTORY_SEPARATOR . 'gpl2.pdf';
        $this->assertEquals($expectedPath1_3, $path1_3);
        
        // Test with file in subfolder
        $path1_3_sub = $this->sipReader1_3->getPathByDateiRef('DAT301');
        $expectedPath1_3_sub = 'content' . DIRECTORY_SEPARATOR . 'DOS_03' . DIRECTORY_SEPARATOR . '00000004.jp2';
        $this->assertEquals($expectedPath1_3_sub, $path1_3_sub);
    }

    public function testGetDokumentTitelByFilenameWithValidFiles(): void
    {
        // Test with metadata_1-2
        $titel = $this->sipReader1_2->getDokumentTitelByFilename('Auftragsuebersicht.pdf');
        $this->assertEquals('Dokument Allgemeines zum Test', $titel);
        
        $titel2 = $this->sipReader1_2->getDokumentTitelByFilename('gpl2.pdf');
        $this->assertEquals('Dokument Lizenz', $titel2);
        
        $titel3 = $this->sipReader1_2->getDokumentTitelByFilename('Licence.txt');
        $this->assertEquals('Dokument Lizenz', $titel3);
        
        // Test with metadata_1-3 - should return same results
        $titel1_3 = $this->sipReader1_3->getDokumentTitelByFilename('Auftragsuebersicht.pdf');
        $this->assertEquals('Dokument Allgemeines zum Test', $titel1_3);
        
        $titel1_3_2 = $this->sipReader1_3->getDokumentTitelByFilename('Lieferschein_a.jp2');
        $this->assertEquals('Lieferschein Glockenaepfel', $titel1_3_2);
    }

    public function testGetDokumentTitelByFilenameWithInvalidFile(): void
    {
        // Test with non-existing file
        $titel = $this->sipReader1_2->getDokumentTitelByFilename('nonexistent.pdf');
        $this->assertEquals('', $titel);
        
        $titel1_3 = $this->sipReader1_3->getDokumentTitelByFilename('does-not-exist.txt');
        $this->assertEquals('', $titel1_3);
    }

    public function testGetDokumentTitelByFilenameWithMetadata1_1(): void
    {
        // Test with metadata_1-1 (different structure)
        $titel = $this->sipReader1_1->getDokumentTitelByFilename('{3AD4D0BA-933C-48E5-A897-41A79638DFF8}.pdf');
        $this->assertNotEmpty($titel);
        
        $titel2 = $this->sipReader1_1->getDokumentTitelByFilename('{46E5084F-407D-4582-8BEE-1D69FC0FC8E5}.pdf');
        $this->assertNotEmpty($titel2);
    }

    public function testGetParentIdentifier(): void
    {
        // Test with a dossier from metadata_1-2
        $dossiers = $this->sipReader1_2->getDossiers();
        $this->assertNotEmpty($dossiers);
        
        $firstDossier = $dossiers[0];
        $parentId = SipReader::getParentIdentifier($firstDossier);
        $this->assertNotEmpty($parentId);
        
        // Test with prefix
        $parentIdWithPrefix = SipReader::getParentIdentifier($firstDossier, 'PREFIX_');
        $this->assertStringStartsWith('PREFIX_', $parentIdWithPrefix);
    }

    public function testAllFunctionsWithDifferentMetadataVersions(): void
    {
        $readers = [
            'metadata_1-1' => $this->sipReader1_1,
            'metadata_1-2' => $this->sipReader1_2,
            'metadata_1-3' => $this->sipReader1_3
        ];
        
        foreach ($readers as $version => $reader) {
            // Test that all basic functions run without errors
            $dokumente = $reader->getDokumente();
            $this->assertIsArray($dokumente, "getDokumente failed for $version");
            
            $dossiers = $reader->getDossiers();
            $this->assertIsArray($dossiers, "getDossiers failed for $version");
            
            $firstLevelDossiers = $reader->getFirstLevelDossiers();
            $this->assertIsArray($firstLevelDossiers, "getFirstLevelDossiers failed for $version");
        }
    }

    public function testComplexFilenameHandling(): void
    {
        // Test with files that have special characters or complex names
        $testFiles = [
            'Lieferschein_a.jp2' => 'Lieferschein Glockenaepfel',
            'WAVE-Testdatei.wav' => 'Dokument Allgemeines zum Test',
            '00000004.jp2' => 'Dokument Beispielsammlung diverser Rueckseiten'
        ];
        
        foreach ($testFiles as $filename => $expectedPartialTitle) {
            $titel = $this->sipReader1_3->getDokumentTitelByFilename($filename);
            $this->assertNotEmpty($titel, "No title found for $filename");
            $this->assertIsString($titel, "Title should be string for $filename");
        }
    }

    public function testGetDateiByDateiRefThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No datei found for dateiRef: NONEXISTENT');
        $this->sipReader1_2->getDateiByDateiRef('NONEXISTENT');
    }

    public function testGetDokumentByDateiRefThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No document found for dateiRef: NONEXISTENT');
        $this->sipReader1_2->getDokumentByDateiRef('NONEXISTENT');
    }
}
