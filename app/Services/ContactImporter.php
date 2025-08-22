<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ContactImporter
{
    public function import(string $path, string $extension): array
    {
        $extension = strtolower($extension);

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
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);
        return iterator_to_array($csv->getRecords());
    }

    private function readExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
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
            'duplicate_items' => [],
            'invalid_items'   => [],
        ];

        $batchSize = 1000;
        $buffer = [];
        $existingEmails = Contact::pluck('email')->map(fn($e) => strtolower($e))->toArray();
        $existingEmails = array_flip($existingEmails);
        $seenEmails = [];

        foreach ($records as $row) {
            $summary['total']++;

            $data = [
                'name' => $row['name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'birthdate' => $row['birthdate'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'nullable|string|max:20',
                'birthdate' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                $summary['invalid']++;
                $summary['invalid_items'][] = array_merge($row, [
                    'errors' => implode('; ', $validator->errors()->all())
                ]);
                continue;
            }

            $emailLower = strtolower($data['email']);

            if (isset($existingEmails[$emailLower])) {
                $summary['duplicates']++;
                $summary['duplicate_items'][] = array_merge($row, [
                    'errors' => "The email already exists"
                ]);
                continue;
            }
            if (isset($seenEmails[$emailLower])) {
                $summary['duplicates']++;
                $summary['duplicate_items'][] = array_merge($row, [
                    'errors' => "The email already exists"
                ]);
                continue;
            }
            $seenEmails[$emailLower] = true;
            $buffer[] = $data;

            if (count($buffer) >= $batchSize) {
                Contact::insert($buffer);
                $summary['imported'] += count($buffer);
                $buffer = [];
            }
        }

        if (!empty($buffer)) {
            Contact::insert($buffer);
            $summary['imported'] += count($buffer);
        }

        return $summary;
    }
}
