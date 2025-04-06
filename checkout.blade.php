@include('Frontend.include.head')
@include('Frontend.include.header')


    
    <nav class="breadcrunb" aria-label="breadcrumb">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('home_index')}}">Home</a></li>
                <!-- <li class="breadcrumb-item"><a href="#">Library</a></li> -->
                <li class="breadcrumb-item active" aria-current="page">Register Now </li>
            </ol>
        </div>
    </nav>
        <div class="checkout-container">
            <!-- Progress Header -->
            <ul class="progress-header">
                <li id="progress1" class="active">Personal Information</li>
                <li id="progress2">Payment Overview</li>
                <li id="progress3">Pay Now</li>
            </ul>

            <!-- Step 1: Add Information -->
            <div class="step active" id="step1">
                <!-- <h3>Step 1: </h3> -->
                <form method="POST" action="{{route('save.registration')}}" id="checkout-form" >
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="region_id" value="{{ $region->id }}">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" id="fname" placeholder="Enter your first name" required>
                                <input type="hidden" id="billRef" placeholder="Enter your first name" value="{{ $trxnData['pp_BillReference'] ?? '' }}">
                                <span id="fname-error" class="error-message" style="color: red; display: none;">First Name is required</span>
                                
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" id="lname" placeholder="Enter your last name" required>
                                <span id="lname-error" class="error-message" style="color: red; display: none;">Last Name is required</span>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" placeholder="Enter your email" required>
                                <span id="email-error" class="error-message" style="color: red; display: none;">Email is required</span>
                            </div>
                        </div>
                        <!-- <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="number">Phone No</label>
                                <input type="tel" id="number" placeholder="Enter your phone number" required>
                                <span id="number-error" class="error-message" style="color: red; display: none;">Phone Number is required</span>
                            </div>
                        </div> -->
                      
                    
    <div class="col-md-4">
        <div class="form-group">
            <label for="country_code">Country Code</label>
            <select id="country_code" class="form-control select2-country_code_s"   style="width: 100%;  ">
                @foreach($countries as $country)
                <option value="{{ $country->country_code }}" 
                data-short="{{ $country->short_name }}" 
                data-region="{{ $country->Region }}">
            {{ $country->short_name }} ({{ $country->country_code }}) ({{ $country->Region }})
        </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label for="number">Phone Number</label>
            <input type="tel" id="number"   class="form-control" placeholder="Enter your phone number" required>
            
            <span id="number-error" class="error-message" style="color: red; display: none;">Phone Number is required</span>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="comments">Comments if(any)</label>
            <textarea id="comments" class="form-control" rows="3"  placeholder="Enter your comments"></textarea>

            
        </div>
                    </div>

