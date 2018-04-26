// Formatted Location Address
$.getScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ8rIABiQwNVunKKcri49rOtjNHIFzRCA&libraries=places', function()
{
  google.maps.event.addDomListener(window, 'load', initialize);
  var componentForm =
  {
   // street_number: 'short_name',
   // route: 'long_name',
   locality: 'long_name',
   administrative_area_level_1: 'short_name',
   country: 'long_name',
   // postal_code: 'short_name'
 };
 function initialize()
 {
  // console.log(status);
  var input = document.getElementById('teamLocation');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.addListener('place_changed', function ()
  {
    location_val='';
    var place = autocomplete.getPlace();
        //var address = place.formatted_address;
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        // console.log(place);
        var address_components = place.address_components;
        for (var i = 0; i < address_components.length; i++)
        {
          var address = address_components[i].types[0];
          if (componentForm[address])
          {
            var val = place.address_components[i][componentForm[address]];
            location_val+=val+",";
          }
          if(address_components[i].types.includes('country'))
          {
            $('#hide_location_country').val(address_components[i].long_name);
            // console.log(address_components[i].long_name);
          }
        }
        location_val=location_val.slice(0,-1);
        // console.log(location_val);
        $('#teamLocation').val(location_val);
      });
}
});