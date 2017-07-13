<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TableRates\Generator\Controller;

/**
 * Class Index
 *
 * @package TableRates\Generator\Controller
 * @author  Toan Nguyen <destro.nnt@gmail.com>
 */
final class Index extends AbstractController
{
    const COUNTRY_DATA_FILE = BP . '/data/countries.json';
    const ISO_MAPPER_FILE = BP . '/data/iso_mapper.json';

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (!file_exists(self::COUNTRY_DATA_FILE) && !file_exists(self::ISO_MAPPER_FILE)) {
            return $this->container->get('view')->render($this->response, 'no_data.twig');
        }

        $baseDir = empty($_COOKIE['BASE_DIR']) ? false : $_COOKIE['BASE_DIR'];

        $countries = $this->resolveCountryData(['iso2' => self::COUNTRY_DATA_FILE, 'mapper' => self::ISO_MAPPER_FILE]);

        return $this->container->get('view')->render($this->response, 'index.twig', [
            'countries' => $countries,
            'baseDir' => $baseDir,
        ]);
    }

    /**
     * Resolve country data for the god sake
     *
     * @param array $paths
     *
     * @return array
     */
    protected function resolveCountryData(array $paths)
    {
        $countries = [];
        if (!empty($paths) && is_array($paths)) {
            $iso2Codes = json_decode(file_get_contents($paths['iso2']), true);
            $mapper = json_decode(file_get_contents($paths['mapper']), true);
            foreach ($iso2Codes as $code => $countryName) {
                $countries[] = [
                    'iso2' => $code,
                    'iso3' => $mapper[$code],
                    'name' => $countryName
                ];
            }
        }

        return $countries;
    }
}
