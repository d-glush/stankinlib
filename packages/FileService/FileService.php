<?php

namespace Packages\FileService;

use DateTime;
use Packages\FileRepository\FileDTO\FileDTO;
use Packages\FileRepository\FileRepository;
use Packages\FileService\FileAttachType\FileAttachType;

class FileService
{
    public CONST ACCOUNT_IMAGES_DIR = '/storage/accounts_images';
    public CONST PUBLICATION_FILES_DIR = '/storage/publications_files';

    private FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @return array<int>
     */
    public function addFiles($files, FileAttachType $attachType): array
    {
        $filesIds = [];
        foreach ($files as $file) {
            $fileName = $file['name'];
            $fileLocation = $file['tmp_name'];
            $fileType = $file['type'];
            $fileSize = $file['size'];

            $newFileName = (new DateTime())->format("dmYHis") . '_' . $fileName;
            $relPath = match ($attachType) {
                    FileAttachType::USER => self::ACCOUNT_IMAGES_DIR,
                    FileAttachType::PUBLICATION => self::PUBLICATION_FILES_DIR,
                } . '/' . $newFileName;
            $newFilePath = $_SERVER['DOCUMENT_ROOT'] . $relPath;

            $fileDTO = new FileDTO([
                'name' => $fileName,
                'location' => $newFilePath,
                'type' => $fileType,
                'size' => $fileSize,
            ]);
            $filesIds[] = $this->fileRepository->add($fileDTO);
            move_uploaded_file($fileLocation, $newFilePath);
        }
        return $filesIds;
    }
}