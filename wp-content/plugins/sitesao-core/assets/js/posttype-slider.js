;(function($){
	
	var slideSortableHelper = function(e, ui) {
		ui.children().children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
	
	$("#the-list").sortable({
		'items': 'tr',
		'axis': 'y',
		'helper': slideSortableHelper,
		'update' : function(e, ui) {
			$.post( ajaxurl, {
				action: 'dh_slider_update_menu_order',
				order: $("#the-list").sortable("serialize"),
			},function(res){
				
			});
		}
	});
	
	$(document).ready(function(){
		var type_switch = function(el){
			var el = $(el),
				type_image = ['_dh_bg_image_field'],
				type_video = ['_dh_bg_video_mp4_field','_dh_bg_video_ogv_field','_dh_bg_video_webm_field','_dh_bg_video_poster_field'];
			$.each(type_image,function(i,e){
				$('.'+e).hide();
			});
			$.each(type_video,function(i,e){
				$('.'+e).hide();
			});
			if(el.val() =='image'){
				$.each(type_image,function(i,e){
					$('.'+e).show();
				});
			}else if(el.val() =='video'){
				$.each(type_video,function(i,e){
					$('.'+e).show();
				});
			}
		};
		type_switch($('#dh-metabox-slide-setting').find('#_dh_bg_type'));
		$('#dh-metabox-slide-setting').find('#_dh_bg_type').on('change',function(){
			type_switch($(this));
		});
		
		var link_switch = function(el){
			var el = $(el),
				type_link = ['_dh_slide_url_field'],
				type_button = ['_dh_button_primary_text_field','_dh_button_primary_link_field','_dh_button_primary_style_field','_dh_button_primary_color_field','_dh_button_secondary_text_field','_dh_button_secondary_link_field','_dh_button_secondary_style_field','_dh_button_secondary_color_field'];
			$.each(type_link,function(i,e){
				$('.'+e).hide();
			});
			$.each(type_button,function(i,e){
				$('.'+e).hide();
			});
			if(el.val() =='link'){
				$.each(type_link,function(i,e){
					$('.'+e).show();
				});
			}else if(el.val() =='button'){
				$.each(type_button,function(i,e){
					$('.'+e).show();
				});
			}
		};
		link_switch($('#dh-metabox-slide-setting').find('#_dh_link_type'));
		$('#dh-metabox-slide-setting').find('#_dh_link_type').on('change',function(){
			link_switch($(this));
		});
		
	});
})(jQuery);