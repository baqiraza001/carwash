$(document).ready(function(){
	$('body').on('click' ,'.cancel-me', function(){
		window.location = admin_url + 'stock_management/checkin';
	});

	// var products = [];
 //    var action= admin_url + 'stock_management/checkin/get_products';
 //    $.ajax(action, {
 //      type: 'POST',
 //      data: [],

 //    }).done(function(data){
 //    	data = JSON.parse(data);

 //      for (var i = 0; i < data.length; i++) {
 //        console.log(data[i].id);
 //        products[i]= [];
 //        products[i]['id'] = data[i].id;
 //        products[i]['product'] = data[i].product;
 //      }
 //      autocomplete(document.getElementById("add_item"), products);
 //    });
});

function autocomplete(inp, arr) {

  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var div, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      div = document.createElement("DIV");
      div.setAttribute("id", this.id + "autocomplete-list");
      div.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(div);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        var id = arr[i]['id'];
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i]['product'].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i]['product'].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i]['product'].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i]['product'] + "' data-id='" + arr[i]['id'] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              document.getElementById("add_item_id").value = this.getElementsByTagName("input")[0].getAttribute("data-id");
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          div.appendChild(b);
        }
      }
  });

  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}