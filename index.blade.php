@include('Frontend.include.head')
@include('Frontend.include.header')
@php
use App\Models\Region;  // You only need to use the specific model
$regions = Region::orderBy('Region', 'ASC')->get(); 
 
 
 // Retrieve regions ordered alphabetically
@endphp
 <!-- slider-start -->
<div class="slider-area">
    <div class="page-title">
        <div class="single-slider slider-height slider-height-breadcrumb d-flex align-items-center">
            <!-- <img src="{{asset('front/img/banner7.jpg')}}" alt="" class="img-fluid"> -->
             <div class="image-container">
                 <img src="{{asset('front/img/banner-450.jpg')}}" alt="" class="img-fluid banrImg">
             </div>
            <!-- <img src="{{asset('front/img/benner3.webp')}}" alt="" class="img-fluid"> -->
            <div class="slider-content slider-content-breadcrumb text-center">
                <h1 class="white-color f-700 top-title">Online Courses</h1>
                <h1 class="white-color f-700 sub-title">Empowering Your IT Journey</h1>
            </div>
        </div>
    </div>
</div>
<!-- slider-end -->


    <!-- courses start -->
    <div class="courses-area courses-bg-height gray-bg pt-70 pb-40" id="courseSection">
        <div class="container">
            <div class="courses-list">
                <div class="row"  id="course-container">
                    @include('partials.course-card')
                </div> 
            </div>


            <!-- <select id="regionSelect2">
    <option value="Australia">Australia</option>
    <option value="USA">USA</option>
    <option value="China">China</option>
    <option value="UK">UK</option>
    <option value="India">India</option>
    <option value="Pakistan">Pakistan</option>
</select> -->
        </div>
    </div>
    <!-- courses end -->
    
  
 

 
 
  

    <!-- Modal for region selection -->
{{-- <div class="modal fade" id="regionModal" tabindex="-1" aria-labelledby="regionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="regionModalLabel">Select Your Country</h5>
                <!-- <button type="button" class="btn-close" aria-label="Close" id="closeModalBtn"><span aria-hidden="true">&times;</span></button> -->
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Side: Image -->
                    <div class="col-md-6">
                        <img src="{{asset('front/img/courses/coursesthumb008.jpg')}}" alt="Region Image" class="img-fluid" id="regionModalImage">
                    </div>
                    <!-- Right Side: Content -->
                    <div class="col-md-6">
                        <form action="{{ route('set_region') }}" method="POST"> 
                            @csrf
                            <div class=" mb-md-4 mb-1">
                                <p class="region-tagline mb-4">
                                    "Welcome to FIS Training Center, an innovative online learning platform dedicated to empowering individuals with the knowledge and skills they need to thrive in today's fast-paced world."
                                </p>

                                <label for="regionSelect" class="form-label">Please choose your country to continue with the course:</label>
                                <select id="regionSelect" name="region" class="form-select mb-md-3 mb-0 custom-dropdown select2" required>
                                    <option value="">Select a country</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->Region }}</option>
                                    @endforeach
                                </select>

                                <!-- <select id="regionSelect" name="region" class="form-select mb-md-3 mb-0 custom-dropdown" required>
                                    <option value="">Select a region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->Region }}</option>  
                                    @endforeach
                                </select> -->



                                

                                
                                <!-- Alert message if no region is selected -->
                                <div class="alert alert-danger mt-2" id="requiredAlert" style="display: none;">
                                    Please select a country before proceeding!
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" id="closeModalBtnFooter">Close</button> -->
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
            </form>

        </div>
        
    </div>
