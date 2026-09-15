<?php

declare(strict_types=1);

namespace App\Product;

use Psr\Http\Message\UploadedFileInterface;

final readonly class ProductImageUploader
{
    public function __construct(
        private string $uploadPath = '@public/uploads/products',
    ) {
    }

    /**
     * @param UploadedFileInterface[] $files
     * @return string[]
     */
    public function upload(array $files): array
    {
        $directory = dirname(__DIR__, 2) . '/public/uploads/products';

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $paths = [];

        foreach ($files as $file) {
            if ($file->getError() !== UPLOAD_ERR_OK) {
                continue;
            }

            $extension = pathinfo((string)$file->getClientFilename(), PATHINFO_EXTENSION);
            $filename = uniqid('product_', true) . ($extension ? '.' . $extension : '');

            $file->moveTo($directory . '/' . $filename);

            $paths[] = '/uploads/products/' . $filename;
        }

        return $paths;
    }
}