</div>



                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="gender" class="mb-0">Gender</label>
                                <div class="form-check-inline">
                                    <label class="form-check-label form-check-label-c">
                                        <input type="radio" class="form-check-input type-checkbox" value="male" checked>Male
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label form-check-label-c">
                                        <input type="radio" class="form-check-input type-checkbox" value="female">Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="address" class="mb-0">Type</label>
                                <div class="form-check-inline">
                                    <label class="form-check-label form-check-label-c">
                                        <input type="radio" class="form-check-input type-checkbox2" value="individual" checked>Individual
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label form-check-label-c">
                                        <input type="radio" class="form-check-input type-checkbox2" value="company">Company
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-6 col-12"> -->
                        <div class="form-group learnerDtl" style="">
                            <label for="address" class="mb-0">Select Learner</label>
                            <div class="form-check-inline">
                                <label class="form-check-label form-check-label-c" for="region1">
                                    <input class="form-check-input "  type="radio" name="region" id="region1" value="student" checked>Studying
                                </label>
                            </div>

                            <div class="form-check-inline">
                                <label class="form-check-label form-check-label-c" for="region2">
                                    <input class="form-check-input"  type="radio" name="region" id="region2" value="employee">Working
                                    <input class=""  type="hidden" name="price" id="price" value="{{$coursePrice->pivot->price}}">
                                </label>
                            </div>

                        </div>
                    <!-- </div> -->
                    
                    <div class="companyDtl" style="display:none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="c_name">Company Name</label>
                                    <input type="text" id="c_name" placeholder="Enter your company name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="designation">Designation</label>
                                    <input type="text" id="designation" placeholder="Enter your designation in company" >
                                </div>
                            </div>    
                        </div>
                    </div>
                    
                    <div class=" btnDiv"> 
                        <button type="button" class="next-btn button-c" onclick="nextStep(2)">Next</button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Payment Overview -->
            <div class="step" id="step2">
                <!-- <h3>Step 2:</h3> -->
                <div class="your-order">
                    <div class="table-responsive-sm order-table mb-1"> 
                        <table class="bg-white table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">Course Name</th>
                                    <th>Price</th>
                                    {{-- <th>Duration</th>
                                    <th>No. of Classes</th>
                                    <th>Subtotal</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-left">{{$course->course_name}}</td>
                                    <td>{{$region->Currency}}&nbsp;{{number_format($coursePrice->pivot->price,2,'.',',')}} </td>
                                    {{-- @if(isset($course->pivot) && isset($course->pivot->price))
                                      {{ $course->pivot->price }}{{ $region->Currency }}
                                    @else
                                    Not available
                                    @endif --}}

                                    {{-- <td>{{$course->duration}}</td>
                                    <td>{{$course->no_of_lectures}}</td>
                                    <td></td> --}}
                                </tr>
                                <tr class="" style=""> 
                                    <td style="border-color: white;"></td>
                                    <td style="border-color: white;"></td>
                                </tr>
                                <tr class="tbl-border" style=""> 
                                    <td class="text-right" style="font-size:18px;border-color: white;" ><strong>Total</strong></td>
                                    <td style="text-align:center; font-size:18px;border-color: white;"><strong>{{$region->Currency}}&nbsp;{{number_format($coursePrice->pivot->price,2,'.',',')}} </strong></td>
                                </tr>
                                
                            </tbody>
                        </table>    
                            <!-- <hr class="table-hr"> -->
                        <!-- <table class="total-table"> -->
                           
                            <!-- <tr class="tbl-border"> 
                                <td colspan="1" class="text-right"><strong>Grand Total</strong></td>
                                <td style="text-align:center;"><strong>$845.00</strong></td>
                            </tr> -->
                        <!-- </table> -->
                    </div>
                </div>
                
                <div class="btnDiv">
                    <button type="button" class="prev-btn button-c" onclick="prevStep(1)">Previous</button>
                    <button type="button" id ="proceed_btn" class="next-btn button-c" onclick="nextStep(3)">Proceed to Payment</button>
                </div>    
            </div>

            <!-- Step 3: Payment Integration -->
            <div class="step" id="step3"> 
                <!-- <h3>Step 3:</h3> -->
                <div class="container p-0 step3Container">
    <div class="payment-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#card">Card</a>
            </li>

            @php
                $userRegion = App\Models\Region::find($region_id); // Get region based on region_id
            @endphp

            @if($userRegion && $userRegion->id == 8)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#online">Bank Transfer</a>
                </li>
            @endif
        </ul>

        <div class="tab-content">
            <!-- Card Payment Tab -->
            <div id="card" class="tab-pane fade show active">
                <div class="card px-4">
                    <div class="row gx-3 d-flex justify-content-center align-items-center">
                        <form action="{{$trxnData['PostURL']}}" method="POST">
                            @csrf
                            <input type="hidden" name="pp_MerchantID" value="{{ $trxnData['pp_MerchantID'] ?? '' }}">
                            <input type="hidden" name="pp_Version" value="{{ $trxnData['pp_Version'] ?? '' }}">
                            <input type="hidden" name="pp_TxnType" value="{{ $trxnData['pp_TxnType'] ?? '' }}">
                            <input type="hidden" name="pp_Language" value="{{ $trxnData['pp_Language'] ?? '' }}">
                            <input type="hidden" name="pp_SubMerchantID" value="{{ $trxnData['pp_SubMerchantID'] ?? '' }}">
                            <input type="hidden" name="pp_Password" value="{{ $trxnData['pp_Password'] ?? '' }}">
                            <input type="hidden" name="pp_TxnRefNo" id="pp_TxnRefNo" value="{{ $trxnData['pp_TxnRefNo'] ?? '' }}">
                            <input type="hidden" name="pp_Amount" value="{{ $trxnData['pp_Amount'] ?? '' }}">
                            <input type="hidden" name="pp_DiscountedAmount" value="{{ $trxnData['pp_DiscountedAmount'] ?? '' }}">
                            <input type="hidden" name="pp_DiscountBank" value="{{ $trxnData['pp_DiscountBank'] ?? '' }}">
                            <input type="hidden" name="pp_TxnCurrency" value="{{ $trxnData['pp_TxnCurrency'] ?? '' }}">
                            <input type="hidden" name="pp_TxnDateTime" id="pp_TxnDateTime" value="{{ $trxnData['pp_TxnDateTime'] ?? '' }}">
                            <input type="hidden" name="pp_TxnExpiryDateTime" id="pp_TxnExpiryDateTime" value="{{ $trxnData['pp_TxnExpiryDateTime'] ?? '' }}">
                            <input type="hidden" name="pp_BillReference" value="{{ $trxnData['pp_BillReference'] ?? '' }}">
                            <input type="hidden" name="pp_Description" value="{{ $trxnData['pp_Description'] ?? '' }}">
                            <input type="hidden" name="pp_ReturnURL" value="{{ $trxnData['pp_ReturnURL'] ?? '' }}">
                            <input type="hidden" name="pp_SecureHash" value="{{ $trxnData['pp_SecureHash'] ?? '' }}">
                            <input type="hidden" name="ppmpf_1" value="{{ $trxnData['ppmpf_1'] ?? '' }}">
                            <input type="hidden" name="ppmpf_2" value="{{ $trxnData['ppmpf_2'] ?? '' }}">
                            <input type="hidden" name="ppmpf_3" value="{{ $trxnData['ppmpf_3'] ?? '' }}">
                            <input type="hidden" name="ppmpf_4" value="{{ $trxnData['ppmpf_4'] ?? '' }}">
                            <input type="hidden" name="ppmpf_5" value="{{ $trxnData['ppmpf_5'] ?? '' }}">

                           <div class="d-flex justify-content-center"><img src="https://training.friendsitsolutions.com/public/front/img/jazz.png" class="h-25 img-fluid mt-4" style="height: 60px !important;!i;!;"></div>

                            <div class="d-flex justify-content-center row mt-3">
                                <img src="https://training.friendsitsolutions.com/public/front/img/visa.png" alt="visa">
                                <img src="https://training.friendsitsolutions.com/public/front/img/discover.png" alt="discover">
                                <img src="https://training.friendsitsolutions.com/public/front/img/master_card.png" alt="master_card">

                                <img src="https://training.friendsitsolutions.com/public/front/img/amarican_express.png" alt="amarican_express">
                            </div>

                            <div class="row d-flex justify-content-center">
                                <button type="submit" class="next-btn button-c my-4" id="pay-now-btn">Pay With Card</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Tab -->
            @if($userRegion && $userRegion->id == 8)
            <div id="online" class="tab-pane fade">
                <div class="card px-4">
                    <div class="row gx-3">
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Bank Name</p>
                                <p class="text mb-1">{{ $detail->bank_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Account Title</p>
                                <p class="text mb-1">{{ $detail->account_title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Account Number</p>
                                <p class="text mb-1">{{ $detail->account_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">IBAN No.</p>
                                <p class="text mb-1">{{ $detail->iban }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <p class="f-600 mb-1 text-dark">Upload Receipt</p>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" accept=".png, .jpeg, .jpg" required>
                                <label class="custom-file-label" for="customFile">Choose pic</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Instructions</p>
                                <p class="mb-4">{{ $detail->instructions }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Button Row -->
    <div class="btnDiv">
        <button type="button" class="prev-btn button-c" onclick="prevStep(2)">Previous</button>
        <button type="button" class="next-btn button-c" id="save-btn" style="display:none;">Save</button>
    </div>
</div>

                 
            
        </div>

        <div class="step" id="step4">
            <div class="checkout-container2">
                <div class="card px-4 border-0">
                    <div class="main">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                        
                        <div class="text congrats">
                            <h2>Congratulations!</h2>
                            <p>Thanks Mr./Mrs. <span class="shown_name"></span> Your course registration has been submitted successfully. We will contact you soon for further details.</p>
                        </div>
                        <div class="text-center">
                            <a class="register-btn browse-courseBtn" href="{{route('home_index')}}" > Browse Courses</a>
                        </div>
                    </div>               
                </div>
            </div>
        </div>
        </div>
 @include('Frontend.include.footer')
 @include('Frontend.include.script')


<script type="text/javascript">
    document.querySelectorAll('.type-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.type-checkbox').forEach((cb) => {
                    if (cb !== this) cb.checked = false;
                });
            }
        });
    });

    document.querySelectorAll('.type-checkbox2').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.type-checkbox2').forEach((cb) => {
                    if (cb !== this) cb.checked = false;
                });
                
                // Show/Hide learnerDtl or companyDtl based on the checkbox value
                if (this.value === 'individual') {  // Individual
                    document.querySelector('.learnerDtl').style.display = 'block';
                    document.querySelector('.companyDtl').style.display = 'none';
                } else if (this.value === 'company') {  // Company
                    document.querySelector('.learnerDtl').style.display = 'none';
                    document.querySelector('.companyDtl').style.display = 'block';
                }
            }
        });
    });