</div> --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script>
    $(document).ready(function() {
        // Use the region data passed from Blade template (region names mapped to IDs)
        var regionData = @json($regions->pluck('id', 'Region'));

        // Log region data to ensure it's available
        console.log('Region Data:', regionData);

        const requestOptions = {
            method: "GET",
            redirect: "follow"
        };

        var API_KEY = '46f18be6ad3c41cc81dacb4c5fc7f43e';  // Your API key

        // Fetch country name using the geolocation API
        fetch(`https://api.ipgeolocation.io/ipgeo?apiKey=${API_KEY}`, requestOptions)
            .then((response) => response.json()) // Parse the JSON response
            .then((result) => {
                console.log(result);  // Log the result to see the full response

                var userCountryName = result.country_name;  // Get the country name from the API
                console.log('User Country: ' + userCountryName);  // Log the country name

                // Check if the country name exists in the regionData
                if (userCountryName && regionData[userCountryName]) {
                    // If it matches, get the region ID from regionData
                    var regionId = regionData[userCountryName];
                    console.log('Region ID for ' + userCountryName + ': ', regionId);

                    // Send the region ID to the server to store it in the session
                    $.ajax({
                        url: '{{ route('set_region') }}',  // Route to store the region
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',  // CSRF token for security
                            region_id: regionId           // Send the region ID
                        },
                        success: function(response) {
                            console.log('Region ID sent to the server and session updated');
                            // Optionally, fetch related courses or data
                            fetchCoursesByRegion(regionId);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error sending region ID:', error);
                        }
                    });
                } else {
                    console.warn("Country name not found in response or no matching region found.");
                }
            })
            .catch((error) => console.error('Error:', error));

        // Function to fetch courses related to the selected region
        function fetchCoursesByRegion(regionId) {
            $.ajax({
                url: '{{ route('fetch_courses_by_region') }}',  // Define the route to fetch courses
                type: 'GET',
                data: {
                    region_id: regionId
                },
          success: function(response) {
    console.log('Courses fetched for region: ', response);
    // You can use the response data to update the page with the courses
    updateCoursesList(response);

    // Check if we are already on the 'home_index' route, if not, proceed with redirection
    if (window.location.pathname !== '{{ route('home_index') }}') {
        // Redirect to the index page
        window.location.href = '{{ route('home_index') }}';  // This will trigger the redirection
    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching courses:', error);
                }
            });
        }
        

        // Function to update the courses list on the page
        function updateCoursesList(courses) {
            var coursesList = $('#coursesList'); // Assuming you have a container with id `coursesList`
            coursesList.empty();  // Clear existing courses

            // Loop through courses and display them
            courses.forEach(function(course) {
                coursesList.append('<li>' + course.name + ' - ' + course.description + '</li>');
            });
        }
    });
</script> --}}
<script>
$(document).ready(function() {
    var regionData = @json($regions->pluck('id', 'Region'));  // Map region name to region id

    console.log('Region Data:', regionData);

    const requestOptions = {
        method: "GET",
        redirect: "follow"
    };

    var API_KEY = '46f18be6ad3c41cc81dacb4c5fc7f43e';

    // Check if the regionId is already in session when the page loads
    var regionIdFromSession = @json(session('selected_region_id'));
    console.log('Session Region ID:', regionIdFromSession);

    if (regionIdFromSession) {
        // If there's already a region in session, fetch courses directly
        fetchCoursesByRegion(regionIdFromSession);
    } else {
        // If no region in session, get user's region via geolocation
        fetch(`https://api.ipgeolocation.io/ipgeo?apiKey=${API_KEY}`, requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log('User Location:', result);

                var userCountryName = result.country_name;
                console.log('User Country:', userCountryName);

                if (userCountryName && regionData[userCountryName]) {
                    var regionId = regionData[userCountryName];
                    console.log('Detected Region ID:', regionId);

                    // If region is detected, set it in session and fetch courses
                    $.ajax({
                        url: '{{ route('set_region') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            region_id: regionId
                        },
                        success: function(response) {
                            console.log('Session Updated! Fetching Courses...');
                            fetchCoursesByRegion(regionId);
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating session:', error);
                        }
                    });
                } else {
                    console.warn("Region not found in database.");
                }
            })
            .catch(error => console.error('Geolocation API Error:', error));
    }

    function fetchCoursesByRegion(regionId) {
        $.ajax({
            url: '{{ route('fetch_courses_by_region') }}',
            type: 'GET',
            data: { region_id: regionId },
            success: function(response) {
                console.log('Courses Loaded:', response);
                updateCoursesList(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching courses:', error);
            }
        });
    }

    function updateCoursesList(courses) {
        var coursesList = $('#coursesList');
        coursesList.empty();  // Clear previous courses list

        courses.forEach(function(course) {
            coursesList.append('<li>' + course.name + ' - ' + course.description + '</li>');
        });
    }
});
</script>

 





@include('Frontend.include.footer')
@include('Frontend.include.script')

 
  
{{-- @if(!session()->has('selected_region_id'))
    <script>
        $(document).ready(function() {
            $('#regionModal').modal('show'); // Correctly show the modal
        });



       
    </script>
@endif --}}