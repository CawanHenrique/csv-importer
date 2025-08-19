<?php

use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader; 

class ContactImporter
{
    public function import(string $path): array
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (in_array($extension, ['csv', 'txt'])) {
            $records = $this->readCsv($path);
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            $records = $this->readExcel($path);
        } else {
            throw new \Exception("Formato de arquivo não suportado: $extension");
        }

        return $this->processRecords($records);
    }

    private function readCsv(string $path): array
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); 
        return iterator_to_array($csv->getRecords());
    }

    private function readExcel(string $path): array
    {
        $spreadsheet = PHPExcel_IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $header = array_map('strtolower', $rows[1]);
        unset($rows[1]);

        $records = [];
        foreach ($rows as $row) {
            $records[] = array_combine($header, $row);
        }

        return $records;
    }

    private function processRecords(array $records): array
    {
        $summary = [
            'total' => 0,
            'imported' => 0,
            'duplicates' => 0,
            'invalid' => 0,
        ];

        foreach ($records as $row) {
            $summary['total']++;

            $data = [
                'name' => $row['name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'birthdate' => $row['birthdate'] ?? null,
            ];

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:contacts,email',
                'phone' => 'nullable|string|max:20',
                'birthdate' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                $summary['invalid']++;
                continue;
            }

            try {
                Contact::create($data);
                $summary['imported']++;
            } catch (\Illuminate\Database\QueryException $e) {
                $summary['duplicates']++;
            }
        }

        return $summary;
    }
}
