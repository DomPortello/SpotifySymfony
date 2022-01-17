<?php

namespace App\Command;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Track;
use App\Repository\ArtistRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class GetAlbumsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private GenreRepository $genreRepository;
    private ArtistRepository $artistRepository;
    private HttpClientInterface $httpClient;

    public function __construct(EntityManagerInterface $entityManager, GenreRepository $genreRepository, ArtistRepository $artistRepository, HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->genreRepository = $genreRepository;
        $this->artistRepository = $artistRepository;
        $this->httpClient = $httpClient;
    }


    protected function configure(): void
    {
        $this->setName('app:getAlbums')
            ->setDescription('Get albums');
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $urlAlbum = 'https://api.deezer.com/album/';
        $urlArtist = 'https://api.deezer.com/artist/';
        $urlTrack = 'https://api.deezer.com/track/';
        $urlGenre = 'https://api.deezer.com/genre/';

        $albumIds = [
            1434890, 302127, 103248, 551834, 86712972,
            182284602, 6927139, 725250, 12979500, 9950568,
            77286, 8568047, 5538751, 8673057, 73715,
            5985462, 12493382, 43888, 39258441, 77960742
        ];

        foreach ($albumIds as $albumId) {
            try {
                $response = $this->httpClient->request('GET', $urlAlbum . $albumId)->toArray();
                dump($response['id']);

                $album = new Album();
                $album->setTitle($response['title'])
                    ->setSmallCover($response['cover_small'])
                    ->setMediumCover($response['cover_medium'])
                    ->setBigCover($response['cover_big'])
                    ->setXlCover($response['cover_xl'])
                    ->setLabel($response['label'])
                    ->setNbTracks($response['nb_tracks'])
                    ->setReleaseAt(new \DateTime($response['release_date']))
                    ->setAvailable($response['available'])
                    ->setPrice(15);

                foreach ($response['genres']['data'] as $genre) {
                    $genreEntity = $this->genreRepository->findOneBy(['name' => $genre['name']]);
                    if ($genreEntity === null) {
                        $genreEntity = new Genre();
                        $responseGenre =
                            $this->httpClient->request('GET', $urlGenre . $genre['id'])->toArray();
                        $genreEntity->setName($responseGenre['name'])
                            ->setSmallPicture($responseGenre['picture_small'])
                            ->setMediumPicture($responseGenre['picture_medium'])
                            ->setBigPicture($responseGenre['picture_big'])
                            ->setXlPicture($responseGenre['picture_xl']);
                        $this->entityManager->persist($genreEntity);
                    }

                    $album->addGenre($genreEntity);
                }

                $artistEntity = $this->artistRepository->findOneBy(['name' => $response['artist']['name']]);
                if ($artistEntity === null) {
                    $artistEntity = new Artist();
                    $responseArtist =
                        $this->httpClient->request('GET', $urlArtist . $response['artist']['id'])->toArray();
                    $artistEntity->setName($responseArtist['name'])
                        ->setSmallPicture($responseArtist['picture_small'])
                        ->setMediumPicture($responseArtist['picture_medium'])
                        ->setBigPicture($responseArtist['picture_big'])
                        ->setXlPicture($responseArtist['picture_xl'])
                        ->setNbAlbum($responseArtist['nb_album']);
                    $this->entityManager->persist($artistEntity);
                }
                $album->setArtist($artistEntity);

                foreach ($response['tracks']['data'] as $track) {
                    $responseTrack = $this->httpClient->request('GET', $urlTrack . $track['id'])->toArray();
                    $trackEntity = new Track();
                    $trackEntity->setTitle($responseTrack['title'])
                        ->setDuration($responseTrack['duration'])
                        ->setPosition($responseTrack['track_position'])
                        ->setRank($responseTrack['rank'])
                        ->setReleaseAt(new \DateTime($responseTrack['release_date']))
                        ->setPreview($responseTrack['preview'])
                        ->setAlbum($album)
                        ->setArtist($artistEntity)
                        ->setPrice(0.99);

                    $this->entityManager->persist($trackEntity);
                    $album->addTrack($trackEntity);
                }
                $this->entityManager->persist($album);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }
        $this->entityManager->flush();
        return 0;
    }
}
