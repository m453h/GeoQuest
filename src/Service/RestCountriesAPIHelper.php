<?php

namespace App\Service;

use App\Entity\Location\Country;
use App\Entity\Location\Region;
use App\Entity\Location\SubRegion;
use App\Repository\Location\CountryRepository;
use App\Repository\Location\RegionRepository;
use App\Repository\Location\SubRegionRepository;
use Doctrine\DBAL\Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestCountriesAPIHelper
{
    private String $resCountriesAPIURL;
    private HttpClientInterface $client;
    private RegionRepository $regionRepository;
    private SubRegionRepository $subRegionRepository;
    private CountryRepository $countryRepository;

    public function __construct(HttpClientInterface $client,
                                $resCountriesAPIURL,
                                RegionRepository $regionRepository,
                                SubRegionRepository $subRegionRepository,
                                CountryRepository $countryRepository
    ){
        $this->resCountriesAPIURL = $resCountriesAPIURL;
        $this->client = $client;
        $this->regionRepository = $regionRepository;
        $this->subRegionRepository = $subRegionRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface|Exception
     */
    public function loadAllRegions($mode): int
    {
        $response = $this->client->request('GET', sprintf('%s/all', $this->resCountriesAPIURL));
        $numberOfRecords = 0;
        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $regions = [];

            if($mode == 'default' || $mode == 'overwrite'){
                $this->regionRepository->removeAllRegions();
            }

            foreach ($results as $result){
                $regionName = $result['region'] ?? null;
                if (!in_array($regionName, $regions) && $regionName!= null) {
                    $regions[] = $regionName;
                    $region = new Region();
                    $region->setName($regionName);
                    $this->regionRepository->add($region, true);
                    $numberOfRecords++;
                }
            }
            return $numberOfRecords;
        }
        return $numberOfRecords;
    }


    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface|Exception
     */
    public function loadAllSubRegions($mode): int
    {
        $response = $this->client->request('GET', sprintf('%s/all', $this->resCountriesAPIURL));
        $numberOfRecords = 0;

        $regionIds = $this->regionRepository->getAllRegionIds();

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $subRegions = [];

            if($mode == 'default' || $mode == 'overwrite'){
                $this->subRegionRepository->removeAll();
            }
            foreach ($results as $result){
                $subRegionName = $result['subregion'] ?? null;
                $regionName = $result['region'] ?? null;
                if (!in_array($subRegionName, $subRegions) && $subRegionName!= null) {
                    $subRegion = new SubRegion();
                    $subRegion->setName($subRegionName);
                    $subRegion->setRegion($regionIds->get($regionName));
                    $this->subRegionRepository->add($subRegion, true);
                    $subRegions[] = $subRegionName;
                    $numberOfRecords++;
                }
            }

            return $numberOfRecords;
        }
        return $numberOfRecords;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface|Exception
     */
    public function loadAllCountries($mode): int
    {
        $response = $this->client->request('GET', sprintf('%s/all', $this->resCountriesAPIURL));
        $numberOfRecords = 0;

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $countries = [];

            if($mode == 'default' || $mode == 'overwrite'){
                $this->countryRepository->removeAll();
            }

            foreach ($results as $result){
                $countryName = $result['name']['common'] ?? null;
                if (!in_array($countryName, $countries) && $countryName!= null) {
                    $country = new Country();
                    $country->setName($countryName);
                    $this->countryRepository->add($country, true);
                    $numberOfRecords++;
                }
            }
            return $numberOfRecords;
        }
        return $numberOfRecords;
    }
}
