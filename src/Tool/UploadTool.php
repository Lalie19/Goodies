<?php 

namespace App\Tool;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadTool
{
    private $slugger;
    
    private $imageDirectory;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->imageDirectory = "image/upload/";
    }

    public function upload(UploadedFile $newFile, ?string $oldPath = ""): string 
    {
        $originalFileName = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFileName);
        $fullFilename = "$safeFilename" . uniqid() . '.' .  $newFile->guessExtension();
        $newFile->move($this->imageDirectory, $fullFilename);
        $this->delete($oldPath);
        return $fullFilename;
    }

    public function delete(?string $oldPath): void 
    {
        if ($oldPath) {
            unlink($this->imageDirectory . $oldPath);
        }
        
    }
}