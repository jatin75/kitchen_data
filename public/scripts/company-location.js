// Formatted Location Address
$.getScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyALk11JxZyIqx5v68K-CjD69QPRmtWGmaI&libraries=places', function()
{
  google.maps.event.addDomListener(window, 'load', initialize);
  var componentForm =
  {
   // street_number: 'short_name',
   // route: 'long_name',
   locality: 'long_name',
   administrative_area_level_1: 'long_name',
   // administrative_area_level_2: 'long_name',
   country: 'long_name',
   postal_code: 'short_name',
   sublocality_level_1 : 'long_name'
 };
 function initialize()
 {
  var input = document.getElementById('locationAddress');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.addListener('place_changed', function ()
  {
    location_val='';
    var place = autocomplete.getPlace();
    var address_components = place.address_components;
    //console.log(address_components);
    for (var i = 0; i < address_components.length; i++)
    {
      if(address_components[i].types.includes('locality'))
      {
        // console.log(address_components[i].types);
        $('#city').val(address_components[i].long_name);
      }
      if(address_components[i].types.includes('administrative_area_level_1'))
      {
        $('#state').val(address_components[i].long_name);
      }
      if(address_components[i].types.includes('postal_code'))
      {
        $('#zipcode').val(address_components[i].long_name);
      }
      if(address_components[i].types.includes('sublocality_level_1'))
      {
        $('#subAddress').val(address_components[i].long_name);
      }
      /*if(address_components[i].types.includes('country'))
      {
        $('#hide_location_country').val(address_components[i].long_name);
      }*/
    } 
    var location= $('#locationAddress').val();
    var arr = location.split(',');
    var location = arr[0] +','+ arr[1];
    $('#locationAddress').val(location);
    // console.log(location);
  });
}


});