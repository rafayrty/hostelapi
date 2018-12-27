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
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;
/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
$route['api/user/register'] = 'api/users/register';
$route['api/user/login'] = 'api/users/login';

//Shops
$route['api/shops/(:num)/shop_name'] = 'api/shops/shops_name/$1';
$route['api/shops/search'] = 'api/shops/shops_search';
$route['api/shops/downqr'] = 'api/shops/downqr';
$route['api/shops/(:num)/deleteshops']['DELETE'] = 'api/shops/deleteshops/$1';
$route['api/shops/add'] = 'api/shops/insert';
$route['api/shops/(:num)/fetch']='api/shops/fetch_shops/$1';
$route['api/shops/(:num)/update']='api/shops/update/$1';
$route['api/shops/deleteall'] = 'api/shops/multi_delete';
$route['api/shops/(:num)/reports_fetch'] = 'api/shops/shops_reports_fetch/$1';
$route['api/shops/(:num)/deletereports']['DELETE'] = 'api/shops/deletereports/$1';
$route['api/shops/deleteallreports'] = 'api/shops/multi_reportsdelete';
$route['api/shops/(:num)/reports_custom'] = 'api/shops/shops_reports_custom/$1';
$route['api/shops/(:num)/reports_month'] = 'api/shops/shops_reports_month/$1';
$route['api/shops/(:num)/reports_year'] = 'api/shops/shops_reports_year/$1';
$route['api/shops/(:num)/reports_date'] = 'api/shops/shops_reports_date/$1';
$route['api/shops/update_status'] = 'api/shops/update_status';
$route['api/shops/(:num)/check_status'] = 'api/shops/check_status/$1';
$route['api/shops/checkin']='api/shops/checkin';
$route['api/shops/checkout']='api/shops/checkout';
$route['api/shops/qrcode'] = 'api/shops/shops_record';

