<?php

Route::get("/sitemap.xml", array("as"   => "sitemap", "uses" => "HomeController@sitemap",));

Auth::routes();
Route::get('/mail', 'MailController@mail');
Route::get('/changePassword','HomeController@showChangePasswordForm');
Route::post('/change/password','HomeController@changePassword');
Route::get('/recovery/{type}/{customer_id}/{code}', 'HomeController@recovery');
Route::post('/updatePass', 'HomeController@updatePass');
Route::get('/activate/{customer_id}/{code}', 'HomeController@activate');
// ------------------- FRONEND  -------------------------------------------
Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::post('/property/rating','HomeController@rating');
Route::get('/property_info/{property_id}',['as' => 'property_info','uses' =>'HomeController@property_info']);
Route::get('/properties/', ['as' => 'propertys','uses' =>'HomeController@properties']);
Route::get('/contact', ['as' => 'contact','uses' =>'HomeController@contact']);
Route::get('/about', ['as' => 'about','uses' =>'HomeController@about']);
Route::get('/help', ['as' => 'help','uses' =>'HomeController@help']);
Route::get('/cooperation', ['as' => 'cooperation','uses' =>'HomeController@cooperation']);


Route::get('/comments',['as' => 'comment','uses' =>'HomeController@comments']);
Route::post('/save_comment','HomeController@save_comment');

Route::get('/order/{property_id}/{room1}/{room2}/{room3}/{room4}', ['uses' =>'HomeController@order']);
Route::get('/rules/',['as' => 'rules','uses' =>'HomeController@rules']);


Route::get('/ajancy_info/{ajancy_id}','HomeController@ajancy_info');
Route::get('/ajancies/','HomeController@ajancies');

//customer controller
Route::post('/login','CustomerController@login');
Route::post('/register','CustomerController@register');
Route::get('customer/logout','CustomerController@logout');
//order

//search box
Route::post('/property/search','SearchController@search');
Route::get('/property/search','SearchController@search');

Route::POST('/property/search_ajax','SearchController@search_ajax');

//comment
Route::post('/property/ifComment','CommentController@ifComment');
Route::post('/comment/save','CommentController@save');

// ------------------- BACKEND  -------------------------------------------
Route::get('/okAdminShod','AdminController@login');
Route::post('/okAdminShod/dashboard','AdminController@dashboard'); // when post admin_email admin_password 
Route::get('/okAdminShod/dashboard','AdminController@dashboard'); // when nothing posted 
Route::get('/okAdminShod/destroy',function(){
	Session::flush();
});

// E 07 ---------
Route::get('/logout','SuperAdminController@logout');

Route::get('/userProperty','UserController@login');
Route::post('/userProperty/dashboard','UserController@dashboard'); // when post admin_email admin_password
Route::get('/userProperty/dashboard','UserController@dashboard'); // when nothing posted
Route::get('/userProperty/destroy',function(){
    Session::flush();
});

// ajancy ROUTES
//E 17
Route::get('/okAdminShod/customer/add','CustomerController@add');
Route::post('/okAdminShod/customer/save','CustomerController@save');
Route::get('/okAdminShod/customer/all','CustomerController@all');
Route::get('/okAdminShod/customer/{customer_id}/delete','CustomerController@delete');
Route::get('/okAdminShod/customer/{customer_id}/active','CustomerController@active');
Route::get('/okAdminShod/customer/{customer_id}/unactive','CustomerController@unactive');
Route::get('/okAdminShod/customer/{customer_id}/edit','CustomerController@edit');
Route::post('/okAdminShod/customer/{customer_id}/edit/done','CustomerController@done_edit');


Route::get('/okAdminShod/prof/add','ProfController@add');
Route::post('/okAdminShod/prof/save','ProfController@save');
Route::get('/okAdminShod/prof/all','ProfController@all');
Route::get('/okAdminShod/prof/{prof_id}/delete','ProfController@delete');
Route::get('/okAdminShod/prof/{prof_id}/active','ProfController@active');
Route::get('/okAdminShod/prof/{prof_id}/unactive','ProfController@unactive');
Route::get('/okAdminShod/prof/{prof_id}/edit','ProfController@edit');
Route::post('/okAdminShod/prof/{prof_id}/edit/done','ProfController@done_edit');



Route::get('/okAdminShod/course/add','CourseController@add');
Route::post('/okAdminShod/course/save','CourseController@save');
Route::get('/okAdminShod/course/all','CourseController@all');
Route::get('/okAdminShod/course/{course_id}/delete','CourseController@delete');
Route::get('/okAdminShod/course/{course_id}/active','CourseController@active');
Route::get('/okAdminShod/course/{course_id}/unactive','CourseController@unactive');
Route::get('/okAdminShod/course/{course_id}/edit','CourseController@edit');
Route::post('/okAdminShod/course/{course_id}/edit/done','CourseController@done_edit');




Route::get('/okAdminShod/exam/add','ExamController@add');
Route::post('/okAdminShod/exam/save','ExamController@save');
Route::get('/okAdminShod/exam/all','ExamController@all');
Route::get('/okAdminShod/exam/{exam_id}/delete','ExamController@delete');
Route::get('/okAdminShod/exam/{exam_id}/active','ExamController@active');
Route::get('/okAdminShod/exam/{exam_id}/unactive','ExamController@unactive');
Route::get('/okAdminShod/exam/{exam_id}/edit','ExamController@edit');
Route::post('/okAdminShod/exam/{exam_id}/edit/done','ExamController@done_edit');
Route::get('/okAdminShod/question/{exam_id}','ExamController@showQuestion');
Route::post('/okAdminShod/question/save','ExamController@save_questions');
Route::get('/okAdminShod/taken_exams','ExamController@showTakenExams');
Route::get('/okAdminShod/exam/show_answers/{exam_id}','ExamController@showTakenExams_answers');
Route::post('okAdminShod/exam/{exam_id}/save_grade','ExamController@save_grade');







// E 25
Route::get('/okAdminShod/slider/add','SliderController@add');
Route::get('/okAdminShod/slider/all','SliderController@all');
Route::post('/okAdminShod/slider/save','SliderController@save');
Route::get('/okAdminShod/slider/{slider_id}/active','SliderController@active');
Route::get('/okAdminShod/slider/{slider_id}/unactive','SliderController@unactive');
Route::get('/okAdminShod/slider/{slider_id}/delete','SliderController@delete');
Route::get('/okAdminShod/slider/{slider_id}/edit','SliderController@edit');
Route::post('/okAdminShod/slider/{slider_id}/edit/done','SliderController@done_edit');

Route::get('/okAdminShod/rule/edit','RuleController@edit');
Route::post('/okAdminShod/rule/edit/done','RuleController@done_edit');

Route::get('/okAdminShod/exit','AdminController@logout');



//userOkShodOkShod panel ( propertyemployee )
Route::get('/userProperty/exit','UserController@logout');
Route::get('/userProperty/changePasswrod','UserController@changePassword');
Route::post('/userProperty/changePasswrod/done','UserController@done_changePassword');

///customer panel
Route::get('/customer/panel','CustomerController@dashboard');
Route::get('/customer/runExam/{exam_id}','CustomerController@runExam');
Route::post('/customer/exam/save','CustomerController@saveExam');
