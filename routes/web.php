<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Auth::routes();



Route::group(['middleware' => ['guest']], function () {

    Route::get('/', 'backend\Auth\LoginController@getLoginForm');

    Route::post('Api/dataStudent','backend\APIController@dataStudent');
    Route::post('Api/dataSynchron','backend\APIController@dataSynchrons');
    Route::post('Api/packUsedSynchron','backend\APIController@packUsedSynchron');
    Route::post('Api/staffLogin','backend\APIController@staffLogin');
    Route::post('Api/staffLogout','backend\APIController@staffLogout');
    
    Route::post('Api/staffCourse','backend\APIController@staffCourse');
    Route::post('Api/staffSemester','backend\APIController@staffSemester');
    Route::post('Api/staffSubject','backend\APIController@staffSubject');
    Route::post('Api/staffSubjectPath','backend\APIController@staffSubjectPath');
    Route::post('Api/staffSubjectFiles','backend\APIController@staffSubjectFiles');
    Route::post('Api/UploadFileSave','backend\APIController@UploadFileSave');

    Route::post('Api/staffLoginSemster','backend\APIController@staffLoginSemster');

    Route::post('Api/staffSclClass','backend\APIController@staffClass');
    Route::post('Api/staffSclSection','backend\APIController@staffSection');
    Route::post('Api/staffSclSubject','backend\APIController@staffSclSubject');
    Route::post('Api/staffSclSubjectFile','backend\APIController@staffSclSubjectFile');
    Route::post('Api/licenselogin','backend\APIController@licenselogin');
    Route::post('Api/listlicenseloginuser','backend\APIController@listlicenseloginuser');
    
    // ADMIN
    Route::get('admin', 'backend\Auth\LoginController@getLoginForm');
    Route::get('admin/login', 'backend\Auth\LoginController@getLoginForm');
    Route::post('admin/authenticate', 'backend\Auth\LoginController@authenticate');
   // Route::get('/brickyard-web', 'backend\Auth\LoginController@getLoginForm');

    // USER
    Route::get('user/login', 'Auth\LoginController@userLoginForm');
    Route::post('user/authenticate', 'Auth\LoginController@userAuthenticate');

    //Route::get('user/register', 'frontend\Auth\RegisterController@getRegisterForm');
    //Route::post('user/saveregister', 'frontend\Auth\RegisterController@saveRegisterForm');

});