//Staff
$route['api/staff/(:num)/staff_name'] = 'api/staff/staff_name/$1';
$route['api/staff/all']='api/staff/fetch';
$route['api/staff/search'] = 'api/staff/staff_search';
$route['api/staff/deleteall'] = 'api/staff/multi_delete';
$route['api/staff/(:num)/deletestaff']['DELETE'] = 'api/staff/deletestaff/$1';
$route['api/staff/downqr'] = 'api/staff/downqr';
$route['api/staff/add'] = 'api/staff/insert';
$route['api/staff/(:num)/fetch']='api/staff/fetch_staff/$1';
$route['api/staff/(:num)/update']='api/staff/update/$1';
$route['api/staff/(:num)/reports_fetch'] = 'api/staff/staff_reports_fetch/$1';
$route['api/staff/(:num)/deletereports']['DELETE'] = 'api/staff/deletereports/$1';
$route['api/staff/deleteallreports'] = 'api/staff/multi_reportsdelete';
$route['api/staff/(:num)/reports_custom'] = 'api/staff/staff_reports_custom/$1';
$route['api/staff/(:num)/reports_month'] = 'api/staff/staff_reports_month/$1';
$route['api/staff/(:num)/reports_year'] = 'api/staff/staff_reports_year/$1';
$route['api/staff/(:num)/reports_date'] = 'api/staff/staff_reports_date/$1';
$route['api/staff/qrcode'] = 'api/staff/staff_record';
$route['api/staff/(:num)/check_status'] = 'api/staff/check_status/$1';
$route['api/staff/checkin']='api/staff/checkin';
$route['api/staff/checkout']='api/staff/checkout';
$route['api/staff/update_status'] = 'api/staff/update_status';
//Excel
//Excel Girls
$route['api/excel/girls'] = 'api/Excel/girls';
$route['api/excel/(:any)/(:num)/girls_date'] = 'api/Excel/girls_day/$1/$2';
$route['api/excel/(:any)/(:num)/girls_month'] = 'api/Excel/girls_month/$1/$2';
$route['api/excel/(:any)/(:num)/girls_year'] = 'api/Excel/girls_year/$1/$2';
$route['api/excel/(:any)/(:any)/(:num)/girls_custom'] = 'api/Excel/girls_custom/$1/$2/$3';
$route['api/excel/(:num)/girls_sms'] = 'api/Excel/girls_sms_export/$1';
//Excel Staff
$route['api/excel/staff'] = 'api/Excel/staff';
$route['api/excel/(:any)/(:num)/staff_month'] = 'api/Excel/staff_month/$1/$2';
$route['api/excel/(:any)/(:num)/staff_date'] = 'api/Excel/staff_day/$1/$2';
$route['api/excel/(:any)/(:num)/staff_year'] = 'api/Excel/staff_year/$1/$2';
$route['api/excel/(:any)/(:any)/(:num)/staff_custom'] = 'api/Excel/staff_custom/$1/$2/$3';
//Excel Shops
$route['api/excel/shops'] = 'api/Excel/shops';
$route['api/excel/(:any)/(:num)/shop_month'] = 'api/Excel/shops_month/$1/$2';
$route['api/excel/(:any)/(:num)/shop_date'] = 'api/Excel/shops_day/$1/$2';
$route['api/excel/(:any)/(:num)/shop_year'] = 'api/Excel/shops_year/$1/$2';
$route['api/excel/(:any)/(:any)/(:num)/shops_custom'] = 'api/Excel/shops_custom/$1/$2/$3';
//Girls
$route['api/girls/(:num)/girl_name'] = 'api/girls/girl_name/$1';
$route['api/girls/update_status'] = 'api/girls/update_status';
$route['api/girls/(:num)/check_status'] = 'api/girls/check_status/$1';
$route['api/girls/(:num)/reports_custom'] = 'api/girls/girls_reports_custom/$1';
$route['api/girls/(:num)/reports_month'] = 'api/girls/girls_reports_month/$1';
$route['api/girls/(:num)/reports_year'] = 'api/girls/girls_reports_year/$1';
$route['api/girls/(:num)/reports_date'] = 'api/girls/girls_reports_date/$1';
$route['api/girls/(:num)/reports_fetch'] = 'api/girls/girls_reports_fetch/$1';
$route['api/girls/(:num)/deletereports']['DELETE'] = 'api/girls/deletereports/$1';
$route['api/girls/deleteallreports'] = 'api/girls/multi_reportsdelete';
$route['api/girls/downqr'] = 'api/girls/downqr';
$route['api/girls/checkin']='api/girls/checkin';
$route['api/girls/checkout']='api/girls/checkout';
$route['api/girls/all']='api/girls/fetch';
$route['api/girls/(:num)/fetch']='api/girls/fetch_girl/$1';
$route['api/girls/add']='api/girls/insert';
$route['api/girls/qrcode'] = 'api/girls/girl_record';
$route['api/girls/search'] = 'api/girls/girls_search';
$route['api/girls/deleteall'] = 'api/girls/multi_delete';
$route['api/girls/(:num)/update'] = 'api/girls/update/$1';
$route['api/girls/(:num)/deletegirls']['DELETE'] = 'api/girls/deletegirls/$1';


//Server Sent Events



//Sms
$route['api/sms/sendtogirls']='api/sms/sendtogirls';
$route['api/sms/settings']='api/sms/settings';
$route['api/sms/(:num)/fetch_girls_sms']='api/sms/fetch_girls_sms/$1';
$route['api/sms/(:num)/send_girl_sms']='api/sms/send_message_girl/$1';
$route['api/sms/sendtostaff']='api/sms/sendtostaff';
$route['api/sms/sendtoshops']='api/sms/sendtoshops';



//Users

$route['api/users/(:num)/delete']['DELETE'] = 'api/users/deleteuser/$1';
$route['api/users/(:num)/delete']['DELETE'] = 'api/users/deleteuser/$1';
$route['api/users/(:num)/fetch'] = 'api/users/fetch_user/$1';
$route['api/users/all'] = 'api/Users/fetch_users';
$route['api/users/search'] = 'api/Users/users_search';
$route['api/users/(:num)/update'] = 'api/users/updateuser/$1';
$route['api/users/add']='api/users/insert';
$route['api/users/forgot']='api/users/forgot';