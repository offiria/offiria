/*
 * jQuery Autogrow Text Area
 * version 1.0
 * It automatically adjusts the height on text area.
 *
 * Written by Jerry Luk jerry@presdo.com
 *
 * Based on Chrys Bader's Auto Expanding Text area www.chrysbader.com
 * and Craig Buckler's TextAreaExpander http://www.sitepoint.com/blogs/2009/07/29/build-auto-expanding-textarea-1/
 *
 * IMPORTANT: code has been altered because on iPad browser the recalculation of textarea height will cause typing very slow <Ikmal Ezzani>
 * DO NOT replace this library code without notifying author
 *
 * Licensed under MIT license.
 */

(function($) {
	$.fn.autoGrow = function(options) {
		var defaults = {
			expandTolerance: 1,
			heightKeeperFunction: null
		};
		options = $.extend(defaults, options);
		
		// IE and Opera should never set a textarea height of 0px
		var hCheck = !($.browser.msie);
		function resize(e) {
			var $e = $(e.target || e), // event or element
			contentLength = $e.val().length,
			elementWidth = $e.innerWidth();
			if ($e.is(":hidden")) {
				// Do not do anything if the element is hidden as we cannot determine the height correctly
				return $e;
			}
			if (contentLength != $e.data("autogrow-length") || elementWidth != $e.data("autogrow-width")) {
				
   				// make the parameter cache-able
				var autogrowMin = $e.data("autogrow-min"),
				scrollHeight = $e.prop("scrollHeight"),
				autogrowLineHeight = $e.data("autogrow-line-height"),
				autogrowMax = $e.data("autogrow-max"),
				cdrScrollHeight = $e.data("cdr-scroll-height") || 0,
				cdrOffset = $e.data("scroll-offset") || 0,
				height = 0;

				// offset can be use to detect changes in height (used if the calculation of height is incorrect)
				var offset = (scrollHeight - cdrScrollHeight)

				// if this does not need growing, exit
				if (scrollHeight == cdrScrollHeight || offset == cdrOffset) {
					return;
				} 
				else if (offset < cdrOffset && $.browser.mozilla) {
					// purposely let this passed through
				} 
				else {
					// bind the offset to retrieve on the next trigger
					$e.data("scroll-offset", offset);
				}

				var calculatedHeight = Math.max(autogrowMin, Math.ceil(Math.min(
					scrollHeight + options.expandTolerance * autogrowLineHeight,
					autogrowMax)));

				// For non-IE and Opera browser, it requires setting the height to 0px to compute the right height
				if (hCheck && (contentLength < $e.data("autogrow-length") ||
							   elementWidth != $e.data("autogrow-width"))) {
					if ($.isFunction(options.heightKeeperFunction)) {
						(options.heightKeeperFunction($e)).height((options.heightKeeperFunction($e)).height());
					}
					$e.css("height", "0px");
				}

				// recalculate height since it was adjusted to 0
				scrollHeight = $e.prop("scrollHeight");

				// there are changes in the height adjust them
				if (cdrScrollHeight < scrollHeight && calculatedHeight == scrollHeight) {
					// to store value to previous height
					cdrScrollHeight = scrollHeight;
					// then assign new height 
					height = scrollHeight;
				} 
				else {
					// to maintain current state without recalculation
					height = scrollHeight;
				}

				// we need store previous height (as in the one that make changes) in data otherwise var will be hoist
				$e.data("cdr-scroll-height", scrollHeight || height);

				$e.css("overflow", ($e.prop("scrollHeight") > height ? "auto" : "hidden"));
				$e.css("height", height + "px");
				if ($.isFunction(options.heightKeeperFunction)) {
					(options.heightKeeperFunction($e)).css({ height: 'auto' });
				}
			}
			
			return $e;
		};
		
		function parseNumericValue(v) {
			var n = parseInt(v, 10);
			return isNaN(n) ? null : n;
		};
		
		function initElement($e) {
			$e.data("autogrow-min", options.minHeight || parseNumericValue($e.css('min-height')) || 0);
			$e.data("autogrow-max", options.maxHeight || parseNumericValue($e.css('max-height')) || 99999);
			$e.data("autogrow-line-height", options.lineHeight || parseNumericValue($e.css('line-height')));

			resize($e);
		};
		
		this.each(function() {
			var $this = $(this);
            
			if (!$this.data("autogrow-initialized")) {
				$this.css("padding-top", 0).css("padding-bottom", 0);
				$this.bind("keyup", resize).bind("focus", resize);
				$this.data("autogrow-initialized", true);
			}
			
			// initElement($this);
			// Sometimes the CSS attributes are not yet there so the above computation might be wrong
			// 100ms delay will do the job
			setTimeout(function() { initElement($this); }, 100);
		});
		
		return this;
	};
})(jQuery);







