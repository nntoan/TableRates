<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TableRates\Generator\Controller;

use Slim\Http\Request;

/**
 * Class Post
 *
 * @package TableRates\Generator\Controller
 * @author  Toan Nguyen <destro.nnt@gmail.com>
 */
final class Post extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var Request $request */
        $request = $this->container->get('request');
        if ($request->isXhr() || $request->isPost()) {
            header("Content-Type: text/plain");
            header("Content-type: application/force-download");
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"tablerates.csv\"");
            switch ($request->getParam('carriers_tablerate_condition_name')) {
                case 'weight':
                    $condition = 'Weight (and price)';
                    break;
                case 'qty':
                    $condition = '# of Items (and above)';
                    break;
                case 'price':
                default:
                    $condition = 'Order Subtotal (and above)';
                    break;
            }

            echo "Country,Region/State,Zip/Postal Code," . $condition . ",Shipping Price\n";
            foreach ($request->getParsedBody() as $key => $value) {
                if (stripos($key, 'country_') !== false) {
                    list($dummy, $country) = explode('_', $key);
                    foreach ($request->getParam('price_' . $country) as $code => $price) {
                        $price = $request->getParam('price_' . $country)[$code];
                        $from = $request->getParam('from_' . $country)[$code];
                        echo $country . ",*,*," . $from . "," . $price . "\n";
                    }
                }
            }
        }

        exit();
    }
}
