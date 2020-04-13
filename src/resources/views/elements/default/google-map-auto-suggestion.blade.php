@php
    $validators = isset($validators) ? $validators : [];
    $elementId = isset($id) ? $id : str_random(8);
@endphp

<div>
    <h4 class="control-label">@if(in_array('required', $validators))<span tred>*</span> @endif {{isset($title) ? $title : ''}}</h4>
    <div>
        <input id="{{$elementId}}" type="text" name="{{isset($name) ? $name : ''}}" value="{{isset($value) ? $value : ''}}" placeholder="{{isset($placeholder) ? $placeholder : trans('lang.enter_your_address')}}" class="pinput" {{in_array('required', $validators) ? 'required' : ''}}  onFocus="geolocate{{$elementId}}()"  type="text" autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="off"  />
        <input id="{{$elementId}}_country" type="hidden" name="{{$name}}_country" />
        <input id="{{$elementId}}_province" type="hidden" name="{{$name}}_province" />
        <input id="{{$elementId}}_district" type="hidden" name="{{$name}}_district" />
    </div>
</div>

<script>
    // This sample uses the Autocomplete widget to help the user select a
    // place, then it retrieves the address components associated with that
    // place, and then it populates the form fields with those details.
    // This sample requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script
    // src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    
    var placeSearch, autocomplete;
    let suggestionElement = document.getElementById('{{$elementId}}');
    
    function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('{{$elementId}}'), {types: ['geocode']});
        
        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(['address_components', 'geometry']);
        
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }
    
    function fillInAddress() {
        let addressField = {
            street_number: 'street_number',
            route: 'street',
            locality: 'city',
            administrative_area_level_1: 'province',
            country: 'country',
            postal_code: 'postal_code',
            administrative_area_level_2: 'district'
        };
        
        // Get the place details from the autocomplete object.
        let place = autocomplete.getPlace();
        
        let result = {
            address : suggestionElement.value,
        };

        if(place.geometry && place.geometry.location){
            result.lat = place.geometry.location.lat();
            result.lng = place.geometry.location.lng();
        }
        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        if(place.address_components){
            for (let i = 0; i < place.address_components.length; i++) {
                let addressType = place.address_components[i].types[0];

                if(addressField[addressType]){
                    let val = place.address_components[i]['short_name'];
                    result[addressField[addressType]] = val;
                }
            }
        }
        else{
            console.log(`There is no address_components`);
        }

        if(!result.district && result.city){
            result.district = result.city;
        }
        console.log(result);

        document.getElementById('{{$elementId}}_country').value = result.country;
        document.getElementById('{{$elementId}}_province').value = result.province;
        document.getElementById('{{$elementId}}_district').value = result.district;
        document.getElementsByName('lat')[0].value = result.lat + "";
        document.getElementsByName('lng')[0].value = result.lng + "";
    }
    
    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate{{$elementId}}() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle(
                {center: geolocation, radius: position.coords.accuracy});
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>


@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{isset($google_api_key) ? $google_api_key : ''}}&libraries=places&callback=initAutocomplete&language=vi" async defer></script>
@endpush