Route::group(['middleware' => ['user']], function () {

    Route::get('user/logout', 'Auth\LoginController@userLogout');

    //Route::post('user/logout', 'frontend\Auth\LoginController@getLogout');
    Route::get('user/dashboard', 'frontend\UserController@dashboard');
    Route::get('user/dashboard1/', function () {
        return view('frontend.dashboard');
    });

    Route::get('user/home', 'user\UserController@home');
    Route::any('user/logout', 'Auth\LoginController@userLogout');
     
    Route::get('user/filemanagerclgstf', 'user\FileManagerClgStfDBController@index');
    Route::post('user/change_password/password_check', 'user\UserController@OldPasswordCheck');
    Route::post('user/change_password/new_password_check', 'user\UserController@NewPasswordCheck');
    Route::post('user/change_password/store', 'user\UserController@change_password');
    Route::get('user/help', 'user\UserController@help');
    Route::post('user/feedback/store', 'user\UserController@feedback');


    Route::get('user/filemanager', 'user\FileManagerDBController@index');
    Route::post('user/filemanager/ajaxfm','user\FileManagerDBController@ajaxfm');
    Route::post('user/filemanager/create_folder','user\FileManagerDBController@create_folder');
    Route::post('user/filemanager/create_file', 'user\FileManagerDBController@create_file');
    Route::post('user/filemanager/delete','user\FileManagerDBController@delete');
    Route::post('user/filemanager/getBrdcrum','user\FileManagerDBController@getBrdcrum');
    Route::any('user/filemanager/rename','user\FileManagerDBController@rename');
    Route::any('user/filemanager/renameflder','user\FileManagerDBController@renameflder');
    Route::any('user/filemanager/ajaxsearchfm','user\FileManagerDBController@ajaxsearchfm');
    Route::any('user/filemanager/filePublish','user\FileManagerDBController@filePublish');
    Route::any('user/filemanager/updatedesc','user\FileManagerDBController@updatedesc');


    Route::post('user/filemanagerclgstf/ajaxfm','user\FileManagerClgStfDBController@ajaxfm');
    Route::post('user/filemanagerclgstf/create_folder','user\FileManagerClgStfDBController@create_folder');
    Route::post('user/filemanagerclgstf/create_file', 'user\FileManagerClgStfDBController@create_file');
    Route::post('user/filemanagerclgstf/delete','user\FileManagerClgStfDBController@delete');
    Route::post('user/filemanagerclgstf/getBrdcrum','user\FileManagerClgStfDBController@getBrdcrum');
    Route::any('user/filemanagerclgstf/rename','user\FileManagerClgStfDBController@rename');
    Route::any('user/filemanagerclgstf/renameflder','user\FileManagerClgStfDBController@renameflder');
    Route::any('user/filemanagerclgstf/ajaxsearchfm','user\FileManagerClgStfDBController@ajaxsearchfm'); 
    Route::any('user/filemanagerclgstf/filePublish','user\FileManagerClgStfDBController@filePublish');
    Route::any('user/filemanagerclgstf/updatedesc','user\FileManagerClgStfDBController@updatedesc');

     /**College student file manager view **/
    Route::get('user/filemanagerclgstudent', 'user\FileManagerClgStudentController@index');
    Route::post('user/filemanagerclgstudent/HomeAjxDir', 'user\FileManagerClgStudentController@getHomeAjxDir');
    Route::post('user/filemanagerclgstudent/searchHomeAjxDir', 'user\FileManagerClgStudentController@getSearchHomeAjxDir');
    Route::post('user/filemanagerclgstudent/subjectSelect', 'user\FileManagerClgStudentController@subjectSelect');
    Route::post('user/filemanagerclgstudent/staffSelect', 'user\FileManagerClgStudentController@staffSelect');

    /**School student file manager  **/
    Route::get('user/filemanagersclstudent', 'user\FileManagerSclStudentController@index');
    Route::post('user/filemanagersclstudent/ajaxfm', 'user\FileManagerSclStudentController@ajaxfm');
    Route::post('user/filemanagersclstudent/ajaxsearchfm', 'user\FileManagerSclStudentController@ajaxsearchfm');
     Route::post('user/filemanagersclstudent/staffSelect', 'user\FileManagerSclStudentController@staffSelect');
});



