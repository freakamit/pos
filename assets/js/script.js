$(function () {

	var App;

	App = {
		init:function(){
			App.initFooterItem();
		},
		initFooterItem : function() {
			$obj = $('.folder-nav li.show').find('a');
			var val = $obj.html();
			var id = $obj.attr('data-id');

			var len = $(id + ' > .img-view').length;
			$('.footer-item > span').html(val);
			$('.footer-count').html(len);
		},
	}

	App.init();

	$('.folder-nav li').on('click', function() {
		$('.folder-nav li.show').removeClass('show');
		$(this).addClass('show');

		var id = $(this).find('a').attr('data-id');
		$('.window-content.show').removeClass('show');
		$(id).addClass('show');

		App.initFooterItem();

	});

	$('body').on('click','.img-view', function() {
		$('.img-view.img-selected').removeClass('img-selected');
		$(this).addClass('img-selected');

		var imgName = $(this).find('div.img-info').html();
		$('.footer-img-name').html(imgName);
	});

});