</script>

<script type="text/javascript">
// Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

</script>
<!-- Blade View -->
<script type="text/javascript">

    // Pass registration data to JS as JSON
    var registrations = @json($registration ?? []);  // Ensure it's always an array
  
    // Extract emails and phone numbers for validation
    var existingEmails = registrations.map(function(registration) {
        return registration.email;
    });
  
    var existingPhones = registrations.map(function(registration) {
        return registration.phone;
    });

    console.log(registrations);
  
    // Validate function
    function nextStep(step) {
        var isValid = true;
  
        // Get form input values
        var fname = document.getElementById("fname").value.trim();
        var lname = document.getElementById("lname").value.trim();
        var email = document.getElementById("email").value.trim();
        var number = document.getElementById("number").value.trim();
  
        // Email and phone validation patterns
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var phonePattern = /^\d{11}$/;
  
        // Validate First Name
        if (!fname) {
            document.getElementById("fname-error").textContent = "First Name is required";
            document.getElementById("fname-error").style.display = "block";
            isValid = false;
        } else {
            document.getElementById("fname-error").style.display = "none";
        }
  
        // // Validate Last Name
        if (!lname) {
            document.getElementById("lname-error").textContent = "Last Name is required";
            document.getElementById("lname-error").style.display = "block";
            isValid = false;
        } else {
            document.getElementById("lname-error").style.display = "none";
        }
  
        // Validate Email
         if (!email) {
             document.getElementById("email-error").textContent = "Email is required";
             document.getElementById("email-error").style.display = "block";
             isValid = false;
         }
         else {
            document.getElementById("email-error").style.display = "none";
        }
    
        // Validate Phone Number
         if (!number) {
             document.getElementById("number-error").textContent = "Phone number is required";
             document.getElementById("number-error").style.display = "block";
             isValid = false;
         //} else if (!phonePattern.test(number)) {
         //    document.getElementById("number-error").textContent = "Please enter a valid 11-digit phone number";
         //    document.getElementById("number-error").style.display = "block";
         //    isValid = false;
         //} else if (existingPhones.includes(number)) {
         //    document.getElementById("number-error").textContent = "This phone number is already registered.";
         //    document.getElementById("number-error").style.display = "block";
         //    isValid = false;
         } else {
             document.getElementById("number-error").style.display = "none";
        }
  
        // If valid, proceed to the next step
        if (isValid) {
            var steps = document.getElementsByClassName("step");
            for (var i = 0; i < steps.length; i++) {
                steps[i].classList.remove("active");
            }
            document.getElementById("step" + step).classList.add("active");
            updateProgress(step);
        }
    }
  </script>
  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="text/javascript">
