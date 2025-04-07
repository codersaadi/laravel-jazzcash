@include('Frontend.include.head')
@include('Frontend.include.header')

<nav class="breadcrunb" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{route('home_index')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Now</li>
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
    <form method="POST" action="{{ route('save.newregistration') }}" id="checkout-form">
    @csrf
    <input type="hidden" name="product_id" value="{{ request()->input('product_id') }}">
    <input type="hidden" name="amount" value="{{ request()->input('amount') }}">
    <input type="hidden" name="source_site_url" value="{{ request()->input('source_site_url') }}">
    <input type="hidden" name="duration" value="{{ request()->input('duration') }}">
    
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="name" value="{{ request()->input('name', '') }}" placeholder="Enter your first name" required>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>
            </div>
        </div>
        <div class="col-md-12 col-12">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ request()->input('email', '') }}" placeholder="Enter your email" required>
            </div>
        </div>
    </div>
    <div class="btnDiv">
        <button type="submit" class="next-btn button-c">Submit</button>
    </div>
</form>
        <div class="tab-content">
    <!-- Card Payment Tab -->
    <div id="card" class="tab-pane fade show active">
        <div class="card px-4">
            <div class="row gx-3 d-flex justify-content-center align-items-center">
              <form action="{{ env('JAZZ_POSTURL') }}" method="POST">
    @csrf
    
    <!-- Required Parameters -->
    <input type="hidden" name="pp_Version" value="1.1">
    <input type="hidden" name="pp_TxnType" value="MPAY">
    <input type="hidden" name="pp_Language" value="EN">
    <input type="hidden" name="pp_MerchantID" value="{{ env('JAZZ_MERCHANT_ID') }}">
    <input type="hidden" name="pp_Password" value="{{ env('JAZZ_PASSWORD') }}">
    <input type="hidden" name="pp_TxnRefNo" value="{{ 'TXN' . time() . rand(1000,9999) }}">
    <input type="hidden" name="pp_Amount" value="{{ request()->input('amount') * 100 }}">
    <input type="hidden" name="pp_TxnCurrency" value="PKR">
    <input type="hidden" name="pp_TxnDateTime" value="{{ now()->format('YmdHis') }}">
    <input type="hidden" name="pp_BillReference" value="BILL{{ now()->format('YmdHis') }}">
    <input type="hidden" name="pp_Description" value="Payment for Product {{ request()->input('product_id') }}">
    <input type="hidden" name="pp_ReturnURL" value="{{ route('payment.return') }}">
    <input type="hidden" name="pp_TxnExpiryDateTime" value="{{ now()->addDays(1)->format('YmdHis') }}">

    <!-- Optional Parameters -->
    <input type="hidden" name="pp_SubMerchantID" value="">
    <input type="hidden" name="pp_DiscountedAmount" value="">
    <input type="hidden" name="pp_DiscountBank" value="">
    <input type="hidden" name="ppmpf_1" value="">
    <input type="hidden" name="ppmpf_2" value="">
    <input type="hidden" name="ppmpf_3" value="">
    <input type="hidden" name="ppmpf_4" value="">
    <input type="hidden" name="ppmpf_5" value="">

    <!-- Secure Hash (Must be the LAST parameter) -->
    @php
        $hashData = [
            env('JAZZ_HASHKEY'),
            request()->input('amount') * 100,
            '',
            'BILL'.now()->format('YmdHis'),
            'Payment for Product '.request()->input('product_id'),
            'EN',
            env('JAZZ_MERCHANT_ID'),
            env('JAZZ_PASSWORD'),
            '',
            route('payment.return'),
            'PKR',
            now()->format('YmdHis'),
            now()->addDays(1)->format('YmdHis'),
            'TXN'.time().rand(1000,9999),
            'MPAY',
            '1.1'
        ];
        $sortedString = implode('&', array_filter($hashData));
        $secureHash = hash_hmac('sha256', $sortedString, env('JAZZ_HASHKEY'));
    @endphp
    <input type="hidden" name="pp_SecureHash" value="{{ $secureHash }}">

    <!-- Payment Logos -->
    <div class="d-flex justify-content-center">
        <img src="{{ asset('front/img/jazz.png') }}" style="height:60px">
    </div>

    <div class="d-flex justify-content-center row mt-3">
        <img src="{{ asset('front/img/visa.png') }}" alt="Visa" style="height:30px">
        <img src="{{ asset('front/img/master_card.png') }}" alt="MasterCard" style="height:30px">
        <!-- Add other card logos as needed -->
    </div>

    <div class="row d-flex justify-content-center">
        <button type="submit" class="btn btn-primary my-4">Pay Now</button>
    </div>
</form>
            </div>
        </div>
    </div>
</div>

            <!-- Bank Transfer Tab -->
            <div id="online" class="tab-pane fade">
                <div class="card px-4">
                    <div class="row gx-3">
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Bank Name</p>
                                <p class="text mb-1">Example Bank</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Account Title</p>
                                <p class="text mb-1">Example Account Title</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">Account Number</p>
                                <p class="text mb-1">123456789</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-flex flex-column mb-3">
                                <p class="f-600 mb-1 text-dark">IBAN No.</p>
                                <p class="text mb-1">PK00EXAMPLEIBAN0000</p>
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
                                <p class="mb-4">Please upload the receipt after making the payment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 @include('Frontend.include.footer')
 @include('Frontend.include.script')
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading indicator
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

    const formData = {
        name: document.getElementById('fname').value,
        email: document.getElementById('email').value,
        product_id: document.querySelector('input[name="product_id"]').value,
        amount: document.querySelector('input[name="amount"]').value,
        source_site_url: document.querySelector('input[name="source_site_url"]').value,
        duration: document.querySelector('input[name="duration"]').value,
    };

    fetch("{{ route('jazzcash.initiate') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Create a hidden form to submit to JazzCash
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = data.payment_url;
            form.style.display = 'none';

            // Add all parameters as hidden inputs
            Object.keys(data.params).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data.params[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        } else {
            throw new Error(data.message || 'Payment initiation failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message to user
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger mt-3';
        errorDiv.textContent = 'Error: ' + error.message;
        e.target.appendChild(errorDiv);
        
        // Reset button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit';
    });
});
</script>