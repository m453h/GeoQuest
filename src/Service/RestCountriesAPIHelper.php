<?php

namespace App\Service;

use App\Entity\Configuration\ContentType;
use App\Entity\Data\CountryFact;
use App\Entity\Location\Country;
use App\Entity\Location\Region;
use App\Entity\Location\SubRegion;
use App\Repository\Configuration\ContentTypeRepository;
use App\Repository\Configuration\FactTypeRepository;
use App\Repository\Data\CountryFactRepository;
use App\Repository\Location\CountryRepository;
use App\Repository\Location\RegionRepository;
use App\Repository\Location\SubRegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private FactTypeRepository $factTypeRepository;
    private CountryFactRepository $countryFactRepository;
    private ContentTypeRepository $contentTypeRepository;

    public function __construct(HttpClientInterface $client,
                                $resCountriesAPIURL,
                                RegionRepository $regionRepository,
                                SubRegionRepository $subRegionRepository,
                                CountryRepository $countryRepository,
                                FactTypeRepository $factTypeRepository,
                                CountryFactRepository $countryFactRepository,
                                ContentTypeRepository $contentTypeRepository
    ){
        $this->resCountriesAPIURL = $resCountriesAPIURL;
        $this->client = $client;
        $this->regionRepository = $regionRepository;
        $this->subRegionRepository = $subRegionRepository;
        $this->countryRepository = $countryRepository;
        $this->factTypeRepository = $factTypeRepository;
        $this->countryFactRepository = $countryFactRepository;
        $this->contentTypeRepository = $contentTypeRepository;
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

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function loadAllCountryFacts($mode): int
    {
       $countries  = $this->countryRepository->getOrderedCountryList();
       $numberOfRecords = 0;
       $subRegionCollection = $this->subRegionRepository->getAllObjectArrayCollection();
       $factTypeCollection = $this->factTypeRepository->getAllObjectArrayCollection();
       $contentTypeCollection = $this->contentTypeRepository->getAllObjectArrayCollection();

       foreach ($countries as $country) {
           $response = $this->client->request('GET', sprintf('%s/name/%s',
               $this->resCountriesAPIURL,
               $country->getName()));

           if ($response->getStatusCode() == 200) {
               $results = $response->toArray();
               $data = $results[0] ?? null;

               if ($data != null) {
                   $subRegion = $data['subregion'] ?? null;
                   if ($subRegion != null) {
                       $country->setSubRegion($subRegionCollection->get($subRegion));
                       $country->setIsFetched(true);
                       $this->countryRepository->add($country, true);
                   }
               }

               if($mode == 'default' || $mode == 'overwrite'){
                  //TODO:: Implement method to delete country fact not created by user
               }

               $capital = $data['capital'][0] ?? null;

               if ($capital != null){
                   $this->createCountryFact($country, $factTypeCollection, 'capital', $capital,
                       $contentTypeCollection->get('Text'));
               }

               if (isset($data['currencies'])) {
                   $currencies = [];
                   foreach ($data['currencies'] as $currency) {
                       $currencies[] = $currency['name'];
                   }
                   $this->createCountryFact($country, $factTypeCollection, 'currencies',json_encode($currencies),
                       $contentTypeCollection->get('Text (JSON)'));
               }

               $tld = $data['tld'][0] ?? null;
               $this->createCountryFact($country, $factTypeCollection, 'tld', $tld,
                   $contentTypeCollection->get('Text'));

               if(!empty($data['flags'])) {
                   $flag = $data['flags']['svg'] ?? $data['flags']['png'];
                   if ($flag != null) {
                       $this->createCountryFact($country, $factTypeCollection, 'flags', $flag,
                           $contentTypeCollection->get('Image (URL)'));
                   }
               }

               if(!empty($data['coatOfArms'])) {
                   $coatOfArms = $data['coatOfArms']['svg'] ?? $data['coatOfArms']['png'];
               } else {
                   $coatOfArms = null;
               }

               if ($coatOfArms != null) {
                   $this->createCountryFact($country, $factTypeCollection, 'coatOfArms', $coatOfArms,
                       $contentTypeCollection->get('Image (URL)'));
               }

               if(isset($data['languages'])) {
                   $languages = [];
                   foreach ($data['languages'] as $language) {
                       $languages[] = $language;
                   }
                   $this->createCountryFact($country, $factTypeCollection, 'languages', json_encode($languages),
                       $contentTypeCollection->get('Text (JSON)'));
               }

               $country->setIsFetched(true);
               $this->countryRepository->add($country, true);
               $numberOfRecords++;
           }

       }

       return $numberOfRecords;
    }

    public function createCountryFact(Country $country,
                                      ArrayCollection $factTypeCollection,
                                      $type,
                                      $content,
                                      ContentType $contentType
    ): bool
    {
        $countryFact = new CountryFact();
        $countryFact->setCountry($country);
        $countryFact->setFactType($factTypeCollection->get($type));
        $countryFact->setContent($content);
        $countryFact->setIsUserCreated(false);
        $countryFact->setContentType($contentType);
        $this->countryFactRepository->add($countryFact, true);
        return true;
    }

}