document.getElementById('save-btn').addEventListener('click', function () {
    // Get the uploaded file and validate
 
    const fileInput = document.getElementById('customFile');
    const file = fileInput.files[0];

    if (!file) {
        Swal.fire({
                icon: 'error',
                title: 'File not found',
                text: 'Please upload an image'
            });
        return;
    }

    // Prepare FormData to send via AJAX
    const formData = new FormData();
    formData.append('receipt', file); // Append the file

    // Collect user data from form fields
    formData.append('course_id', document.querySelector('input[name="course_id"]').value);
    formData.append('region_id', document.querySelector('input[name="region_id"]').value);
    formData.append('fname', document.getElementById('fname').value);
    formData.append('lname', document.getElementById('lname').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('phone', document.getElementById('number').value);
    formData.append('company_name', document.getElementById('c_name').value);
    formData.append('designation', document.getElementById('designation').value);
    formData.append('price', document.getElementById('price').value);
    formData.append('bill_ref', document.getElementById('billRef').value);

    formData.append('comments', document.getElementById('comments').value);
    let countrySelect = document.getElementById('country_code');
let selectedOption = countrySelect.options[countrySelect.selectedIndex];

// Extract values    
let countryCode = selectedOption.value; // Example: "+92"
let countryShortName = selectedOption.getAttribute('data-short'); // Example: "PK"
let countryName = selectedOption.getAttribute('data-region'); // Example: "Pakistan"

// Append all values in a single string
formData.append('country_code', countryCode + ',' + countryShortName + ',' + countryName);
  
    formData.append('payment_type','Bank Transfer');
   

    const gender = document.querySelector('input.type-checkbox:checked');
    if (gender) formData.append('gender', gender.value);

    const userType = document.querySelector('input.type-checkbox2:checked');
    if (userType) formData.append('user_type', userType.value);

    const learnerType = document.querySelector('input[name="region"]:checked');
    if (learnerType) formData.append('learner_type', learnerType.value);

    console.log(formData);

    const saveButton = document.getElementById('save-btn');
    saveButton.disabled = true;

    fetch('{{ route("registeration.save") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            saveButton.disabled = false;

            if (data.success) {

                // Populate the success step with user information
                document.querySelector('.shown_name').textContent = `${document.getElementById('fname').value} ${document.getElementById('lname').value}`;

                // Show the success message step
                document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
                document.getElementById('step4').classList.add('active');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'There was an error saving your details. Please try again.'
                });
            }
        })
        .catch(error => {
            saveButton.disabled = false;
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'There was an error processing your request. Please try again later.'
            });
        });
});


