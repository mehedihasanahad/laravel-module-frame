<?php

namespace App\Http\Controllers;

use App\Services\PDF\PdfTrait;
use App\Services\Image\ImageOptimizer;
use Illuminate\Http\Request;

class checkServices extends Controller
{
    use PdfTrait, ImageOptimizer;

    public function storeAndGetPDFLink() {
       $this->generatePDF([
           'blade_file_path' => 'test.pdfCheck',
           'blade_file_data' => [],
           'watermarkText' => 'Hello',
           'watermarkImage' => [
               'src' => 'https://media.istockphoto.com/id/1146517111/photo/taj-mahal-mausoleum-in-agra.jpg?s=612x612&w=0&k=20&c=vcIjhwUrNyjoKbGbAQ5sOcEzDUgOfCsm9ySmJ8gNeRk=',
               'alpha' => 0.5,
               'width' => 50,
               'height' => 10,
               'position_left' => 10,
               'position_top' => 50
           ],
           'destination' => public_path('pdf_service'),
           'fileNamePrefix' => 'DD',
           'viewMode' => true
       ]);
    }
}
