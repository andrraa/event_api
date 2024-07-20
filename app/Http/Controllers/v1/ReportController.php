<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    #[NoReturn] public function export(ReportRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $transactionData = Transaction::query()
                ->whereBetween('date', [$validatedData['start_date'], $validatedData['end_date']])
                ->with(['masterEvent', 'ticket'])
                ->get();

            // Excel Initialize
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Transaction Report');

            // Header & Data Style
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => '000000']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb', 'FFFF00'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb', '000000']
                    ]
                ]
            ];

            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb', '000000']
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
            ];

            $sheet->setCellValue('A1', 'Laporan Transaksi');
            $sheet->setCellValue('A2', 'Tanggal Awal');
            $sheet->setCellValue('A3', 'Tanggal Akhir');

            $sheet->getStyle('A1:A3')->applyFromArray(['font' => ['bold' => true]]);

            $sheet->mergeCells('A1:B1');
            $sheet->mergeCells('A2:B2');
            $sheet->mergeCells('A3:B3');

            $sheet->setCellValue('C2', ': ' . $validatedData['start_date']);
            $sheet->setCellValue('C3', ': ' . $validatedData['end_date']);

            // Write Header & Data
            $sheet->setCellValue('A5', 'No.');
            $sheet->setCellValue('B5', 'Tanggal Transaksi');
            $sheet->setCellValue('C5', 'Nama Event');
            $sheet->setCellValue('D5', 'Tipe Tiket');
            $sheet->setCellValue('E5', 'Harga Tiket');
            $sheet->setCellValue('F5', 'Nama Buyer');
            $sheet->setCellValue('G5', 'Email Buyer');
            $sheet->setCellValue('H5', 'Nomor Telepon');
            $sheet->setCellValue('I5', 'Jumlah Tiket');
            $sheet->setCellValue('J5', 'Total Harga');

            $sheet->getStyle('A5:J5')->applyFromArray($headerStyle);

            $startRow = 6;

            foreach ($transactionData as $item => $transaction) {
                $sheet->setCellValue('A' . $startRow, $item + 1);
                $sheet->setCellValue('B' . $startRow, $transaction->date);
                $sheet->setCellValue('C' . $startRow, optional($transaction->masterEvent)->name);
                $sheet->setCellValue('D' . $startRow, optional($transaction->ticket)->name);
                $sheet->setCellValue('E' . $startRow, optional($transaction->ticket)->price);
                $sheet->setCellValue('F' . $startRow, $transaction->name);
                $sheet->setCellValue('G' . $startRow, $transaction->email);
                $sheet->setCellValue('H' . $startRow, $transaction->phone);
                $sheet->setCellValue('I' . $startRow, $transaction->quantity);
                $sheet->setCellValue('J' . $startRow, $transaction->total_price);

                $sheet->getStyle('E' . $startRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J' . $startRow)->getNumberFormat()->setFormatCode('#,##0');
                $startRow++;
            }

            // Total Transaction
            $totalRow = $startRow;

            $sheet->mergeCells('A' . $totalRow . ':H' . $totalRow);

            $sheet->setCellValue('A' . $totalRow, 'Jumlah');
            $sheet->setCellValue('I' . $totalRow, '=SUM(I6:I' . $totalRow - 1 . ')');
            $sheet->setCellValue('J' . $totalRow, '=SUM(J6:J' . $totalRow - 1 . ')');

            $sheet->getStyle('A' . $totalRow . ':J' . $totalRow)
                ->applyFromArray(['font' => ['bold' => true]]);
            $sheet->getStyle('I' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('J' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');

            // Style All Data
            $sheet->getStyle('A6:J' . $totalRow)->applyFromArray($dataStyle);

            // Autofit Column
            foreach (range('A', 'J') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Output
            $writer = new Xlsx($spreadsheet);

            $fileName = 'TransactionReport-' . $validatedData['start_date'] . '-' . $validatedData['end_date'] . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
