// run this script when the site is fully loaded
document.addEventListener('DOMContentLoaded', function () {
	
	// grab our elements
	var mod_locations_list = document.getElementById("mod_locations_list");
	var location_default = document.getElementById("state_not_found");
	
	// add an event listener to fire our function when there is a keyup on our input
	mod_locations_list.addEventListener("keyup", onKeyUp);
	
	// the function to fire when a keyup event is fired
	function onKeyUp() {
	
		// if we have exactly 5 digits inside the zip input
		var zip_input = document.getElementById("locations_zip_input");
		if(zip_input.value.length == 5) {
			
			
			var x = document.getElementsByClassName("location_full");
			var i;
			var found = 0;
			
			// loop through every location
			for (i = 0; i < x.length; i++) {
				
				// get the hidden zip field
				var hidden_zip = x[i].getElementsByClassName("hidden_zip");
				
				// if the hidden zip contains the searched zip
				if(hidden_zip[0].innerHTML.indexOf(zip_input.value) !== -1) {
					x[i].style.display = 'block';
					found = 1
				}
				
			}
			// we found a zip, hide the default address
			if(found == 1){
				location_default.style.display = 'none';
			}
			// nothing found, just show our default address
			else {
				location_default.style.display = 'block';
			}
			
		}
		// there was a keyup event but it has more or less than 5 digits
		else {
		
			// hide our default location
			location_default.style.display = 'none';
			
			// hide every location
			var x = document.getElementsByClassName("location_full");
			var i;
			for (i = 0; i < x.length; i++) {
				x[i].style.display = 'none';
			}
		}
	}
  
}, false);
