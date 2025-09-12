<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected $dompdf;

    public function __construct(array $options = [])
    {
        $cfg = new Options();
        // configurar opções padrão
        $cfg->set('isRemoteEnabled', true);
        $cfg->set('isHtml5ParserEnabled', true);

        foreach ($options as $k => $v) {
            try { $cfg->set($k, $v); } catch (\Exception $e) { /* ignore unknown */ }
        }

        $this->dompdf = new Dompdf($cfg);
    }

    /**
     * Renderiza HTML e retorna o objeto Dompdf
     * @param string $html
     * @param string $paper 'A4', 'letter'
     * @param string $orientation 'portrait'|'landscape'
     * @return Dompdf
     */
    public function renderHtml(string $html, string $paper = 'A4', string $orientation = 'portrait')
    {
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        return $this->dompdf;
    }

    /**
     * Stream the PDF to browser
     * @param Dompdf $dompdf
     * @param string $filename
     * @param bool $inline
     */
    public function stream($dompdf, string $filename = 'document.pdf', bool $inline = true)
    {
        $dompdf->stream($filename, ["Attachment" => $inline ? 0 : 1]);
        exit;
    }
}
