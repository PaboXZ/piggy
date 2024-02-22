<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Config\Paths;

class ReceiptService {

    public function __construct(private Database $database){

    }

    public function validateFile(?array $file){
        if(!$file || $file['error'] !== UPLOAD_ERR_OK)
            throw new ValidationException(['receipt' => ['Failed to upload file']]);
        
        $maxFileSizeMB = 4;

        if($file['size'] > $maxFileSizeMB * 1024 ** 2)
            throw new ValidationException(['receipt' => ["Exceeded maximum file size ({$maxFileSizeMB} MB)"]]);

        $originName = $file['name'];
        if(!preg_match('#^[a-zA-z0-9\s.-_]+$#', $originName))
            throw new ValidationException(['receipt' => ["Invalid file name"]]);

        $acceptedTypes = [
            'image/jpeg',
            'image/png',
            'application/pdf'
        ];

        $originType = $file['type'];

        if(!in_array($originType, $acceptedTypes))
            throw new ValidationException(['receipt' => ['Accepted formats: jpg, pdf, png']]);

    }

    public function upload(array $file, int $transactionID){

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $newFileName = random_bytes(16);
        $newFileName = bin2hex($newFileName) .'.'. $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . '/' . $newFileName;

        if(!move_uploaded_file($file['tmp_name'], $uploadPath))
            throw new ValidationException(['receipt' => ['Failded to upload file']]);

        $this->database->query("INSERT INTO receipts (original_filename, storage_filename, media_type, transaction_id) VALUE (
            :originalFilename, :storageFilename, :mediaType, :transactionID)", [
                'originalFilename' => $file['name'],
                'storageFilename' => $newFileName,
                'mediaType' => $file['type'],
                'transactionID' => $transactionID
            ]);
    }

    public function getReceipt(string $receiptID) {
         return $this->database->query("SELECT * FROM receipts WHERE id = :receiptID", ['receiptID' => $receiptID])->find();
    }

    public function read($receipt) {
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

        if(!file_exists($filePath))
            redirectTo('/');

        header("Content-Disposition: inline;filename={$receipt['original_filename']}");
        header("Content-Type: {$receipt['media_type']}");

        readfile($filePath);

    }

    public function delete($receipt){
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

        unlink($filePath);

        $this->database->query("DELETE FROM receipts WHERE id = :id", ['id' => $receipt['id']]);
    }
}