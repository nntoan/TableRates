<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

// Middleware
$app->add($app->getContainer()->get('csrf'));
