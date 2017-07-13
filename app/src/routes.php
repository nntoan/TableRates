<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

// Route configuration
$app->get('/', 'TableRates\Generator\Controller\Index')->setName('index');
$app->post('/generate', 'TableRates\Generator\Controller\Post')->setName('generate');