/* ========================================================
 * bootstrap-tabs.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#tabs
 * ========================================================
 * Copyright 2011 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================== */


!function( $ ){

	"use strict"

	function activate ( element, container ) {
		container
			.find('> .active')
			.removeClass('active')
			.find('> .dropdown-menu > .active')
			.removeClass('active')

		element.addClass('active')

		if ( element.parent('.dropdown-menu') ) {
			element.closest('li.dropdown').addClass('active')
		}
	}

	function tab( e ) {
		var $this = $(this)
		, $ul = $this.closest('ul:not(.dropdown-menu)')
		, href = $this.attr('href')
		, previous
		, $href

		if ( /^#\w+/.test(href) ) {
			e.preventDefault()

			if ( $this.parent('li').hasClass('active') ) {
				return
			}

			previous = $ul.find('.active a').last()[0]
			$href = $(href)

			activate($this.parent('li'), $ul)
			activate($href, $href.parent())

			$this.trigger({
				type: 'change'
				, relatedTarget: previous
			})
		}
	}


	/* TABS/PILLS PLUGIN DEFINITION
	 * ============================ */

	$.fn.tabs = $.fn.pills = function ( selector ) {
		return this.each(function () {
			$(this).delegate(selector || '.tabs li > a, .pills > li > a', 'click', tab)
		})
			}

	$(document).ready(function () {
		$('body').tabs('ul[data-tabs] li > a, ul[data-pills] > li > a')
	})

}( window.jQuery || window.ender );



/* ========================================================
 * cWindow
 * ========================================================
 */

