/*! Samylle Aguiar - v0.1.0 - 2013-08-29
 * http://samylleaguiar.com
 * Copyright (c) 2013; * Licensed GPLv2+ */
var ajaxPath = "/wp-admin/admin-ajax.php";
var homePath = "/";
var samylle = {
	menu: {
		init: function() {
			samylle.menu.place();
			$(".menu > ul > li").hover( function(){
				$(".hover").stop().animate( {"left": $(this).offset().left - $(".menu").offset().left, "width": $(this).outerWidth()}, 500);
			}, function(){
				$(".hover").stop().animate( {"left": $(".current").offset().left - $(".menu").offset().left, "width": $(".current").outerWidth()}, 500);
			});
		},
		place: function() {
			$(".menu .current").removeClass("current");
			var currentSection = ( $(".menu ." + $(".main").data("category")).length <= 0 ) ? "sa" : $(".main").data("category");
			$(".menu ." + currentSection).addClass("current");
			$(".hover").animate( {"left": $(".current").offset().left - $(".menu").offset().left, "width": $(".current").outerWidth()}, 500);
		}
	},
	slider: {
		init: function() {
			clearTimeout( samylle.slider.playing );
			samylle.slider.current = 0;
			$(".slider li").eq(samylle.slider.current).fadeIn(1000);
			samylle.slider.trigger();
			samylle.slider.playing = setTimeout( samylle.slider.autoplay, 5000 );
		},
		prevSlide: function() {
			samylle.slider.current = ( samylle.slider.current > 0 ) ? samylle.slider.current - 1 : $(".slider li").length - 1 ;
			$(".slider li").fadeOut();
			$(".slider li").eq(samylle.slider.current).fadeIn();
		},
		nextSlide: function() {
			samylle.slider.current = ( samylle.slider.current < $(".slider li").length - 1 ) ? samylle.slider.current + 1 : 0 ;
			$(".slider li").fadeOut();
			$(".slider li").eq(samylle.slider.current).fadeIn();
		},
		trigger: function() {
			$(".slider-control li div").unbind("click").click( function(e) {
				e.preventDefault();
				clearTimeout( samylle.slider.playing );
				if( $(this).data("dir") == "prev" ) {
					samylle.slider.prevSlide();
				} else {
					samylle.slider.nextSlide();
				}
			} );
		},
		autoplay: function() {
			samylle.slider.nextSlide();
			samylle.slider.playing = setTimeout( samylle.slider.autoplay, 5000 );
		}
	},
	ajax: {
		init: function() {
			window.onstatechange = function(){
		        var State = History.getState();
		        samylle.ajax.open( State.url );
		    }
			samylle.ajax.localLinks();
		},
		open: function( target ) {
			$("html, body").animate( { "scrollTop" : 145 } , 500 );
			$(".main").animate({opacity:0}, 500, function(){
				$("body").addClass("progress");
				$.get( target + "?ajax=true" , function( html ) {
					$(html).css("opacity",0);
					$(".main").after( html );
					$(".main").eq(0).remove();
					samylle.menu.place();
					$(".main").animate({opacity:1} , 500, function(){
						$("body").removeClass("progress");
						$("title").text( ($(".main").hasClass("page")) ? $(".post header > h1").text() + " | Samylle Aguiar" : "Samylle Aguiar" );
						samylle.ajax.localLinks( this );
					});
				});
			});
		},
		localLinks: function( target ) {
			$("a", target).each( function(){
				if( this.host == location.host ) {
					$(this).unbind("click").click( function(e){
						e.preventDefault();
						clearInterval( samylle.infiniteScroll.watching );
						clearTimeout( samylle.slider.playing );
						var href = this.href.split("#");
						History.pushState(null, document.title, href[0]);
					} );
				}
			});
		}
	},
	archive: {
		posts: [],
		change: function( content ) {
			$(".articles").addClass("request");
			$(".articles li").animate( {opacity: 0}, 300);
			$(".articles").animate( {backgroundColor: "#fff"}, 300, function(){
				if( typeof samylle.archive.posts[content] === "undefined" ) {
					$.post( ajaxPath, "action=sidebar_archive&order=" + content, function( data ){
						$(".articles").html( data ).animate( {backgroundColor: "#fff"}, 300, function(){
							$(this).removeClass("request");
							samylle.archive.posts[content] = data;
							samylle.ajax.localLinks( this );
						});
					});
				} else {
					$(".articles").html( samylle.archive.posts[content] ).animate( {backgroundColor: "#fff"}, 300, function(){
						$(this).removeClass("request");
						samylle.ajax.localLinks( this );
					});
				}
			});
		},
		triggers: function() {
			$(".archive li").click( function(){
				if( ! $(this).hasClass("active") ) {
					$(".active").removeClass("active");
					$(this).addClass("active");
					samylle.archive.change( $(this).data("target") );
				}
			} );
			samylle.archive.posts["popular"] = $(".articles").html();
		},
		init: function() {
			samylle.archive.triggers();
		}
	},
	contactForm: {
		activate: function() {
			$("#submit").click( function( e ) {
				e.preventDefault();
				if( $("#name").val() != "" && $("#email").val() != "" && $("#text").val() != "" ) {
					var fields = {
						action: "submit_contact_form",
						name: $("#name").val(),
						email: $("#email").val(),
						text: $("#text").val()
					}
					$.post( ajaxPath, fields, function( data ){
						$(".contact-form").html( "<p><strong>Formulário enviado com sucesso!</strong></p>" );
					});
				}
			} );
		},
		init: function() {
			samylle.contactForm.activate();
		}
	},
	infiniteScroll: {
		page: 1, stop: false,
		loadMore: function(){
			if( ! this.stop ) {
				$.get( homePath + "?loadmore=true&page=" + ++samylle.infiniteScroll.page , function( html ) {
					$(".posts").append( html );
					$(".posts").animate({opacity:1} , 500, function(){
						$(".generated").removeClass("generated");
						samylle.ajax.localLinks( this );
					});
				});
			}
		},
		watch: function(){
			var scrolled = false;
			samylle.infiniteScroll.stop = false;
			samylle.infiniteScroll.page = 1;
			$( window ).scroll( function() { scrolled = true ; } ) ;
			samylle.infiniteScroll.watching = setInterval( function() {
			    if ( scrolled ) { //caso positivo
			        scrolled = false ; //definimos a variavel como falsa
			        if( $( window ).scrollTop() + $( window ).height() >= Math.max( document.body.scrollHeight , document.documentElement.scrollHeight , document.body.offsetHeight , document.documentElement.offsetHeight , document.body.clientHeight , document.documentElement.clientHeight ) - 200 ) { //e verificamos se estamos no fim da página
			        	samylle.infiniteScroll.loadMore();
			        }
			    }
			} , 1000 ) ;
		}
	},
	scroll: function() {
		$(document).scroll( function(){
			if( $(window).scrollTop() <= 500 ) {
				$("header.samylle").height( 500 - $(window).scrollTop() * 2 );
			}
		} );
	},
	init: function() {
		samylle.menu.init();
		samylle.scroll();
		samylle.ajax.init();
	},
	scripts: {
		home: function( exec ) {
			if( exec == 0 ) {
				samylle.slider.init();
				samylle.infiniteScroll.watch();
				$.getScript("http://samylleaguiar.disqus.com/count.js");
			}
		},
		post: function( exec ) {
			if( exec == 0 ) {
				samylle.archive.init();
				gapi.plusone.go();
				$(".share").delay(500).fadeIn();
			}
		},
		contact: function( exec ) {
			if( exec == 0 ) {
				samylle.archive.init();
				samylle.contactForm.init();
			}
		},
		page: function( exec ) {
			if( exec == 0 ) {
				samylle.archive.init();
			}
		}
	},
	twitter: function(d,s,id){
		var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}
	}
} ;

$(function() {
	samylle.init();
});