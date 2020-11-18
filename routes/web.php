<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (!defined('ADMIN_PATH')) {
	define('ADMIN_PATH', 'admin');
}

Route::group(['prefix'=>ADMIN_PATH],function(){


	Route::group(['namespace'=>'Admin'],function(){

        Route::group(['prefix' => '/auth'], function() {
            Route::post('/login', 'AuthController@login');
        });

        Route::get('/', 'AuthController@loginIndex');
        Route::get('/login', 'AuthController@loginIndex');

        Route::group(['middleware' => 'admin'], function () {

            Route::get('/profile'         , 'AuthController@profile');
		    Route::post('/profile/edit'	, 'AuthController@updateProfile');

            Route::get('/dashboard', 'AdminController@index');
            Route::get('/logout', 'AuthController@logout');

            Route::resource('admins', 'SupervisorsController');
            Route::get('admin/search', 'SupervisorsController@search');
            Route::resource('roles', 'RolesController');

            Route::resource('users', 'UsersController');
            Route::post('/users/activate'       , 'UsersController@activate');
            Route::post('/users/ban'            , 'UsersController@ban');
            Route::get('/user/search'            , 'UsersController@search');

            Route::resource('consultants', 'ConsultantsController');
            Route::post('/consultants/activate'       , 'ConsultantsController@activate');
            Route::post('/consultants/ban'       , 'ConsultantsController@ban');
            Route::get('/consultant/search'            , 'ConsultantsController@search');

            Route::resource('countries', 'CountriesController');
            Route::get('country/search', 'CountriesController@search');

            Route::resource('lawDegrees', 'LawDegreesController');
            Route::get('lawDegree/search', 'LawDegreesController@search');

            Route::resource('consultingTypes', 'ConsultingTypesController');
            Route::get('consultingType/search', 'ConsultingTypesController@search');

            Route::resource('consultingSubTypes', 'ConsultingSubTypesController');
            Route::get('consultingSubType/search', 'ConsultingSubTypesController@search');

            Route::resource('bank','BanksController');
            Route::get('banks/search','BanksController@search');

            Route::resource('academySpecials','AcademySpecialsController');
            Route::get('academySpecial/search', 'AcademySpecialsController@search');


            Route::resource('rateReasons','RateReasonsController');
            Route::resource('appbankaccount','AppBankAccountsController');
            Route::get('appbankaccounts/search','AppBankAccountsController@search');

            //Route::resource('settings','SettingsController');
            Route::get('settings','SettingsController@edit');
            Route::patch('settings/{id}','SettingsController@update');
            Route::resource('consultations','ConsultationsController');
            Route::get('consultation/search','ConsultationsController@search');
            Route::get('consultation/chat/{id}','ConsultationsController@chat');

            Route::resource('transactions','TransactionsController');
            Route::get('transaction/search','TransactionsController@search');
            Route::post('transaction/approve','TransactionsController@approve');
            Route::post('/transaction/ConsultantTransaction','TransactionsController@ConsultantTransaction');

            Route::resource('transactionsBalance','TransactionsBalanceController');
            Route::get('transactionBalance/search','TransactionsBalanceController@search');

            Route::resource('transactionsConsultant','TransactionsConsultantController');
            Route::get('transactionConsultant/search','TransactionsConsultantController@search');

            Route::resource('transactionsReport','TransactionsReportController');
            Route::get('transactionReport/search','TransactionsReportController@search');

            Route::resource('reports','ReportController');
            Route::get('report/search','ReportController@search');

            Route::resource('notifications','NotificationController');
            Route::get("notification/search","NotificationController@search");

            //Route::resource('aboutApp','AboutAppController');
            Route::get('aboutApp','AboutAppController@edit');
            Route::patch('aboutApp/{id}','AboutAppController@update');
            Route::resource('contacts','ContactController');
            Route::get("contact/search","ContactController@search");
            Route::resource('contactTypes','ContactTypesController');
            Route::get("contactType/search","ContactTypesController@search");

            Route::resource('adminReports','adminReportsController');
            Route::get("adminReport/search","adminReportsController@search");
            Route::get("report/export","ReportsExportController@export");


            Route::resource('endReasons','EndReasonsController');

            Route::resource('transferReasons','TransferReasonsController');

            Route::resource('transferConsultations','TransferConsultationsController');
            Route::get('transferConsultation/search','TransferConsultationsController@search');


        });


    });
});

Auth::routes();

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get("/",'GeneralController@index')->middleware('SetSubQuery');
Route::get("/{any}",'GeneralController@index')->middleware('SetSubQuery');
Route::get('/privacy','GeneralController@privacy');
Route::get('/contact','GeneralController@contact');

Route::get('/reset/password','GeneralController@ResetPassword');
Route::post('/change/password','GeneralController@ChangePassword');
Route::get('/email/verified/{id}','GeneralController@EmailVerified');

