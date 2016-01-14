<?php
/**
 * ownCloud - logextension
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julius Chen <julius.j@inwinstack.com>
 * @copyright Julius Chen 2016
 */

namespace OCA\LogExtension\AppInfo;

$app = new Application();  
$app->getContainer()->query('UserHooks')->register(); 
