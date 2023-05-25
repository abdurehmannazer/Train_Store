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
