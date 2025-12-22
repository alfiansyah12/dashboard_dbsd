<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['login'] = 'auth';

$route['admin']  = 'admin/index';

/** PEGAWAI **/
$route['pegawai'] = 'pegawai/index';
$route['pegawai/pilih_tugas'] = 'pegawai/pilih_tugas';
$route['pegawai/ambil_tugas'] = 'pegawai/ambil_tugas';

$route['pegawai/dashboard'] = 'pegawai/dashboard_list';      // LIST semua input
$route['pegawai/input/(:num)'] = 'pegawai/dashboard/$1';     // FORM input (pakai alias input)
$route['pegawai/dashboard/(:num)'] = 'pegawai/dashboard/$1'; // optional: biar tetap bisa akses lewat /dashboard/{id}
$route['pegawai/dashboard_store'] = 'pegawai/dashboard_store';

/** ATASAN **/
$route['atasan'] = 'atasan/index';
$route['atasan/review_store'] = 'atasan/review_store';
$route['atasan/target_store'] = 'atasan/target_store';
$route['atasan/terminate/(:num)'] = 'atasan/terminate/$1';

// $route['atasan/chart'] = 'atasan/chart'; // hapus kalau chart sudah 1 halaman di output

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