Route::group(['middleware' => ['admin']], function () {

    Route::get('admin/home', 'backend\AdminController@home');
    Route::get('admin/logout', 'backend\Auth\LoginController@getLogout');
    Route::post('admin/logout', 'backend\Auth\LoginController@getLogout');
    Route::any('admin/setting', 'backend\SettingController@index');
    Route::any('admin/setting/Upload_Banner', 'backend\SettingController@Upload_Banner');
    Route::any('admin/setting/addTitle', 'backend\SettingController@addTitle');
    
    Route::post('/admin/setting/store', 'backend\SettingController@store');
    Route::get('admin/change_password', 'backend\ChangePassController@index');
    Route::post('/admin/change_password/store', 'backend\ChangePassController@store');
    Route::post('/admin/change_password/password_check', 'backend\ChangePassController@OldPasswordCheck');

    //Admin user creation 
    Route::get('admin/userCreation', 'backend\AdminUserCreationController@index');
    Route::get('admin/userCreation/add', 'backend\AdminUserCreationController@create');
    Route::post('admin/userCreation/store', 'backend\AdminUserCreationController@store');
    Route::post('admin/userCreation/actionupdate','backend\AdminUserCreationController@actionupdate');
    Route::get('admin/userCreation/edit/{id}', 'backend\AdminUserCreationController@edit');
    Route::post('admin/userCreation/update', 'backend\AdminUserCreationController@update');
    

    //Admin Clg file permission
    Route::get('admin/userClgFile', 'backend\UserFilePermissionClgController@index');
    Route::get('admin/userClgFile/add', 'backend\UserFilePermissionClgController@create');
    Route::post('admin/userClgFile/userPermissionAjax', 'backend\UserFilePermissionClgController@userPermissionAjax');
    Route::post('admin/userClgFile/userPermissionCurdAjax', 'backend\UserFilePermissionClgController@userPermissionCurdAjax');

    //Admin Scl file permission
    Route::get('admin/userSclFile', 'backend\UserFilePermissionSclController@index');
    Route::get('admin/userSclFile/add', 'backend\UserFilePermissionSclController@create');
    Route::post('admin/userSclFile/userPermissionAjax', 'backend\UserFilePermissionSclController@userPermissionAjax');
    Route::post('admin/userSclFile/userPermissionCurdAjax', 'backend\UserFilePermissionSclController@userPermissionCurdAjax');
    
    
    

     // User Route
    Route::get('admin/user', 'backend\UserController@index');
    Route::post('admin/user/actionupdate', 'backend\UserController@actionupdate');


    //school class creation
    Route::get('admin/schclass', 'backend\ClassCreController@index');
    Route::get('admin/schclass/add', 'backend\ClassCreController@create');
    Route::post('admin/schclass/store', 'backend\ClassCreController@store');
    Route::get('admin/schclass/edit/{id}', 'backend\ClassCreController@edit');
    Route::post('admin/schclass/update', 'backend\ClassCreController@update');
    Route::post('admin/schclass/actionupdate','backend\ClassCreController@actionupdate');

    //school section creation
    Route::get('admin/schsection', 'backend\SchSectionController@index');
    Route::get('admin/schsection/add', 'backend\SchSectionController@create');
    Route::post('admin/schsection/store', 'backend\SchSectionController@store');
    Route::get('admin/schsection/edit/{id}', 'backend\SchSectionController@edit');
    Route::post('admin/schsection/update', 'backend\SchSectionController@update');
    Route::post('admin/schsection/actionupdate','backend\SchSectionController@actionupdate');

    //school section mapping creation
    Route::get('admin/schsectionmap', 'backend\SchClassSectionMappingController@index');
    Route::get('admin/schsectionmap/add', 'backend\SchClassSectionMappingController@create');
    Route::post('admin/schsectionmap/store', 'backend\SchClassSectionMappingController@store');
    Route::get('admin/schsectionmap/edit/{id}', 'backend\SchClassSectionMappingController@edit');
    Route::post('admin/schsectionmap/update', 'backend\SchClassSectionMappingController@update');
    Route::post('admin/schsectionmap/actionupdate','backend\SchClassSectionMappingController@actionupdate');


    //school staff creation
    Route::get('admin/schstaff', 'backend\SclStaffController@index');
    Route::get('admin/schstaff/add', 'backend\SclStaffController@create');
    Route::post('admin/schstaff/store', 'backend\SclStaffController@store');
    Route::post('admin/schstaff/bulkstore','backend\SclStaffController@bulkstore');
    Route::get('admin/schstaff/edit/{id}', 'backend\SclStaffController@edit');
    Route::post('admin/schstaff/update', 'backend\SclStaffController@update');
    Route::post('admin/schstaff/actionupdate','backend\SclStaffController@actionupdate');

    //school staff-class mapping creation
    Route::get('admin/schstfclsmap', 'backend\SchStfClsMapController@index');
    Route::get('admin/schstfclsmap/add', 'backend\SchStfClsMapController@create');
    Route::post('admin/schstfclsmap/store', 'backend\SchStfClsMapController@store');
    Route::post('admin/schstfclsmap/bulkstore','backend\SchStfClsMapController@bulkstore');
    Route::get('admin/schstfclsmap/show/{id}','backend\SchStfClsMapController@show');
    Route::get('admin/schstfclsmap/edit/{id}', 'backend\SchStfClsMapController@edit');
    Route::post('admin/schstfclsmap/update', 'backend\SchStfClsMapController@update');
    Route::post('admin/schstfclsmap/actionupdate','backend\SchStfClsMapController@actionupdate');


    //school staff-subje mapping creation
    Route::get('admin/schstaffsubmapp', 'backend\SchStfSubjectMapController@index');
    Route::get('admin/schstaffsubmapp/add', 'backend\SchStfSubjectMapController@create');
    Route::post('admin/schstaffsubmapp/store', 'backend\SchStfSubjectMapController@store');
    //Route::post('admin/schstaffsubmapp/bulkstore','backend\SchStfClsMapController@bulkstore');
    Route::get('admin/schstaffsubmapp/show/{id}','backend\SchStfSubjectMapController@show');
    Route::get('admin/schstaffsubmapp/edit/{id}', 'backend\SchStfSubjectMapController@edit');
    Route::post('admin/schstaffsubmapp/update', 'backend\SchStfSubjectMapController@update');
    Route::post('admin/schstaffsubmapp/actionupdate','backend\SchStfSubjectMapController@actionupdate');
    Route::post('admin/schstaffsubmapp/depdropbox', 'backend\SchStfSubjectMapController@depDropBox');
    Route::post('admin/schstaffsubmapp/depdropboxSec', 'backend\SchStfSubjectMapController@depdropboxSection');

     /*Staff File Manager test*/
    Route::get('admin/schstffolder', 'backend\FileManagerController@index');
    Route::post('admin/schstffolder/ajaxfm','backend\FileManagerController@ajaxfm');
    Route::post('admin/schstffolder/create_folder','backend\FileManagerController@create_folder');
    Route::post('admin/schstffolder/create_file', 'backend\FileManagerController@create_file');
    Route::post('admin/schstffolder/delete','backend\FileManagerController@delete');
    /*school Student File Manager*/

    /* School Staff File Manager Live*/
    Route::get('admin/schstfdbfolder', 'backend\FileManagerDBController@index');
    Route::post('admin/schstfdbfolder/ajaxfm','backend\FileManagerDBController@ajaxfm');
    Route::post('admin/schstfdbfolder/create_folder','backend\FileManagerDBController@create_folder');
    Route::post('admin/schstfdbfolder/create_file', 'backend\FileManagerDBController@create_file');
    Route::post('admin/schstfdbfolder/delete','backend\FileManagerDBController@delete');
    Route::post('admin/schstfdbfolder/getBrdcrum','backend\FileManagerDBController@getBrdcrum');
    Route::any('admin/schstfdbfolder/rename','backend\FileManagerDBController@rename');
    Route::any('admin/schstfdbfolder/renameflder','backend\FileManagerDBController@renameflder');
    Route::any('admin/schstfdbfolder/ajaxsearchfm','backend\FileManagerDBController@ajaxsearchfm'); 

     
    //Settion Package Controller 
    Route::get('admin/schusedpack','backend\UsedPackController@index');
    Route::get('admin/schusedpack/add','backend\UsedPackController@create');

    //school student creation
    Route::get('admin/schstudent', 'backend\SclStudentController@index');
    Route::get('admin/schstudent/add', 'backend\SclStudentController@create');
    Route::post('admin/schstudent/depdropbox', 'backend\SclStudentController@depDropBox');
    Route::post('admin/schstudent/store', 'backend\SclStudentController@store');
    Route::post('admin/schstudent/bulkstore','backend\SclStudentController@bulkstore');
    Route::get('admin/schstudent/edit/{id}', 'backend\SclStudentController@edit');
    Route::post('admin/schstudent/update', 'backend\SclStudentController@update');
    Route::post('admin/schstudent/actionupdate','backend\SclStudentController@actionupdate');
    Route::post('admin/schstudent/checkroll','backend\SclStudentController@checkroll');

    //school Subject creation
    Route::get('admin/schsubject', 'backend\SchSubjectController@index');
    Route::get('admin/schsubject/add', 'backend\SchSubjectController@create');
    Route::post('admin/schsubject/depdropbox', 'backend\SchSubjectController@depDropBox');
    Route::post('admin/schsubject/store', 'backend\SchSubjectController@store');
    Route::post('admin/schsubject/bulkstore','backend\SchSubjectController@bulkstore');
    Route::get('admin/schsubject/edit/{id}', 'backend\SchSubjectController@edit');
    Route::post('admin/schsubject/update', 'backend\SchSubjectController@update');
    Route::post('admin/schsubject/actionupdate','backend\SchSubjectController@actionupdate');
    Route::post('admin/schsubject/checkroll','backend\SchSubjectController@checkroll');

    //college department creation
    Route::get('admin/clgdepart', 'backend\ClgDepartController@index');
    Route::get('admin/clgdepart/add', 'backend\ClgDepartController@create');
    Route::post('admin/clgdepart/store', 'backend\ClgDepartController@store');
    Route::get('admin/clgdepart/edit/{id}', 'backend\ClgDepartController@edit');
    Route::post('admin/clgdepart/update', 'backend\ClgDepartController@update');
    Route::post('admin/clgdepart/actionupdate','backend\ClgDepartController@actionupdate');
    Route::post('admin/clgdepart/bulkstore','backend\ClgDepartController@bulkstore');


    //college course creation
    Route::get('admin/clgcourse', 'backend\ClgCourseController@index');
    Route::get('admin/clgcourse/add', 'backend\ClgCourseController@create');
    Route::post('admin/clgcourse/store', 'backend\ClgCourseController@store');
    Route::get('admin/clgcourse/edit/{id}', 'backend\ClgCourseController@edit');
    Route::post('admin/clgcourse/update', 'backend\ClgCourseController@update');
    Route::post('admin/clgcourse/actionupdate','backend\ClgCourseController@actionupdate');
    Route::post('admin/clgcourse/depdropbox', 'backend\ClgCourseController@depDropBox');
    Route::post('admin/clgcourse/bulkstore','backend\ClgCourseController@bulkstore');
    

    //College subject creation
    Route::get('admin/clgsubject', 'backend\ClgSubjectController@index');
    Route::post('admin/clgsubject/bulkstore', 'backend\ClgSubjectController@bulkstore');
    Route::get('admin/clgsubject/add', 'backend\ClgSubjectController@create');
    Route::post('admin/clgsubject/store', 'backend\ClgSubjectController@store');
    Route::get('admin/clgsubject/viewalledit/{id}', 'backend\ClgSubjectController@viewalledit');
    Route::get('admin/clgsubject/viewalledit/{id}/{str}', 'backend\ClgSubjectController@viewalledit');
    Route::post('admin/clgsubject/actionupdate/{id}','backend\ClgSubjectController@actionupdate');
    Route::get('admin/clgsubject/edit/{id}', 'backend\ClgSubjectController@edit');
    Route::post('admin/clgsubject/update', 'backend\ClgSubjectController@update');
    Route::post('admin/clgsubject/semester', 'backend\ClgSubjectController@dropboxsemester');
    Route::get('admin/clgsubject/subedit/{id}', 'backend\ClgSubjectController@subedit');
    Route::get('admin/clgsubject/show/{id}','backend\ClgSubjectController@show');

    //College staff-subject mapping

    Route::get('admin/clgstaffsubmapp', 'backend\ClgStfSubjectMapController@index');
    Route::get('admin/clgstaffsubmapp/add', 'backend\ClgStfSubjectMapController@create');
    Route::post('admin/clgstaffsubmapp/store', 'backend\ClgStfSubjectMapController@store');
    Route::post('admin/clgstaffsubmapp/depdropboxSec', 'backend\ClgStfSubjectMapController@depdropboxSection');
     Route::get('admin/clgstaffsubmapp/show/{id}','backend\ClgStfSubjectMapController@show');
     Route::post('admin/clgstaffsubmapp/actionupdate','backend\ClgStfSubjectMapController@actionupdate');

        //college student creation
    Route::get('admin/clgstudent', 'backend\ClgStudentController@index');
    Route::get('admin/clgstudent/add', 'backend\ClgStudentController@create');
    Route::post('admin/clgstudent/store', 'backend\ClgStudentController@store');
    Route::get('admin/clgstudent/viewalledit/{id}', 'backend\ClgStudentController@viewalledit');
    Route::get('admin/clgstudent/viewalledit/{id}/{str}', 'backend\ClgStudentController@viewalledit');
    Route::post('admin/clgstudent/actionupdate/{id}','backend\ClgStudentController@actionupdate');
    Route::get('admin/clgstudent/edit/{id}', 'backend\ClgStudentController@edit');
    Route::post('admin/clgstudent/update', 'backend\ClgStudentController@update');
    //Route::post('admin/clgstudent/actionupdate','backend\ClgStudentController@actionupdate');
    Route::post('admin/clgstudent/depdropbox', 'backend\ClgStudentController@depDropBox');
    Route::post('admin/clgstudent/courdropbox', 'backend\ClgStudentController@courDropBox');
    Route::post('admin/clgstudent/semdropbox', 'backend\ClgStudentController@semDropBox');
    Route::get('admin/clgstudent/stuedit/{id}', 'backend\ClgStudentController@stuedit');
    Route::post('admin/clgstudent/bulkstore','backend\ClgStudentController@bulkstore');
    

    //college staff creation
    Route::get('admin/clgstaff', 'backend\ClgStaffController@index');
    Route::get('admin/clgstaff/add', 'backend\ClgStaffController@create');
    Route::post('admin/clgstaff/store', 'backend\ClgStaffController@store');
    Route::post('admin/clgstaff/bulkstore','backend\ClgStaffController@bulkstore');
    Route::get('admin/clgstaff/edit/{id}', 'backend\ClgStaffController@edit');
    Route::post('admin/clgstaff/update', 'backend\ClgStaffController@update');
    Route::post('admin/clgstaff/actionupdate','backend\ClgStaffController@actionupdate');     


    // key customer Route
    Route::get('admin/keycustomer', 'backend\KeyCustomerController@index');
    Route::get('admin/keycustomer/add', 'backend\KeyCustomerController@create');
    Route::post('admin/keycustomer/store', 'backend\KeyCustomerController@store');
    Route::get('admin/keycustomer/show/{id}','backend\KeyCustomerController@show');
    Route::get('admin/keycustomer/edit/{id}', 'backend\KeyCustomerController@edit');
    Route::any('admin/keycustomer/update', 'backend\KeyCustomerController@update');
    Route::post('admin/keycustomer/actionupdate','backend\KeyCustomerController@actionupdate');
    Route::post('admin/keycustomer/check_mail', 'backend\KeyCustomerController@check_mail');

    //School Create Route

    Route::get('admin/school', 'backend\SchoolController@index');
    Route::get('admin/school/add', 'backend\SchoolController@create');
    Route::post('admin/school/store', 'backend\SchoolController@store');
    Route::get('admin/school/show/{id}','backend\SchoolController@show');
    Route::get('admin/school/edit/{id}', 'backend\SchoolController@edit');
    Route::any('admin/school/update', 'backend\SchoolController@update');
    Route::post('admin/school/actionupdate','backend\SchoolController@actionupdate');
    

});
