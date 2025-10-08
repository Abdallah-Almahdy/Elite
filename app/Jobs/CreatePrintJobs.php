<?php

namespace App\Jobs;

// use Log;
// use App\Models\PrintJob;
use App\Models\Order;
use App\Models\PrintJob;
// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
// use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log as FacadesLog;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
// use PDF; // if you use barryvdh/laravel-dompdf

class CreatePrintJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderProducts = $this->order->orderProducts()->with('product.subSection.kitchens.printers')->get();

        FacadesLog::info('hellow world ', $orderProducts->toArray());


        // foreach ($orderProducts as $orderProduct) {
        //     $product = $orderProduct->product;
        //     $subSection = $product->subSection;
        //     $kitchen = $subSection->kitchen;
        //     $printers = $kitchen->printers;
        // }
        // Group products by printer
        $grouped = [];

        foreach ($orderProducts as $orderProduct) {
            $product = $orderProduct->product;
            if (!$product || !$product->subSection || $product->subSection->kitchens->isEmpty()) {
                continue;
            }


            foreach ($product->subSection->kitchens as $kitchen) {
                foreach ($kitchen->printers as $printer) {
                    $grouped[$printer->id][] = $orderProduct;
                }
            }
        }

        // FacadesLog::info('Grouped printers: ', $grouped);

        foreach ($grouped as $printerId => $printerProducts) {
            // Generate PDF
            $html = view('pdf.kitchen_ticket', [
                'order' => $this->order,
                'products' => $printerProducts,
            ])->render();

            //   / Setup mPDF with Arabic-friendly font
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'fontDir' => array_merge($fontDirs, [
                    resource_path('fonts/'),
                ]),
                'fontdata' => $fontData + [
                    'amiri' => [
                        'R' => 'Cairo-Regular.ttf',
                        'B' => 'Cairo-Bold.ttf',
                    ],
                ],
                'default_font' => 'Cairo',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            $mpdf->WriteHTML($html);

            $pdfName = 'order_' . $this->order->id . '_printer_' . $printerId . '.pdf';
            $pdfPath = storage_path('app/public/prints/' . $pdfName);

            $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);

            // Save print job
            PrintJob::create([
                'order_id' => $this->order->id,
                'printer_id' => $printerId,
                'pdf_url' => '/storage/' . $pdfPath,
                'status' => 'pending',
            ]);
        }
    }
}
