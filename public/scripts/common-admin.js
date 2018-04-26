// Checkbox js
function checkbox()
{
  var checkboxes = document.querySelectorAll('input.thing'),
  checkall = document.getElementById('checkall');
  for(var i=0; i<checkboxes.length; i++) {
    checkboxes[i].onclick = function() {
      var checkedCount = document.querySelectorAll('input.thing:checked').length;

      checkall.checked = checkedCount > 0;
      checkall.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
    }
  }
  checkall.onclick = function() {
    for(var i=0; i<checkboxes.length; i++) {
      checkboxes[i].checked = this.checked;
    }
  }
}

// Filter Form Status
function filterFormStatus()
{
  // filterForm team status
  var toggle = $('#enable');
  var toggleNext = $('#disable');
  if(toggle.hasClass("success"))
  {
    toggle.removeClass("fa-unlock-alt");
    toggle.removeClass("success");
    toggle.addClass("fa-lock");
    toggle.addClass("red");

    var delay = 700; //1 second
    setTimeout(function()
    {
      //your code to be executed after 1 second
      toggleNext.addClass("fa-unlock-alt");
      toggleNext.addClass("success");
      toggleNext.removeClass("fa-lock");
      toggleNext.removeClass("red");
    }, delay);
  }
  else
  {
    toggle.removeClass("fa-lock");
    toggle.removeClass("red");
    toggle.addClass("fa-unlock-alt");
    toggle.addClass("success");

    var delay = 700; //1 second
    setTimeout(function()
    {
      //your code to be executed after 1 second
      toggleNext.addClass("fa-lock");
      toggleNext.addClass("red");
      toggleNext.removeClass("fa-unlock-alt");
      toggleNext.removeClass("success");
    }, delay);
  }
}