</script>
<script type="text/javascript"> 
document.getElementById('proceed_btn').addEventListener('click', function () {
    // // Get the uploaded file and validate
    // const fileInput = document.getElementById('customFile');
    // const file = fileInput.files[0];

    // if (!file) {
    //     Swal.fire({
    //             icon: 'error',
    //             title: 'File not found',
    //             text: 'Please upload an image'
    //         });
    //     return;
    // }

    // Prepare FormData to send via AJAX
    const formData = new FormData();
    // formData.append('receipt', file); // Append the file

    // Collect user data from form fields
    formData.append('course_id', document.querySelector('input[name="course_id"]').value);
    formData.append('region_id', document.querySelector('input[name="region_id"]').value);
    formData.append('fname', document.getElementById('fname').value);
    formData.append('lname', document.getElementById('lname').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('phone', document.getElementById('number').value);
    formData.append('company_name', document.getElementById('c_name').value);
    formData.append('designation', document.getElementById('designation').value);
    formData.append('price', document.getElementById('price').value);
    formData.append('bill_ref', document.getElementById('billRef').value);
    formData.append('comments', document.getElementById('comments').value);
    let countrySelect = document.getElementById('country_code');
let selectedOption = countrySelect.options[countrySelect.selectedIndex];

// Extract values
let countryCode = selectedOption.value; // Example: "+92"
let countryShortName = selectedOption.getAttribute('data-short'); // Example: "PK"
let countryName = selectedOption.getAttribute('data-region'); // Example: "Pakistan"

// Append all values in a single string
formData.append('country_code', countryCode + ',' + countryShortName + ',' + countryName);

    formData.append('payment_type','card'); 


    const gender = document.querySelector('input.type-checkbox:checked');
    if (gender) formData.append('gender', gender.value);

    const userType = document.querySelector('input.type-checkbox2:checked');
    if (userType) formData.append('user_type', userType.value);

    const learnerType = document.querySelector('input[name="region"]:checked');
    if (learnerType) formData.append('learner_type', learnerType.value);

    console.log(formData);

    const saveButton = document.getElementById('save-btn');
    saveButton.disabled = true;

    fetch('{{ route("registeration.save") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            saveButton.disabled = false;

            if (data.success) {

                // Populate the success step with user information
                // document.querySelector('.shown_name').textContent = `${document.getElementById('fname').value} ${document.getElementById('lname').value}`;

                // Show the success message step
                document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
                document.getElementById('step3').classList.add('active');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'There was an error saving your details. Please try again.'
                });
            }
        })
        .catch(error => {
            saveButton.disabled = false;
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'There was an error processing your request. Please try again later.'
            });
        });
});


</script>
<script>
$(document).ready(function() {
    $('.select2-country_code_s').select2({
        width: '100%',
        placeholder: 'Select a country',
        allowClear: true
    });
});
</script>


