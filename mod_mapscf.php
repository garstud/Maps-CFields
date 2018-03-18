<?php
defined('_JEXEC') or die('Forbidden access');

require_once dirname(__FILE__).'/helper.php';

$list = modMapsCFHelper::getList($params);
$modclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_mapscf', $params->get('layout', 'gmaps'));
