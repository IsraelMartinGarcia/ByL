<?php

namespace App\Factory;

use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Movie;
use App\Repository\ActorRepository;
use App\Repository\DirectorRepository;
use App\Repository\MovieRepository;
use DateTime;
use Exception;

class MovieFactory
{
    private MovieRepository $movieRepository;
    private DirectorRepository $directorRepository;
    private ActorRepository $actorRepository;

    public function __construct(
        MovieRepository $movieRepository,
        DirectorRepository $directorRepository,
        ActorRepository $actorRepository
    ) {
        $this->movieRepository = $movieRepository;
        $this->directorRepository = $directorRepository;
        $this->actorRepository = $actorRepository;
    }

    /**
     * @throws Exception
     */
    public function create(array $filteredValues): void {
        $movie = new Movie();
        $movie->setTitle($filteredValues['title']);
        $movie->setDuration($filteredValues['duration']);
        $movie->setGenre($filteredValues['genre']);
        $movie->setProducer($filteredValues['production_company']);
        $movie->setPublished(new DateTime($filteredValues['date_published']));

        foreach(explode(',', $filteredValues['director']) as $directorName) {
            $director = $this->createDirector(trim($directorName));
            $movie->addDirector($director);
            $this->directorRepository->add($director);
        }

        foreach(explode(',', $filteredValues['actors']) as $actorName) {
            $actor = $this->createActor(trim($actorName));
            $movie->addActor($actor);
            $this->actorRepository->add($actor);
        }

        $this->movieRepository->add($movie, true);
    }

    private function createDirector(string $directorName): Director {
        $director = $this->directorRepository->findOneBy(['name' => $directorName]);
        if ($director === null) {
            $director = new Director();
            $director->setName(trim($directorName));
        }

        return $director;
    }

    private function createActor(string $actorName): Actor {
        $actor = $this->actorRepository->findOneBy(['name' => $actorName]);
        if ($actor === null) {
            $actor = new Actor();
            $actor->setName(trim($actorName));

        }
        return $actor;
    }
}