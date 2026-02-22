<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entiy\Song;
use App\Repository\SongRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/IndexController.php',
        ]);
    }

    #[Route('/importCSV', name: 'app_import_csv')]  
    public function importCSV(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($file->getClientOriginalExtension() !== 'csv') {
            return new JsonResponse(['error' => 'Invalid file type. Only CSV files are allowed.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $filePath = $file->getPathname();
            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                throw new FileException('Could not open the file.');
            }

            // Skip the header row
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                $song = new Song();
                $song->setTitle($data[0]);

                $entityManager->persist($song);
            }

            fclose($handle);
            $entityManager->flush();

            return new JsonResponse(['message' => 'CSV imported successfully'], JsonResponse::HTTP_OK);
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'File error: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
}

}