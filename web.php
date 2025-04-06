<?php

use App\Http\Controllers\backend\JazzController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\HomeController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\CourseController;
use App\Http\Controllers\backend\ReigonController;
use App\Http\Controllers\backend\PriceController;
use App\Http\Controllers\backend\RegistrationController;
use App\Http\Controllers\backend\SubmissionController;
use App\Http\Controllers\FrontHomeController;
use App\Http\Controllers\backend\BankDetailsController;
use App\Http\Controllers\backend\FaqController;
use App\Http\Controllers\backend\BadgeReportController;



// use App\Http\Controllers\Teacher\VideoController;
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

// Route::get('/', function () {
//     return view('welcome');

// });



// Manage
Route::get('/managevent', [HomeController::class, 'manageEvent'])->name('manage.event');
Route::get('/manageuser', [HomeController::class, 'manageUser'])->name('manage.user');
Route::get('/managechapter', [HomeController::class, 'manageChapter'])->name('manage.chapter');
Route::get('/managecategory', [HomeController::class, 'manageCategory'])->name('manage.category');
Route::get('/managetype', [HomeController::class, 'manageType'])->name('manage.type');
Route::get('/managelocation', [HomeController::class, 'manageLocation'])->name('manage.location');

// View
Route::get('/viewevent', [HomeController::class, 'viewEvent'])->name('view.event');

// ADD 
Route::get('/addevent',[HomeController::class,'addEvent'])->name('add.event');
Route::get('/adduser', [HomeController::class, 'addUser'])->name('add.user');
Route::get('/addlocation', [HomeController::class, 'addLocation'])->name('add.location');
Route::get('/addtype', [HomeController::class, 'addType'])->name('add.type');
Route::get('/addcategory', [HomeController::class, 'addCategory'])->name('add.category');
Route::get('/addchapter', [HomeController::class, 'addChapter'])->name('add.chapter');

// Auth
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/signup', [HomeController::class, 'signUp'])->name('signup');

Route::get('/admin/login', [AdminController::class,'login'])->name('admin.login');
Route::post('/admin/signin', [AdminController::class,'SingIn'])->name('admin.siginin');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin',[HomeController::class, 'index'])->name('icna');
    Route::get('/admin/dashboard', [AdminController::class,'index'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class,'logout'])->name('admin.logout');
    Route::get('/userprofile', [HomeController::class, 'userProfile'])->name('user.profile');
    Route::post('admin/users-profile/profile',[AdminController::class,'profile_update'])->name('admin.update');
    Route::get('admin/users-profile/delete-profilepic/{id}',[AdminController::class,'delete_admin_img'])->name('admin.delete_img');
    Route::post('admin/users-profile/password',[AdminController::class,'password_update'])->name('admin.change.password');
Route::get('/Admin/slider', [AdminController::class, 'index'])->name('admin.banner');
Route::get('/Admin/slider/goto',[AdminController::class, 'goto'])->name('add');
Route::post('/Admin/slider/goto/add',[AdminController::class, 'add_banner'])->name('add.banner');
Route::get('/Admin/slider/delete/{id}', [AdminController::class, 'delete_banner'])->name('delete.banner');
Route::get('/Admin/slider/update/{id}', [AdminController::class, 'showUpdateBanner'])->name('show.update.banner'); // Display form
Route::post('/Admin/slider/update/{id}', [AdminController::class, 'updateBanner'])->name('update.banner'); // Handle form submission

// Courses
Route::get('/admin/managecourses', [CourseController::class, 'index'])->name('manage.courses');
Route::get('/admin/managecourses/addpage', [CourseController::class, 'addCoursesPage'])->name('add.page');
Route::post('/admin/managecourses/addpage', [CourseController::class, 'addCourses'])->name('add.course');
Route::get('/admin/managecourses/delete/{id}', [CourseController::class, 'deleteCourse'])->name('delete.course');
Route::get('/admin/managecourses/coursedetails/{id}', [CourseController::class, 'showCourseDetails'])->name('details.course');
Route::post('/admin/managecourses/coursedetails/{id}', [CourseController::class, 'updateCourse'])->name('update.course');

