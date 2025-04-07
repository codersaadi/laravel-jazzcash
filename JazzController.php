<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Review;
use App\Models\Region;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalNotification;
use App\Models\PackagePayment;
use Illuminate\Support\Facades\Log;



use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Registration;

class JazzController extends Controller
{

    public function return(Request $request){

        // Retrieve the 'pp_ResponseCode' from the POST request
        $responseCode = $request->input('pp_ResponseCode');
        $bill_ref = $request->input('pp_BillReference');
        
        // dd($bill_ref);
        

        // Log or use the response code as needed
        if ($responseCode === '000') {
            
            $registration =  Registration::where('bill_ref',$bill_ref)->first();
            $registration->status='approved';
            $name = $registration->name;
         
            $registration->save();
            session()->flash('payment_error', 1);
            
            return view('Frontend.CardSuccess',compact('name','bill_ref'));
        } else {
            // Transaction failed or some other status
            session()->flash('payment_error', 0);
            $registration =  Registration::where('bill_ref',$bill_ref)->first();
           
            $registration->status='incomplete';
            $name = $registration->name;
            return view('Frontend.CardSuccess',compact('name','bill_ref'));

        }
       
    }
    public function trxnData($amount)
{
 
    $Amount = round($amount* 100);
   
   //API Params from env
    $MerchantID  = env('JAZZ_MERCHANT_ID');
    $Password = env('JAZZ_PASSWORD');
    $HashKey = env('JAZZ_HASHKEY');
    $ReturnURL = route('payment.return');
    $PostURL = env('JAZZ_POSTURL'); 

    date_default_timezone_set("Asia/karachi");

    $BillReference = "Fri" . Carbon::now()->format('YmdHis') . mt_rand(10, 100);
    $Description = "Test-TRXN";
    $Language = "EN";
    $TxnCurrency = "PKR";
    $TxnDateTime = Carbon::now('Asia/Karachi')->format('YmdHis');
    $TxnExpiryDateTime = Carbon::now('Asia/Karachi')->addDays(3)->format('YmdHis');
    $TxnRefNumber = $BillReference;
    $TxnType = "MPAY"; 
    $Version = '1.1';

   //optional fields
    $SubMerchantID = "";
    $BankID = "";
    $ProductID = "";
    $ppmpf_1 = "";
    $ppmpf_2 = "";
    $ppmpf_3 = "";
    $ppmpf_4 = "";
    $ppmpf_5 = "";

    // Hash Generation
    $HashArray = [
        $Amount, $BankID, $BillReference, $Description, $Language, $MerchantID,
        $Password, $ProductID, $ReturnURL, $TxnCurrency, $TxnDateTime, $TxnExpiryDateTime,
        $TxnRefNumber, $TxnType, $Version, $ppmpf_1, $ppmpf_2, $ppmpf_3, $ppmpf_4,
        $ppmpf_5
    ];

    $SortedArray = $HashKey;
    foreach ($HashArray as $value) {
        if (!empty($value)) {
            $SortedArray .= "&" . $value;
        }
    }

    $Securehash = hash_hmac('sha256', $SortedArray, $HashKey);

    // Data to be posted from the form
    $formData = [
        'pp_Version' => $Version,
        'pp_TxnType' => $TxnType,
        'pp_Language' => $Language,
        'pp_MerchantID' => $MerchantID,
        'pp_SubMerchantID' => $SubMerchantID,
        'pp_Password' => $Password,
        'pp_TxnRefNo' => $TxnRefNumber,
        'pp_Amount' => $Amount,
        'pp_DiscountedAmount' => '',  
        'pp_DiscountBank' => '',  
        'pp_TxnCurrency' => $TxnCurrency,
        'pp_TxnDateTime' => $TxnDateTime,
        'pp_TxnExpiryDateTime' => $TxnExpiryDateTime,
        'pp_BillReference' => $BillReference,
        'pp_Description' => $Description,
        'pp_ReturnURL' => $ReturnURL,
        'pp_SecureHash' => $Securehash,
        'ppmpf_1' => $ppmpf_1,
        'ppmpf_2' => $ppmpf_2,
        'ppmpf_3' => $ppmpf_3,
        'ppmpf_4' => $ppmpf_4,
        'ppmpf_5' => $ppmpf_5,
        'PostURL' =>$PostURL
    ];
    
    return  $formData;
    
}
public function status_inquire(Request $request)
{
    // Get bill reference from request
    $bill_ref = $request->input('bill_ref');
    // dd($bill_ref);

    // Prepare hash array
    $hashArray = [
        "pp_TxnRefNo"  => $bill_ref,
        "pp_Password" => env('JAZZ_PASSWORD'), 
        "pp_MerchantID" => env('JAZZ_MERCHANT_ID'),
    ];

    // Sort array and create hash
    ksort($hashArray);
    $hashString = implode('&', $hashArray);
    $salt = env('JAZZ_HASHKEY');
    $hashString = $salt.'&'.$hashString;
    $hash = hash_hmac('sha256', $hashString, $salt);

    // Prepare request data
    $postData = [
        'pp_TxnRefNo' => $bill_ref,
        'pp_MerchantID' => env('JAZZ_MERCHANT_ID'),
        'pp_Password' => env('JAZZ_PASSWORD'),
        'pp_SecureHash' => $hash
    ];

    try {
        $response = Http::asForm()
            ->post(env('JAZZ_STATUS_URL'), $postData);

    

        $responseCode = $response['pp_ResponseCode'];
        $responseMessage = $response['pp_ResponseMessage'];
        $paymentResponseCode = $response['pp_PaymentResponseCode'];
        $paymentResponseMessage = $response['pp_PaymentResponseMessage'];
        $transactionStatus = $response['pp_Status'];

        if($responseCode == '000') {
            if($paymentResponseCode == '121') {
                $registration = Registration::where('bill_ref', $bill_ref)->first();
                $registration->status = 'approved';
                $registration->save();
                // dd($registration);
              
                    $course = Course::find($registration->course_id);
                    $region = Region::find($registration->region_id);
                    $currency = ($registration->region_id == 8) ? 'PKR' : '$';
                    $emailData = [
                        'name' => $registration->name,
                        'email' => $registration->email,
                        'course_name' => $course ? $course->course_name : 'Unknown Course',
                        'region' => $region ? $region->Region : 'Unknown Region',
                       'price' => $currency . ' ' . $registration->price,
                       'status' => ucfirst($registration->status),
                    ];
            
                    // Send email to user
                    Mail::to($registration->email)->send(new ApprovalNotification($emailData));
            
                Mail::to('training@friendsitsolutions.com')->send(new ApprovalNotification($registration));
                return redirect()->back()->with([
                    'alert' => [
                        'type' => 'success',
                        'title' => 'Success!',
                        'message' => $paymentResponseMessage
                    ]
                ]);
            } else {
                return redirect()->back()->with([
                    'alert' => [
                        'type' => 'warning',
                        'title' => 'Info:',
                        'message' => $paymentResponseMessage
                    ]
                ]);
            }
        } else {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => $responseMessage
                ]
            ]);
        }

    } catch (\Exception $e) {
        \Log::error('JazzCash API Error: ' . $e->getMessage());
        return redirect()->back()->with([
            'alert' => [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'An error occurred while processing your request'
            ]
        ]);
    }
}
    public function redirect(){
        return 'hello';
    }
    ////
    // public function initiatePayment(Request $request)
    // {
    // //    dd($request->all());
    //     try {
    //         // Validate input
    //         $validated = $request->validate([
    //             'product_id' => 'required|integer',
    //             'amount' => 'required|numeric',
    //             'source_site_url' => 'required|url',
    //             'duration' => 'required|string',
    //             'email' => 'required|email',
    //             'name' => 'required|string',
                
    //         ]);

    //         $course = null;

    //         // Check if type is 1 or plan_id is not null
    //        if ($request->input('type') == 1 || !is_null($request->input('product_id'))) {
    //             // Save data to the 'courses' table
    //             $course = Course::updateOrCreate(
    //                 ['plan_id' => $validated['product_id']],
    //                 [
    //                     'course_name' => 'Product ' . $validated['product_id'],
    //                     'description' => 'Payment for product ' . $validated['product_id'],
    //                     'type' => 1,
    //                     'source_site' => $validated['source_site_url'],
    //                     'advisor' => 'System',
    //                     'level' => 'Beginner',
    //                     'duration' => $validated['duration'],
    //                 ]
    //             );
    //         //    dd(  $course);
          
    //             // Store email and name in the 'registration' table
    //        Registration::create([
    //                 'email' => $validated['email'],
    //                 'name' => $validated['name'],
    //                 'payment_method'=>'card',
    //                 'status'=>'incomplete',

    //             ]);
    //   // Redirect to the frontend checkout form with auto-filled email and name
    //             return view('Frontend.checkin', [
    //                 'email' => $validated['email'],
    //                 'name' => $validated['name'],
                     
    //             ]);
    //        }

    //         // Generate transaction reference
    //         $txnRef = 'TXN' . $validated['product_id'] . time();

    //         // Create payment record
    //         $payment = PackagePayment::create([
    //             'transaction_id' => $txnRef,
    //             'product_id' => $validated['product_id'],
    //             'amount' => $validated['amount'],
    //             'status' => 'pending',
    //             'return_url' => url('/jazzcash/callback?source=' . urlencode($validated['source_site_url'])),
    //             'jazzcash_response' => null,
    //         ]);

    //         // Verify JazzCash credentials
    //         // $merchantId = config('services.jazzcash.merchant_id');
    //         // $password = config('services.jazzcash.password');
    //         // $hashKey = config('services.jazzcash.hash_key');
    //         // $paymentUrl = env('JAZZ_POSTURL');

    //         // if (empty($merchantId) || empty($password) || empty($hashKey)) {
    //         //     throw new \Exception('JazzCash credentials not configured');
    //         // }
    //         $Amount = $request->input('amount');
   
    //         //API Params from env
    //          $MerchantID  = env('JAZZ_MERCHANT_ID');
    //          $Password = env('JAZZ_PASSWORD');
    //          $HashKey = env('JAZZ_HASHKEY');
    //          $ReturnURL = route('payment.return');
    //          $PostURL = env('JAZZ_POSTURL'); 
         
    //          date_default_timezone_set("Asia/karachi");
         
    //          $BillReference = "Fri" . Carbon::now()->format('YmdHis') . mt_rand(10, 100);
    //          $Description = "Test-TRXN";
    //          $Language = "EN";
    //          $TxnCurrency = "PKR";
    //          $TxnDateTime = Carbon::now('Asia/Karachi')->format('YmdHis');
    //          $TxnExpiryDateTime = Carbon::now('Asia/Karachi')->addDays(3)->format('YmdHis');
    //          $TxnRefNumber = $BillReference;
    //          $TxnType = "MPAY"; 
    //          $Version = '1.1';
         
    //         //optional fields
    //          $SubMerchantID = "";
    //          $BankID = "";
    //          $ProductID = "";
    //          $ppmpf_1 = "";
    //          $ppmpf_2 = "";
    //          $ppmpf_3 = "";
    //          $ppmpf_4 = "";
    //          $ppmpf_5 = "";
         
    //          // Hash Generation
    //          $HashArray = [
    //              $Amount, $BankID, $BillReference, $Description, $Language, $MerchantID,
    //              $Password, $ProductID, $ReturnURL, $TxnCurrency, $TxnDateTime, $TxnExpiryDateTime,
    //              $TxnRefNumber, $TxnType, $Version, $ppmpf_1, $ppmpf_2, $ppmpf_3, $ppmpf_4,
    //              $ppmpf_5
    //          ];
         
    //          $SortedArray = $HashKey;
    //          foreach ($HashArray as $value) {
    //              if (!empty($value)) {
    //                  $SortedArray .= "&" . $value;
    //              }
    //          }
         
    //          $Securehash = hash_hmac('sha256', $SortedArray, $HashKey);
         
    //          // Data to be posted from the form
    //          $params = [
    //              'pp_Version' => $Version,
    //              'pp_TxnType' => $TxnType,
    //              'pp_Language' => $Language,
    //              'pp_MerchantID' => $MerchantID,
    //              'pp_SubMerchantID' => $SubMerchantID,
    //              'pp_Password' => $Password,
    //              'pp_TxnRefNo' => $TxnRefNumber,
    //              'pp_Amount' => $Amount,
    //              'pp_DiscountedAmount' => '',  
    //              'pp_DiscountBank' => '',  
    //              'pp_TxnCurrency' => $TxnCurrency,
    //              'pp_TxnDateTime' => $TxnDateTime,
    //              'pp_TxnExpiryDateTime' => $TxnExpiryDateTime,
    //              'pp_BillReference' => $BillReference,
    //              'pp_Description' => $Description,
    //              'pp_ReturnURL' => $ReturnURL,
    //              'pp_SecureHash' => $Securehash,
    //              'ppmpf_1' => $ppmpf_1,
    //              'ppmpf_2' => $ppmpf_2,
    //              'ppmpf_3' => $ppmpf_3,
    //              'ppmpf_4' => $ppmpf_4,
    //              'ppmpf_5' => $ppmpf_5,
    //              'PostURL' =>$PostURL
    //          ];
             
           
    //         // $params = [
    //         //     'pp_Version' => '1.1',
    //         //     'pp_TxnType' => 'MWALLET',
    //         //     'pp_Language' => 'EN',
    //         //     'pp_MerchantID' => $merchantId,
    //         //     'pp_Password' => $password,
    //         //     'pp_ReturnURL' => $payment->return_url,
    //         //     'pp_Amount' => $validated['amount'] * 100,
    //         //     'pp_TxnRefNo' => $txnRef,
    //         //     'pp_Description' => 'Product Purchase',
    //         //     'pp_BillReference' => 'prod-' . $validated['product_id'],
    //         //     'pp_TxnCurrency' => 'PKR',
    //         //     'pp_TxnDateTime' => now()->format('YmdHis'),
    //         //     'pp_TxnExpiryDateTime' => now()->addMinutes(30)->format('YmdHis'),
    //         //     'pp_SecureHash' => '',
    //         // ];

    //         // Generate Secure Hash
    //         // ksort($params);
    //         // $params['pp_SecureHash'] = hash_hmac('sha256', implode('&', $params), $hashKey);

    //         return response()->json([
    //             'success' => true,
    //             'payment_url' => $PostURL . '?' . http_build_query($params),
    //           //  'course_id' =>  $course->id ,
    //             'payment_id' => $payment->id,
    //             'txn_ref' => $txnRef
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Payment Initiation Error: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Payment processing failed',
    //             'message' => $e->getMessage(),
    //             'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : null
    //         ], 500);
    //     }
    // }
    public function initiatePayment(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'product_id' => 'required|integer',
              'price' => 'nullable|numeric',  // Changed to price to match DB
            'amount' => 'nullable|numeric',
                'source_site_url' => 'required|url',
                'duration' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
            ]);
    
            // Create or update course
            $course = Course::updateOrCreate(
                ['plan_id' => $validated['product_id']],
                [
                    'course_name' => 'Product ' . $validated['product_id'],
                    'description' => 'Payment for product ' . $validated['product_id'],
                    'type' => 1,
                    'source_site' => $validated['source_site_url'],
                    'advisor' => 'System',
                    'level' => 'Beginner',
                    'duration' => $validated['duration'],
                ]
            );
    
            // Create registration
            $registration = Registration::create([
                'email' => $validated['email'],
                'name' => $validated['name'],
                'payment_method' => 'card',
                'status' => 'incomplete',
                'amount' => $validated['amount'], // Store amount in registration
            ]);
    
            // Generate transaction reference
            $txnRef = 'TXN' . $validated['product_id'] . time();
    
            // Create payment record
            $payment = PackagePayment::create([
                'transaction_id' => $txnRef,
                'product_id' => $validated['product_id'],
                'amount' => $validated['amount'],
                'status' => 'pending',
                'return_url' => url('/jazzcash/callback?source=' . urlencode($validated['source_site_url'])),
                'jazzcash_response' => null,
            ]);
    
            $MerchantID  = env('JAZZ_MERCHANT_ID');
            $Password = env('JAZZ_PASSWORD');
            $HashKey = env('JAZZ_HASHKEY');
            $ReturnURL = route('payment.return');
            $PostURL = env('JAZZ_POSTURL'); 
        
            date_default_timezone_set("Asia/karachi");
        
            $BillReference = "Fri" . Carbon::now()->format('YmdHis') . mt_rand(10, 100);
            $Description = "Payment for Product " . $validated['product_id'];
            $Language = "EN";
            $TxnCurrency = "PKR";
            $TxnDateTime = Carbon::now('Asia/Karachi')->format('YmdHis');
            $TxnExpiryDateTime = Carbon::now('Asia/Karachi')->addDays(3)->format('YmdHis');
            $TxnRefNumber = $BillReference;
            $TxnType = "MPAY"; 
            $Version = '1.1';
        
            //optional fields
            $SubMerchantID = "";
            $BankID = "";
            $ProductID = "";
            $ppmpf_1 = "";
            $ppmpf_2 = "";
            $ppmpf_3 = "";
            $ppmpf_4 = "";
            $ppmpf_5 = "";
        
            // Use the validated amount here
            $amount = ($validated['amount'] ?? $validated['price'] ?? 0) * 100;
        
            // Hash Generation
            $HashArray = [
                $amount, $BankID, $BillReference, $Description, $Language, $MerchantID,
                $Password, $ProductID, $ReturnURL, $TxnCurrency, $TxnDateTime, $TxnExpiryDateTime,
                $TxnRefNumber, $TxnType, $Version, $ppmpf_1, $ppmpf_2, $ppmpf_3, $ppmpf_4,
                $ppmpf_5
            ];
    
            $SortedArray = $HashKey;
            foreach ($HashArray as $value) {
                if (!empty($value)) {
                    $SortedArray .= "&" . $value;
                }
            }
    
            $Securehash = hash_hmac('sha256', $SortedArray, $HashKey);
    
            $params = [
                'pp_Version' => $Version,
                'pp_TxnType' => $TxnType,
                'pp_Language' => $Language,
                'pp_MerchantID' => $MerchantID,
                'pp_SubMerchantID' => $SubMerchantID,
                'pp_Password' => $Password,
                'pp_TxnRefNo' => $TxnRefNumber,
                'pp_Amount' => $amount,  // Using the validated amount here
                'pp_DiscountedAmount' => '',  
                'pp_DiscountBank' => '',  
                'pp_TxnCurrency' => $TxnCurrency,
                'pp_TxnDateTime' => $TxnDateTime,
                'pp_TxnExpiryDateTime' => $TxnExpiryDateTime,
                'pp_BillReference' => $BillReference,
                'pp_Description' => $Description,
                'pp_ReturnURL' => $ReturnURL,
                'pp_SecureHash' => $Securehash,
                'ppmpf_1' => $ppmpf_1,
                'ppmpf_2' => $ppmpf_2,
                'ppmpf_3' => $ppmpf_3,
                'ppmpf_4' => $ppmpf_4,
                'ppmpf_5' => $ppmpf_5,
                'PostURL' => $PostURL
            ];
    
            return response()->json([
                'success' => true,
                'payment_url' => $PostURL . '?' . http_build_query($params),
                'payment_id' => $payment->id,
                'txn_ref' => $txnRef,
                'registration_id' => $registration->id
            ]);
    
        } catch (\Exception $e) {
            Log::error('Payment Initiation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Payment processing failed',
                'message' => $e->getMessage(),
                'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    public function paymentCallback(Request $request)
    {
        // Verify Secure Hash
        $receivedHash = $request->pp_SecureHash;
        $postData = $request->except('pp_SecureHash');
        ksort($postData);
        $calculatedHash = hash_hmac('sha256', implode('&', $postData), config('services.jazzcash.hash_key'));

        if ($receivedHash !== $calculatedHash) {
            Log::error('JazzCash Hash Mismatch', ['request' => $request->all()]);
            return $this->redirectToSource($request->pp_SourceSite, 'failed', 'security_validation_failed');
        }

        // Process payment status
        if ($request->pp_ResponseCode === '000') {
            Log::info('Payment Successful', [
                'txn_id' => $request->pp_TxnRefNo,
                'amount' => $request->pp_Amount / 100
            ]);
            return $this->redirectToSource($request->pp_SourceSite, 'success', $request->pp_TxnRefNo);
        }

        Log::warning('Payment Failed', [
            'reason' => $request->pp_ResponseMessage,
            'code' => $request->pp_ResponseCode
        ]);
        return $this->redirectToSource($request->pp_SourceSite, 'failed', $request->pp_ResponseMessage);
    }

    private function redirectToSource($site, $status, $param)
    {
        $sites = [
            'site1' => 'https://quickinn.fisapps.net/',
            'site2' => 'https://your-second-site.com'
        ];

        $baseUrl = $sites[$site] ?? config('app.url');
        $query = $status === 'success' 
            ? "txn_id=".urlencode($param) 
            : "error=".urlencode($param);

        return redirect("$baseUrl/payment-$status?$query");
    }
  
}
