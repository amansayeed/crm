// JavaScript Document
//side menu transition
(function($){$(function(){var menu_head=$('ul.side-menu h2.title').height();var item_height=$('ul.side-menu li a').height();$(document).mouseup(function(e){var wrapper=$('ul.side-menu');if((!wrapper.is(e.target)&&wrapper.has(e.target).length===0)&&(!($('a.m_menu_open').is(e.target))&&$('a.m_menu_open').has(e.target).length===0)){wrapper.removeClass("in");$('body, ul.side-menu').removeClass("open");$('ul.side-menu li').css("top","100%");$('ul.side-menu h2').css("top","-60px");}});$("a.m_menu_open").click(function(e){e.preventDefault();if($('ul.side-menu, body').hasClass('open')){$('ul.side-menu').removeClass('open');$('body').removeClass('open');$('ul.side-menu li').css("top","100%");$('ul.side-menu h2').css("top","-60px");}
else{$('ul.side-menu').addClass('open');$('body').addClass('open');$('ul.side-menu h2').css("top",0);$('ul.side-menu li').each(function(){if($(this).hasClass('link')){var i=($(this).index()- 1)
var fromTop=menu_head+(i*item_height);var delayTime=100*i;$(this).delay(delayTime).queue(function(){$(this).css("top",fromTop);$(this).dequeue();});}
else{var delayTime=(row_i*200)+ Math.floor((Math.random()*200)+ 1);console.log(delayTime);$(this).css("left",fromLeft);$(this).delay(delayTime).queue(function(){$(this).css("top",fromTop);$(this).dequeue();});}});}})});})(jQuery); 
/***************** Smooth Scrolling ******************/
$(window).load(function() {
	$('ul.menu_nav li a,ul.side-menu li a').on('click', function () {
		$('body, ul.side-menu').removeClass("open");
		var scrollAnchor = $(this).attr('data-scroll'),
			scrollPoint = $('div[data-anchor="' + scrollAnchor + '"]').offset().top - 90;
	
		$('body,html').animate({
			scrollTop: scrollPoint
		}, 1500);
	
		return false;
	
	})
	$(window).scroll(function () {
		var windscroll = $(window).scrollTop();
		var fromBottom = $(document).height() - ($(window).scrollTop() + $(window).height());
		if (fromBottom == 0) {     // <-- scrolled to the bottom
			$('ul.menu_nav li a.data_link,ul.side-menu li a.data_link').removeClass('current');
			$('ul.menu_nav li a.data_link:last,ul.side-menu li a.data_link:last').addClass('current');
		} else if (windscroll > 90) {
			$('.data_source').each(function (i) {
				if ($(this).position().top <= windscroll + 300) {
					$('ul.menu_nav li a.data_link,ul.side-menu li a.data_link').removeClass('current');
					$('ul.menu_nav li a.data_link,ul.side-menu li a.data_link').eq(i).addClass('current');
				}
			});
		} else {
			$('ul.menu_nav li a.data_link,ul.side-menu li a.data_link').removeClass('current');
			$('ul.menu_nav li a.data_link:first,ul.side-menu li a.data_link:first').addClass('current');
		}
	
	}).scroll();
});
//top scroll
$(document).ready(function(){ 
		$(window).scroll(function(){
			if ($(this).scrollTop() > 100) {
				$('.back_top').fadeIn();
			} else {
				$('.back_top').fadeOut();
			}
		}); 
		$('.back_top').click(function(){
			$("html, body").animate({ scrollTop: 0 }, 1000);
			return false;
		});

	});