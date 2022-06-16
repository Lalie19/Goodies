<?php 

namespace App\Tool;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadTool
{
    private $slugger;

    private $uploadFolder = "image/upload/";
    
    // private $imageDirectory;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        // $this->imageDirectory = "image/upload/";
    }

    public function upload(UploadedFile $newFile, ?string $oldFile = ""): string 
    {
        $originalFileName = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFileName);
        $fullFilename = $safeFilename . uniqid() . '.' .  $newFile->guessExtension();
        $newFile->move($this->uploadFolder, $fullFilename);
        $this->delete($oldFile);
        return $fullFilename;
    }

    public function delete(?string $oldFileName = ""): void 
    {
        if ($oldFileName) {
            unlink($this->uploadFolder . $oldFileName);
        }
        
    }
}