// Region 
Route::get('/admin/region',[ReigonController::class, 'index'])->name('region');
Route::get('/admin/region/addregion',[ReigonController::class, 'addRegionPage'])->name('add.region');
Route::post('/admin/region/addregion',[ReigonController::class, 'addRegion'])->name('save.region');
Route::get('/admin/region/delete/{id}', [ReigonController::class, 'deleteRegion'])->name('delete.region');
Route::get('/admin/region/updateregion/{id}', [ReigonController::class, 'showRegion'])->name('show.region');
Route::post('/admin/region/updateregion/{id}', [ReigonController::class, 'updateRegion'])->name('update.region');
 

// Register



//Prices
// Route::get('/admin/price', [PriceController::class, 'index'])->name('price');
// Route::get('/admin/price/create', [PriceController::class, 'createPriceForm'])->name('create.price');
// Route::post('/admin/price/create', [PriceController::class, 'addPrice'])->name('save.price');
// Route::get('/admin/price/delete/{id}', [PriceController::class, 'deletePrice'])->name('delete.price'); 
// Route::get('/admin/price/update/{id}', [PriceController::class, 'showPrice'])->name('show.price'); 
// Route::post('/admin/price/update/{id}', [PriceController::class, 'updatePrice'])->name('update.price'); 


// Registration
Route::get('/admin/registration',[RegistrationController::class, 'index'])->name('registration');
Route::get('/admin/registration/add',[RegistrationController::class, 'showRegistrationForm'])->name('add.registration');
Route::post('/admin/registration/add',[RegistrationController::class, 'addRegistration'])->name('save.registration');
Route::get('admin/registeration/verify-reciept/{reg_id}',[RegistrationController::class,'verify_reciept'])->name('approve.registration');
Route::get('admin/registeration/veiw-details/{reg_id}',[RegistrationController::class,'view_details'])->name('registration_details');
Route::get('admin/registeration/download-CSV',[RegistrationController::class,'makeCSV'])->name('registration_csv');
///
Route::get('/delete-registration/{reg_id}', [RegistrationController::class, 'delete'])->name('delete_registration');
////
Route::post('/change-status', [RegistrationController::class, 'changeStatus'])->name('change.status');

// Route::get('/admin/registration/delete/{id}',[RegistrationController::class, 'deleteRegistration'])->name('delete.registration');
// Route::get('/admin/registration/update/{id}',[RegistrationController::class, 'showRegistraionForUpdate'])->name('show.update.registration');
// Route::post('/admin/registration/update/{id}',[RegistrationController::class, 'updateRegistration'])->name('update.registration');


Route::get('/admin/reviews',[AdminController::class, 'reviews_manage'])->name('reviews_manage');
Route::get('/admin/reviews/add',[AdminController::class, 'reviews_add'])->name('reviews_add');
Route::post('/admin/reviews/save',[AdminController::class, 'reviews_save'])->name('reviews_save');
Route::get('/admin/reviews/edit/{id}',[AdminController::class, 'reviews_edit'])->name('reviews_edit');
Route::put('/admin/reviews/udpate/{id}',[AdminController::class, 'reviews_update'])->name('reviews_update');
Route::get('/admin/reviews/delete/{id}',[AdminController::class, 'reviews_destroy'])->name('reviews_delete'); 




///Submitted/
Route::get('admin/submitted',[SubmissionController::class,'index']);
Route::get('admin/submitted/create', [SubmissionController::class,'create'])->name('submitted.add');
Route::post('admin/submitted/store', [SubmissionController::class,'store'])->name('submitted.store');
Route::get('admin/submitted/edit/',[SubmissionController::class,'edit'])->name('submitted.edit');
Route::post('admin/submitted/update', [SubmissionController::class,'update'])->name('submitted.update');
Route::get('admin/submitted/delete/{id}',[SubmissionController::class,'delete'])->name('submitted.delete');

