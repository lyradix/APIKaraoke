<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Song;
use App\Entity\Room;
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

    #[Route('/importCSV', name: 'app_import_csv', methods: ['POST'])]
    public function importCSV(Request $request, 
    EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (strtolower($file->getClientOriginalExtension()) !== 'csv') {
            return new JsonResponse(['error' => 'Invalid file type. Only CSV files are allowed.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
             // move file to folder APIKaraoke\src\dataFixtures\data\songs 
            $file->move($this->getParameter('kernel.project_dir') . '/src/dataFixtures/data/songs', $file->getClientOriginalName());
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'Failed to upload file: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }   
        // Process the CSV file and import songs into the database
        $filePath = $this->getParameter('kernel.project_dir') . '/src/dataFixtures/data/songs/' . $file->getClientOriginalName();
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $song = new Song();
                $song->setTitle($data[0]);
                $entityManager->persist($song); 
            }
            fclose($handle);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Songs imported successfully'], JsonResponse::HTTP_OK);
        } else {
            return new JsonResponse(['error' => 'Failed to open the uploaded file'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

#[Route('/songs', name: 'app_songs', methods: ['GET'])]
    public function getSongs(EntityManagerInterface $entityManager): JsonResponse
    {
        $songs = $entityManager->getRepository(Song::class)->findAll();
        $data = [];

        foreach ($songs as $song) {
            $data[] = [
                'id' => $song->getId(),
                'title' => $song->getTitle(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/postRoom', name: 'app_post_room', methods: ['POST'])]
    public function postRoom(
    Request $request, 
    EntityManagerInterface $entityManager
): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    if (!isset($data['name']) || !isset($data['date']) || !isset($data['place'])) {
        return new JsonResponse(['error' => 'Name, date, and place are required'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $room = new Room();
    $room->setName($data['name']);
    $room->setPlace($data['place']);
    $room->setDate(new \DateTime($data['date']));
    $entityManager->persist($room);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Room created successfully', 'id' => $room->getId()], JsonResponse::HTTP_CREATED);
}

    #[Route('/rooms', name: 'app_rooms', methods: ['GET'])]
    public function getRooms(EntityManagerInterface $entityManager): JsonResponse
    {
        $rooms = $entityManager->getRepository(Room::class)->findAll();
        $data = [];

        foreach ($rooms as $room) {
            $data[] = [
                'id' => $room->getId(),
                'name' => $room->getName(),
                'place' => $room->getPlace(),
                'date' => $room->getDate()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }   
    


}