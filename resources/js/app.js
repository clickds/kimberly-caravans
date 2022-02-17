require('./bootstrap');

window.addEventListener('load', function(){
  document.querySelectorAll('.tab-listing__list').forEach(function(node, index) {
  	new Glider(node, {
  		slidesToShow: 3,
  		slidesToScroll: 1,
  		draggable: true
  	});
  })
})

$(document).ready(function() {
	$('.tab-listing__tabs__item').on('click', function() {
		let id = $(this).attr('data-id');
	    $('.tab-listing__tabs__item').removeClass('tab-listing__tabs__item--active');
	    $(this).addClass('tab-listing__tabs__item--active');

	    $('.tab-listing__list').removeClass('tab-listing__list--active');
	    $('#tab-listing__list-' + id).addClass('tab-listing__list--active');
	    let glider = Glider(document.querySelector('#tab-listing__list-' + id));
	    glider.refresh();
	});
});

