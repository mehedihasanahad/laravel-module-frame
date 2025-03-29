<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class CommonFunction
{
    public static function convert2Bangla($eng_number)
    {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($eng, $ban, $eng_number);
    }

    public static function convert2English($ban_number)
    {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($ban, $eng, $ban_number);
    }

    public static function buildSQLRange($range, $field, $max_range = 2001)
    {
        $cond = '';
        $ranges = explode(',', trim($range));
        $ins = '';
        for ($i = 0; $i < count($ranges); $i++) {
            $betw = explode('-', $ranges[$i]);
            if (count($betw) == 2) {
                if ($betw[1] - $betw[0] > 0 && $betw[1] - $betw[0] < $max_range) {
                    $cond .= (strlen($cond) > 0 ? " OR $field BETWEEN " : " $field BETWEEN ") . $betw[0] . ' AND ' . $betw[1];
                }
            } else {
                $ins .= (strlen($ins) > 0 ? ',' : '') . $ranges[$i];
            }
        }
        if (strlen($ins) > 0) {
            $cond .= (strlen($cond) > 0 ? " OR $field IN(" : " $field IN(") . $ins . ')';
        }
        return trim($cond);
    }

//    public static function get_enum_values($table, $field)
//    {
//        $type = DB::select(DB::raw('SHOW COLUMNS FROM ' . $table . ' WHERE Field = "' . $field . '"'))[0]->Type;
//        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
//        $enum = explode("','", $matches[1]);
//        return $enum;
//    }

    public static function curlPostRequest($url, $postdata, $headers, $jsonData = false)
    {
        if (!$jsonData) {
            $postdata = http_build_query($postdata);
        }
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);

            if (!curl_errno($ch)) {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            } else {
                $http_code = 0;
            }
            curl_close($ch);
            return ['http_code' => intval($http_code), 'data' => $result];

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function checkSQLInjection($givenData)
    {
        $givenData = strtoupper($givenData);
        $searchData = ['INSERT', 'UPDATE', 'DELETE', 'ALTER', 'DROP', 'SHOW', ';', ' ', '='];
        $replacedData = ['', '', '', '', '', '', '', '', ''];
        return str_replace($searchData, $replacedData, strtoupper($givenData));
    }

    public static function multiPagepdfGeneration($title, $subject, $stylesheet = '', $dataArr, $pdfFilePath, $saveOrView = 'I', $additionalData)
    {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path(),
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
            'fontdata' => $fontData + ['kalpurush' => ['R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75]],
            'default_font' => 'kalpurush', 'mode' => 'utf-8', 'format' => 'A4', 'default_font_size' => 11,
            'margin_top' => 7, 'margin_left' => 7, 'margin_right' => 7, 'margin_header' => 0, 'margin_footer' => 7,
        ]);

        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle($title);
        $mpdf->SetSubject($subject);
        $mpdf->SetAuthor("Business Automation Limited");
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->SetHTMLHeader();
        $footer = isset($additionalData['footer']) ?? $additionalData['footer'];
        $mpdf->SetHTMLFooter($footer);
        $mpdf->defaultfooterline = 0;

        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->showWatermarkImage = true;

        if ($stylesheet) {
            $mpdf->WriteHTML($stylesheet, 1);
        }
        if (count($dataArr) > 0) {
            foreach ($dataArr as $data) {
                $mpdf->AddPage();
                $html = "<h1>Welcome to PDF Generation by MPDF<h1>";     // this will be changed
                $mpdf->WriteHTML($html, 2);
            }
        } else {
            $html = "<h1>Welcome to PDF Generation by MPDF<h1>";
            $mpdf->WriteHTML($html, 2);
        }
        $mpdf->Output($pdfFilePath, $saveOrView);
        exit();
    }

    public static function qrCodeGenerate($qrCodeData, $size = 300)
    {
        return QrCode::size($size)->generate($qrCodeData);
    }

    public static function barCodeGenerate($barcodeData)
    {
        $generatorPNG = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $base64EncodedData = base64_encode($generatorPNG->getBarcode($barcodeData, $generatorPNG::TYPE_CODE_128));
        return 'data:image/png;base64,' . $base64EncodedData;
    }

    public static function fileUploadToS3($file_full_path, $filePath)
    {
        $contents = File::get($filePath);
        return Storage::disk('minio')->put($file_full_path, $contents);
    }

    public static function showErrorPublic($param, $msg = 'Sorry! Something went wrong! '): string
    {
        $j = strpos($param, '(SQL:');
        if ($j > 15) {
            $param = substr($param, 8, $j - 9);
        }

        return $msg . $param;
    }

   static function resizeImageAndMoveToDirectories($file, $destination, $width, $height, $fileNamePrefix): array
   {
        try {
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $filename = uniqid($fileNamePrefix) . $file->getClientOriginalName();
            $fileStoredPath = $destination . '/' . $filename;
            $img = Image::make($file->getRealPath())->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($fileStoredPath);
            return [
                'status' => 200,
                'imagePath' => $fileStoredPath
            ];
        } catch (\Exception $e) {
            dd('M:' . $e->getMessage(), 'F:' . $e->getFile() . 'L:' . $e->getFile());
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param $base64Image
     * @param $destination
     * @param $width
     * @param $height
     * @param $fileNamePrefix
     * @return string
     */
    function base64ImageStoreAndResize($base64Image, $destination, $width, $height, $fileNamePrefix): string
    {
        try {
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $data = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($data);
            $filename = uniqid($fileNamePrefix) . '.jpeg';
            $fileStoredPath = $destination . '/' . $filename;
            $img = Image::make($imageData)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($fileStoredPath);

            return $fileStoredPath;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Create role if doesn't exist
     * @param string $rollName name of the role
     * @param array $rollPermissions permissions list
     * @return object
    */
    public function getRollInfo(string $rollName, array $rollPermissions): object {
        $role = Role::firstWhere(['name' => $rollName]);
        if (empty($role)) {
            $role = new Role();
            $role->name = $rollName;
            $role->guard_name = 'web';
            $role->save();
            $role->syncPermissions($rollPermissions);
        }
        return $role;
    }
}