//Front end  
});
// C
Route::get('/', [FrontHomeController::class, 'index'])->name('home_index');
// Route::get('front/home/register/{course_id}/{region_id}', [FrontHomeController::class, 'register'])->name('register');
Route::get('front/home/view_details/{course_id}/{region_id}', [FrontHomeController::class, 'view_details'])->name('view_details');
Route::get('front/home/view_details/search', [FrontHomeController::class, 'search_course'])->name('search.course');
Route::get('{slug}/{course_id}/{region_id}', [FrontHomeController::class, 'register'])->name('register');
Route::post('front/home/save-registeration', [FrontHomeController::class, 'save_registeration'])->name('registeration.save');


Route::get('front/home/contact_us', [FrontHomeController::class, 'contact_us'])->name('contact_us'); 
Route::get('front/home/faqs', [FrontHomeController::class, 'faqs'])->name('faqs');
Route::get('front/home/terms_conditions', [FrontHomeController::class, 'terms_conditions'])->name('terms_conditions');


Route::post('/set_region', [FrontHomeController::class, 'setRegion'])->name('set_region');
Route::get('/fetch-courses-by-region', [FrontHomeController::class, 'fetchCoursesByRegion'])->name('fetch_courses_by_region');
 
// Route::get('front/home/register/{course_id}/{region_id}', [FrontHomeController::class, 'bankDetails'])->name('bankdetails');



//Bank Details Controller
Route::get('/admin/bankdetails', [BankDetailsController::class, 'index'])->name('bank.index');
Route::get('/admin/bankdetails/add', [BankDetailsController::class, 'showBankDetails'])->name('bank.show');

Route::post('/admin/bankdetails/add', [BankDetailsController::class, 'addBankDetails'])->name('bank.store');
Route::get('/admin/bankdetails/delete/{id}', [BankDetailsController::class, 'deleteBankDetails'])->name('bank.delete');
Route::get('/admin/bankdetails/update/{id}', [BankDetailsController::class, 'showUpadteBankDetails'])->name('bank.update');
Route::post('/admin/bankdetails/update/{id}', [BankDetailsController::class, 'updateBankDetails'])->name('bank.storeupdate');


/*FAQ*/
Route::get('admin/faq',[FaqController::class,'index'])->name('faq');
Route::get('admin/faq/create', [FaqController::class,'create'])->name('faq.add');
Route::post('admin/faq/store', [FaqController::class,'store'])->name('faq.store');
Route::get('admin/faq/edit/{id}',[FaqController::class,'edit'])->name('faq.edit');
Route::post('admin/faq/update', [FaqController::class,'update'])->name('faq.update');
Route::get('admin/faq/delete/{id}',[FaqController::class,'delete'])->name('faq.delete');

// Payment gateway

Route::post('/payment/return',[JazzController::class,'return'])->name('payment.return');
// Route::get('/payment/data/{amount}',[JazzController::class,'trxnData'])->name('payment.trxnData');
Route::get('/payment/test',[JazzController::class,'test'])->name('payment.test');
Route::post('/payment/testData',[JazzController::class,'trxnData'])->name('payment.redirect');
Route::post('/payment/inquire',[JazzController::class,'status_inquire'])->name('payment.inquire');

// badge report

Route::get('/admin/badgereport', [BadgeReportController::class, 'index'])->name('badge.index');
Route::get('/admin/badgereport/filter', [BadgeReportController::class, 'filterRegistrations'])->name('registrations.filter');
Route::get('admin/badgereport/download-CSV',[BadgeReportController::class,'makeCSV'])->name('badgereport_csv');
///////////////////////////////newadd route

Route::post('/jazzcash/initiate', [JazzController::class, 'initiatePayment'])
    ->name('jazzcash.initiate')
    ->middleware('api'); // Use API middleware if needed

Route::post('/jazzcash/callback', [JazzController::class, 'paymentCallback'])
    ->name('jazzcash.callback');
    // Route::get('/redirect-to-jazzcash', [PaymentController::class, 'redirectToJazzCash']);
  
    Route::post('/admin/registration/add/new',[RegistrationController::class, 'addnewRegistration'])->name('save.newregistration');
