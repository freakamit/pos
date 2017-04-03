 $(function () {
 	// Header Section Animation
 	$("h1.logo").addClass("animated fadeInDown");
 	$(".top-header-holder").addClass("animated fadeInRight");
 	function check(type){
 		return $('body').find(type).length;
 	}
 	var dropkickSelect = check('.form-select');
 	if(dropkickSelect > 0){
 		$('.form-select').dropkick();
 	}
  // tinyScrollInit();
  // $(window).resize(function(){
  //     var winWidth = $(window).width();
  //     if(winWidth <= 800){
  //         tinyScrollInit();
  //     } 
  // });

  // function tinyScrollInit(){
  //     var customScroll = check('div.scroll');
  //   if(customScroll > 0){
  //     var $scrollbar = $("div.scroll");
  //     $scrollbar.tinyscrollbar(); 
  //     var tinyScrollBar = $("div.scroll > div.scrollbar"),
  //         tinyScrollThumb = $("div.scroll > div.scrollbar div.thumb"),
  //         curScrollHeight = tinyScrollBar.innerHeight(),
  //         curScrollThumbHeight = tinyScrollThumb.innerHeight(),
  //         newScrollHeight = curScrollHeight - 20,
  //         newScrollThumbHeight = curScrollThumbHeight -20;
  //         tinyScrollBar.css("height", newScrollHeight);
  //         tinyScrollThumb.css("height", newScrollThumbHeight);
  //         tinyScrollBar.find("div.track").css("height", newScrollHeight);
  //   }
  // }

	var owlCarouselBanner = check('#mainBanner');
	    if(owlCarouselBanner > 0 ){
	      	$("#mainBanner").owlCarousel({
	      	navigation : false,
	      	slideSpeed : 300,
	      	paginationSpeed : 400,
	      	autoPlay: true,
	      	autoPlaySpeed: 3000,
	      	singleItem : true,

      // "singleItem:true" is a shortcut for:
      // items : 1, 
      // itemsDesktop : false,
      // itemsDesktopSmall : false,
      // itemsTablet: false,
      // itemsMobile : false
  			});
	}
	var owlCarouselBanner = check('#testimonialBanner');
	    if(owlCarouselBanner > 0 ){
	      	$("#testimonialBanner").owlCarousel({
	      	navigation : false,
	      	slideSpeed : 300,
	      	paginationSpeed : 400,
	      	autoPlay: true,
	      	autoPlaySpeed: 3000,
	      	singleItem : true,


      // "singleItem:true" is a shortcut for:
      // items : 1, 
      // itemsDesktop : false,
      // itemsDesktopSmall : false,
      // itemsTablet: false,
      // itemsMobile : false
  			});
	}
	// Custom Navigation Events
	// for main banner
	$('.banner-slider .customNextBtn').click(function(e) {
    e.preventDefault();
    $("#mainBanner").trigger('owl.next');
  	});
  	$('.banner-slider .customPrevBtn').click(function(e) {
    e.preventDefault();
    $("#mainBanner").trigger('owl.prev');
  	});
  	// for testimonial banner
  	$('.featured-testimonial .customNextBtn').click(function(e) {
    e.preventDefault();
    $("#testimonialBanner").trigger('owl.next');
  	});
  	$('.featured-testimonial .customPrevBtn').click(function(e) {
    e.preventDefault();
    $("#testimonialBanner").trigger('owl.prev');
  	});

  // Custom Navigation Events
  //Sticky Navigation
  $(window).scroll(function(){
  	  var siteHeaderHeight = $("header.site-header").innerHeight(),
  	  	  windowScrollTop = $(window).scrollTop();
  	  if(windowScrollTop > siteHeaderHeight){
  	  	$("header.site-header div.main-header").addClass("sticky animated slideInDown");
  	  }
  	  else{
  	  	$("header.site-header div.main-header").removeClass("sticky slideInDown");
  	  }
  });
  // Featured Vertical Content Tab
  
   $("div.tab-view-content div.tab-content-item").first().addClass("current");
  $("div.vertical-tab-content .tab-link ul li a").on('click',function(e){
  	e.preventDefault();
  	$("div.tab-link ul li").removeClass("active");
  	$("div.tab-view-content div.tab-content-item").removeClass("current");
  	$(this).parent("li").addClass("active");
  	var currentDataTab = $(this).data("tab");
  	$(currentDataTab).addClass('current');
  });
  // Admission Process Tab
$("div.process-tab-content div.process-info-item").first().addClass("current");
  $("ul.process-tab-link li span").on('click',function(){
    $("ul.process-tab-link li").removeClass("active");
    $("div.process-tab-content div.process-info-item").removeClass("current");
    $(this).parent("li").addClass("active");
    var currentDataTab = $(this).data("tab");
    console.log(currentDataTab);
    $(currentDataTab).addClass('current');
  });

  // On focus Contact Full Display
  $("div.front-contact-form form input").on("focus", function(){
  	var parentOffset = $("div.front-contact-form").offset(),
  		stickyMenuheight = $("div.sticky").innerHeight(),
  		contactFormHeight = $("div.front-contact-form").innerHeight();
  	$(this).parents("div.front-contact-form").addClass("show").animate({height : 567}, 500 );
  	$("body, html").animate({scrollTop : parentOffset.top - stickyMenuheight}, 1000);
  });

  //on screen featured animation
  $('section.featured-events-lists div.events article, div.our-stats div.stat-block, section.featured-notices .container-layout > ul, #testimonialBanner, div.front-contact-form form, div.block-middle h1, div.top-footer div.block').onScreen({
	doIn:function(){
	    $(this).animate({opacity:1}).addClass('animated fadeInUp');
	 },
	doOut:function(){
	    $(this).removeClass('fadeInUp onScreen').animate({opacity:0.6});
	},
	     tolerence:50
	});
   $('div.our-stats').onScreen({
	doIn:function(){
	    $('#colNo').animateNumber({ number: 8,  easing: 'easeInQuad', }, 1000);
	    $('#colPlac').animateNumber({ number: 15000,  easing: 'easeInQuad', }, 1000);
	    $('#colGra').animateNumber({ number: 75840,  easing: 'easeInQuad', }, 1000);
	 },
	doOut:function(){
	},
	     tolerence:50
	});

  $(window).load(function(){
	$('#preloader').fadeOut('slow',function(){$(this).remove();});
});
  // var inNum =$("body").contents();  
  //   inNum=parseInt(inNum);
  //   inNum.css("color" , "red";)

  $("div.buger-menu").on("click", function(){
    $("body").toggleClass("mobile-slide");
  });

  $("div.apply-now").on("click", "a.btn", function(e){
    e.preventDefault();
    $("body").addClass("hidden-y");
    $("#admissionForm").css({display : "flex", visibility : "visible"}).animate({
      opacity: 1
    });
    $("#admissionForm div.popup-box").addClass("animated fadeInDown").animate({top: "100px"});
  });
  $("div.select-item").on("click", "a.btn", function(e){
    e.preventDefault();
    $("body").addClass("hidden-y");
    $("#carrerForm").css({display : "flex", visibility : "visible"}).animate({
      opacity: 1
    });
    $("#carrerForm div.popup-box").addClass("animated fadeInDown").animate({top: "100px"});
  });
  $("span.close").on("click", function(){
    $("body").removeClass("hidden-y");
    $("body").css("overflow", "hidden");
    setTimeout(function(){
      $("body").attr("style", "");
    }, 800);
    $(this).parents("div.popup-holder").fadeOut();
    $(this).parents("div.popup-box").animate({top: "-100%"});
  });

  $("div.vacancy-item div.btn-more i").on("click", function(){
    debugger;
    if($(this).parents("div.vacancy-item").hasClass("is-open")){
      $("div.vacancy-item").removeClass("is-open").find("div.job-detail").slideUp();
    }
    else{
        $("div.vacancy-item").removeClass("is-open");
        $("div.vacancy-item div.job-detail").css("display", "none");
        $(this).parents("div.vacancy-item").addClass("is-open").find("div.job-detail").slideDown();
    }
  });
});