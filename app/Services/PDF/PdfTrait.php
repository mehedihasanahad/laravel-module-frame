<?php

namespace App\Services\PDF;

trait PdfTrait
{
    /**
     * Generate pdf with cross language
     * @param  array  $configuration defined configuration, which is required
     * @return string
    */
    public function generatePDF(array $configuration) : string {
        try {
            // internal configuration
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            $mpdf = new \Mpdf\Mpdf([
                'tempDir'       => storage_path(),
                'fontDir'       => array_merge($fontDirs, [public_path('fonts')]),
                'fontdata'      => $fontData + [ 'kalpurush' => [ 'R' => 'Kalpurush.ttf', 'I' => 'Kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75 ]],
                'default_font'  => 'kalpurush','mode' => 'utf-8','format' => 'A4','default_font_size' => 11,
                'margin_top'    => 7,'margin_left' => 7,'margin_right' => 7,'margin_header' => 0,'margin_footer' => 7,
            ]);

            // watermark text
            if (!empty($configuration['watermarkText'])) {
                $mpdf->SetWatermarkText($configuration['watermarkText']);
                $mpdf->showWatermarkText = true;
            }

            // watermark image
            if (!empty($configuration['watermarkImage']['src'])) {
                $mpdf->SetWatermarkImage(
                    $configuration['watermarkImage']['src'],
                    $configuration['watermarkImage']['alpha'],
                    [$configuration['watermarkImage']['width'], $configuration['watermarkImage']['height']],
                    [$configuration['watermarkImage']['position_left'], $configuration['watermarkImage']['position_top']]
                );
                $mpdf->showWatermarkImage = true;
            }

            // print pdf
            $mpdf->WriteHTML(view($configuration['blade_file_path'] ?? abort(500, 'You have to provide blade file path'), ($configuration['blade_file_data'] ?? [])));
            if ($configuration['destination']) {
                if (!file_exists($configuration['destination'])) {
                    mkdir( $configuration['destination'], 0777, true );
                }
                $filepth = $configuration['destination']. '/'. uniqid($configuration['fileNamePrefix']). '.pdf';
                $mpdf->Output($filepth, !empty($configuration['viewMode']) ? 'I' : 'F');
                return $filepth;
            } else abort(500, 'You have to must provide destination path');
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