(function($){
	
	var methods = {
	    show : function( title, func ) {
	    	// Hide old ones
			methods.hide();
	    	
			var windowHTML = decodeURIComponent('%3Cdiv%20id%3D%22cWindow%22%3E%0A%09%09%3Cdiv%20class%3D%22cWindow-border%22%3E%0A%09%09%09%3Cdiv%20class%3D%22cWindow-container%22%3E%0A%09%09%09%09%3Cdiv%20class%3D%22cWindow-head%22%3E%0A%09%09%09%09%09%3Cspan%3E%3Ch2%3E%7Btitle%7D%3C%2Fh2%3E%3C%2Fspan%3E%0A%09%09%09%09%09%3Cspan%20class%3D%22cWindow-close%20fRight%22%3E%3C%2Fspan%3E%0A%09%09%09%09%3C%2Fdiv%3E%0A%0A%09%09%09%09%3Cdiv%20class%3D%22cWindow-content%22%3E%0A%09%09%09%09%09%3Cdiv%20class%3D%22cWindow-dummy%22%3E%3C%2Fdiv%3E%0A%09%09%09%09%3C%2Fdiv%3E%0A%0A%09%09%09%09%3Cdiv%20class%3D%22cWindow-foot%22%3E%0A%09%09%09%09%09%3Cspan%3E%3Cinput%20type%3D%22button%22%20value%3D%22Button%22%20class%3D%22fRight%22%3E%3C%2Fspan%3E%0A%09%09%09%09%3C%2Fdiv%3E%0A%0A%09%09%09%3C%2Fdiv%3E%0A%09%09%3C%2Fdiv%3E%0A%09%3C%2Fdiv%3E');
			// Set the title
			windowHTML = windowHTML.replace('{title}', title);
			
			var contentHeight = 200;
			var modalSizes = {
				contentWrapHeight  : function() { return + contentHeight },
				contentOuterWidth  : function() { return + contentWidth },
				contentOuterHeight : function() { return + contentHeight + 30 },
				width              : function() { return this.contentOuterWidth() + 40 },
				height             : function() { return this.contentOuterHeight() + 40 },
				left               : function() { return ($(window).width() - this.width()) / 2 },
				top                : function() { return (($(window).height() - 100 - contentHeight) / 2) },
				zIndex             : function() { return cGetZIndexMax() + 1 }
			};
			
			// Set position of te window to the center
			
			// Attach it to the body
			$(windowHTML)
				.css(
					{
						'top'   : modalSizes.top()
					}).prependTo('body');
			
			// Click the close button
			$('.cWindow-close').click(function(){
				methods.hide();	
			});
			
			// Call the ajax to load the content
			func.apply();
			
	    },
	    hide : function( ) { 
			$('#cWindow').remove();
	    },
	    
	    // Set Window title
	    title : function ( title ){
	    	$('#cWindow .cWindow-head h2').html(title);
		},
	    
	    content : function( content ) {
	    	// Add content and resize the window's height
	    	$('#cWindow .cWindow-content').html(content);
			$().modal('resize');
			
		},
		
		resize: function(){
			var windowHeight = $(window).height() - 100;
			var contentHeight = $('#cWindow .cWindow-container').height();
			var uncoveredHeight = windowHeight - contentHeight;
			var offsetHeight = parseInt(uncoveredHeight/2);
			
			$('#cWindow').animate({'top': offsetHeight+'px'}, 'fast');
			
			// show scroll bar to the content if the content is too long
			if (contentHeight + offsetHeight > (windowHeight * 0.8))
			{
				$('#cWindow .cWindow-content').css({'height': parseInt(windowHeight /2)+'px', 'overflow-y':'scroll', 'overflow-x':'hidden'}, 1000);
			}
		},
		
		actions : function( content ) {
	    	// Add content and resize the window's height
	    	$('#cWindow .cWindow-foot').html(content);
		},
	    update : function( content ) { 
			// !!! 
	    }
  	};

    /*$.fn.modal = function( method ) {
		
		// Method calling logic
		if ( methods[method] ) {
    		return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
    		return methods.init.apply( this, arguments );
		} else {
    		$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
		}    
		
	};*/
    
	//pass jQuery to the function, 
	//So that we will able to use any valid Javascript variable name 
	//to replace "$" SIGN. But, we'll stick to $ (I like dollar sign: ) )       
})(jQuery);



