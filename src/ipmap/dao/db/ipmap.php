<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace bravedave\ipmap;

$dbc = \sys::dbCheck('ipmap');

// note id, autoincrement primary key is added to all tables - no need to specify

$dbc->defineField('created', 'datetime');
$dbc->defineField('updated', 'datetime');

$dbc->defineField('hostname', 'varchar');
$dbc->defineField('ip', 'varchar');
$dbc->defineField('description', 'varchar', 100);

$dbc->check();  // actually do the work, check that table and fields exis
