<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.first,page.edit.first,page.first
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('share', 'plug');

cot_share_get('page', $id);

die(print_r($usr, true));

?>