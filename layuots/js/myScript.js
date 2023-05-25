$(function () {
    

  'use strict' 



  $('[placeholder]').focus(function () {
      $(this).attr('data-text', $(this).attr('placeholder'));
      $(this).attr('placeholder', '');
  }).blur(function () {
      $(this).attr('placeholder' , $(this).attr('data-text'))
  })

})

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const appendAlert = (message, type) => {
  const wrapper = document.createElement('div')
  wrapper.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')

  alertPlaceholder.append(wrapper)
}

const alertTrigger = document.getElementById('liveAlertBtn')
if (alertTrigger) {
  alertTrigger.addEventListener('click', () => {
    appendAlert('Nice, you triggered this alert message!', 'success')
  })
}


$('input').each(function () {
  if ($(this).attr('required')) {
    $(this).after('<span class="astrics">*</span>');
  }
})




var passField = $('.password')
$('.fa-eye').hover(  () => {
  passField.attr('type', 'text');
},  () => {
  passField.attr('type' , 'password')
})


$('.confirm').click(() => {
  return confirm("Are You sure Delete?")
})



$(".navIcon").click(function(){
  $(".collapse.navbar-collapse").toggleClass("show");
});



catName = document.querySelectorAll(".catName");
catName.forEach(ele => {
  ele.onclick = () => {
    nextEle = ele.nextElementSibling;
    if (nextEle.classList.contains("d-none")) {
      nextEle.classList.replace("d-none" , "d-block");
    } else {
      nextEle.classList.add("d-none");
    }
  }  
});
  


$('.iconToggle').click( function ()  {
  $(this).toggleClass('selected').parent().next(".list-group").fadeToggle(50)
  if ($(this).hasClass("selected")) {
    $(this).html('<i class="fa-solid fa-minus"></i>')
  } else {
    $(this).html('<i class="fa-solid fa-plus"></i>')
  }  
})




// -------------------------------Store Page login

// my logic 
// toggle forms 
// $(".Login").click(function () {
//   if (!$(this).hasClass("active")) {
//     $(this).toggleClass("active");
//     $(".login_form").toggleClass("d-none")
//     $(".singup_form").addClass("d-none")
//     $(".Signup").removeClass("active")
//   }
// });
// $(".Signup").click(function () {
//   if (!$(this).hasClass("active")) {
//     $(this).toggleClass("active");
//     $(".singup_form").toggleClass("d-none")
//     $(".login_form").addClass("d-none")
//     $(".Login").removeClass("active")
//   }
// });

// elzero logic 
$(".container h1 span").click(function () {
  $(this).addClass("active").siblings().removeClass("active");
  $(".logPage form").hide();

  $('.' + $(this).data('form')).fadeIn(100);

})



// model
const exampleModal = document.getElementById('exampleModal')
if (exampleModal) {
  exampleModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const recipient = button.getAttribute('data-bs-whatever')
    console.log(recipient);
    console.log(button);
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalBodyInput = exampleModal.querySelector('.modal-body input')

    // modalTitle.textContent = `+  Add New Item ${recipient}`
    // modalBodyInput.value = recipient
  })
}



