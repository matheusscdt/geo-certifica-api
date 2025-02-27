<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Beganovich\Snappdf\Snappdf;
use Exception;
use setasign\Fpdi\Fpdi;

class PdfService
{
    public function addHashToPDF($pdfContent, $hash)
    {
        $tempPdf = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempPdf, $pdfContent);

        // Cria uma instância do FPDI
        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false); // Impede que o texto quebre automaticamente
        $pageCount = $pdf->setSourceFile($tempPdf);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);

            // Adiciona a página original
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            // Gera um hash baseado no conteúdo da página
            //$hash = hash('sha256', $pdfPath . $i . time());

            // 🔹 Configura a fonte e cor
            $pdf->SetFont('Arial', '', '8');

            $pdf->SetTextColor(73,80,87);
            //$pdf->SetTextColor(255, 0, 0);

            // 🔹 Define a posição Y no rodapé
            $posY = $size['height'] - 4;
            $pdf->SetY($posY); // 🔹 Apenas o SetY() já alinha corretamente

            // 🔹 Cell com largura da página, alinhamento centralizado
            $pdf->Cell($size['width'], 5, "GeoCertifica - Documento assinado eletronicamente: $hash", 0, 0, 'C');
        }

        $newTempPdf = tempnam(sys_get_temp_dir(), 'pdf');
        $pdf->Output($newTempPdf, 'F');

        $pdfFile = file_get_contents($newTempPdf);

        unlink($tempPdf);
        unlink($newTempPdf);

        return $pdfFile;
    }


    public function adicionarPagina($pdfContent, $pdfContentAdicional)
    {
        $tempPdf = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempPdf, $pdfContent);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($tempPdf);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);
        }

        $tempPdf = tempnam(sys_get_temp_dir(), 'htmlpdf');
        file_put_contents($tempPdf, $pdfContentAdicional);

        $pageCount = $pdf->setSourceFile($tempPdf);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);
        }

        $newTempPdf = tempnam(sys_get_temp_dir(), 'pdf');
        $pdf->Output($newTempPdf, 'F');

        return file_get_contents($newTempPdf);
    }

    public function gerarPdfComHtmlDomPdf($htmlContent)
    {
        $dompdf = DomPDF::loadHTML($htmlContent);
        $dompdf->setPaper('A4'); // Set paper size to A4 and orientation to portrait
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->render();
        return $dompdf->output();
    }

    public function gerarPdfComHtml($htmlContent)
    {
        $snappdf = new Snappdf();
        return $snappdf->setHtml($htmlContent)->generate();
    }

    public function convertPdfToVersion14($contentPdfFile): string
    {
        // Create temporary files for input and output
        $inputPdfPath = tempnam(sys_get_temp_dir(), 'inputPdf');
        $outputPdfPath = tempnam(sys_get_temp_dir(), 'outputPdf');

        // Write the content to the input temporary file
        file_put_contents($inputPdfPath, $contentPdfFile);

        // Ghostscript command to convert PDF to version 1.4
        $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . escapeshellarg($outputPdfPath) . " " . escapeshellarg($inputPdfPath);

        // Execute the command
        exec($command, $output, $returnVar);

        // Check if the command was successful
        if ($returnVar !== 0) {
            throw new Exception("Error converting PDF to version 1.4: " . implode("\n", $output));
        }

        // Get the content of the output temporary file
        $convertedPdfContent = file_get_contents($outputPdfPath);

        // Clean up temporary files
        unlink($inputPdfPath);
        unlink($outputPdfPath);

        // Return the content of the converted PDF
        return $convertedPdfContent;
    }
}