(function($){
	
	var methods = {

		init : function (selector, options){
			// limit to scroll is the optimum height before the popover is attached to a scroller
			var limitToScroll = options.height,
			reposition = options.reposition;
			$('div.content').live('mouseenter', function () {
				clearTimeout(popModalTimeout);
				popModalTimeout = null;

				var $content = $(this);
				$content.find('li:not(.nolink)').mouseenter(function() {
					$(this).addClass('active');
				}).mouseleave(function() {
					$(this).removeClass('active');
				}).click(function() {
					var url = $(this).find('a').attr('href');
					window.location = url;
				});
				// console.log("enter");
			}).live('mouseleave', function () {
				if(popModalTimeout == null)
			   		$(popModal).popover('hide');
			   	//console.log("leave");
			});
			
			options.trigger = 'manual';
			$(selector).hover(
				function() {
					// make sure there is only single instance of popover
					$('div.popover').remove();

				    clearTimeout(popModalTimeout);
				    popModalTimeout = null;
				    popModal = this;

					// For some reason, it cannot take 'options' object
					$(this).popover({trigger:options.trigger, html: options.html, placement: options.placement});
					$(this).popover('show');
					// since there are more new options needed extending option object serve no purpose
					$('.popover').popScroll({ height: limitToScroll, sender: this, reposition: reposition, placement: options.placement });
				}, function(){
					// trigger popover closure, if 500ms later, the mouse is outside
					// the popover
					popModalTimeout = setTimeout(function(){
						$(popModal).popover('hide');
					},1000);
				});
		}
  	};

	$.fn.extend({
		popScroll: function(options) {
			var height = options.height || 250,
			sender = options.sender || this,
			reposition = options.reposition || false;
			if ($('.popover .overview').length == 0 && $('.popover').height() > height) {
				var templateScrollbar = '<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>';
				$('.popover .inner .content ul').addClass('overview');
				$('.popover .inner .content').addClass('viewport');
				if ($('div.popover .inner').find('.scrollbar').length == 0) {
					$('.popover .viewport').prepend(templateScrollbar);
				}
				if (reposition) { 
					// the scroller will move the popover by pixels once the first trigger, this is to fix that
					switch(options.placement) {
					case 'above':
						$('.popover').css('top', $(sender).offset().top 
										  - $(sender).parent('tr').height() * 2 
										  // unspecified arrow height
										  - 20);
						break;
					case 'right':
						$('.popover').css('top', $(sender).offset().top - (height / 2));
						break;
					case 'below':
						break;
					case 'left':
						break;
					}
						
				}

				// modified the width according to the popover width content
				var totalWidth = $('.popover .content ul').outerWidth();
				$('.popover .viewport').css({
					'height': height,
					'width': totalWidth,
					'overflow': 'hidden'
				});
				$('.popover').tinyscrollbar({axis:'y'});

			}
		}
	});
	
    $.fn.popmodal = function( method ) {
		// Method calling logic
		if ( methods[method] ) {
    		return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
		return methods.init( this, method );
		} else {
    		$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
		}    
		
	};

	//pass jQuery to the function, 
	//So that we will able to use any valid Javascript variable name 
	//to replace "$" SIGN. But, we'll stick to $ (I like dollar sign: ) )       
})(jQuery);

var popModal = null;
var popModalTimeout = null;

/*
 *
 * Copyright (c) 2010 C. F., Wong (<a href="http://cloudgen.w0ng.hk">Cloudgen Examplet Store</a>)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */
(function($,len,createRange,duplicate){
	$.fn.caret=function(options,opt2){
		var start,end,t=this[0],browser=$.browser.msie;
		if(typeof options==="object" && typeof options.start==="number" && typeof options.end==="number") {
			start=options.start;
			end=options.end;
		} else if(typeof options==="number" && typeof opt2==="number"){
			start=options;
			end=opt2;
		} else if(typeof options==="string"){
			if((start=t.value.indexOf(options))>-1) end=start+options[len];
			else start=null;
		} else if(Object.prototype.toString.call(options)==="[object RegExp]"){
			var re=options.exec(t.value);
			if(re != null) {
				start=re.index;
				end=start+re[0][len];
			}
		}
		if(typeof start!="undefined"){
			if(browser){
				var selRange = this[0].createTextRange();
				selRange.collapse(true);
				selRange.moveStart('character', start);
				selRange.moveEnd('character', end-start);
				selRange.select();
			} else {
				this[0].selectionStart=start;
				this[0].selectionEnd=end;
			}
			this[0].focus();
			return this
		} else {
			if(browser){
				var selection=document.selection;
                if (this[0].tagName.toLowerCase() != "textarea") {
                    var val = this.val(),
                    range = selection[createRange]()[duplicate]();
                    range.moveEnd("character", val[len]);
                    var s = (range.text == "" ? val[len]:val.lastIndexOf(range.text));
                    range = selection[createRange]()[duplicate]();
                    range.moveStart("character", -val[len]);
                    var e = range.text[len];
                } else {
                    var range = selection[createRange](),
                    stored_range = range[duplicate]();
                    stored_range.moveToElementText(this[0]);
                    stored_range.setEndPoint('EndToEnd', range);
                    var s = stored_range.text[len] - range.text[len],
                    e = s + range.text[len]
                }
            } else {
				var s=t.selectionStart,
				e=t.selectionEnd;
			}
			var te=t.value.substring(s,e);
			return {start:s,end:e,text:te,replace:function(st){
				return t.value.substring(0,s)+st+t.value.substring(e,t.value[len])
			}}
		}
	}
})(jQuery,"length","createRange","duplicate");