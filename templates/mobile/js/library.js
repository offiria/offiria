/* ==========================================================
 * bootstrap-twipsy.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#twipsy
 * Adapted from the original jQuery.tipsy by Jason Frame
 * ==========================================================
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
 * ========================================================== */


!function( $ ) {

  "use strict"

 /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)
  * ======================================================= */

  var transitionEnd

  $(document).ready(function () {

    $.support.transition = (function () {
      var thisBody = document.body || document.documentElement
        , thisStyle = thisBody.style
        , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined
      return support
    })()

    // set CSS transition event type
    if ( $.support.transition ) {
      transitionEnd = "TransitionEnd"
      if ( $.browser.webkit ) {
      	transitionEnd = "webkitTransitionEnd"
      } else if ( $.browser.mozilla ) {
      	transitionEnd = "transitionend"
      } else if ( $.browser.opera ) {
      	transitionEnd = "oTransitionEnd"
      }
    }

  })


 /* TWIPSY PUBLIC CLASS DEFINITION
  * ============================== */

  var Twipsy = function ( element, options ) {
    this.$element = $(element)
    this.options = options
    this.enabled = true
    this.fixTitle()
  }

  Twipsy.prototype = {

    show: function() {
      var pos
        , actualWidth
        , actualHeight
        , placement
        , $tip
        , tp

      if (this.hasContent() && this.enabled) {
        $tip = this.tip()
        this.setContent()

        if (this.options.animate) {
          $tip.addClass('fade')
        }

        $tip
          .remove()
          .css({ top: 0, left: 0, display: 'block' })
          .prependTo(document.body)

        pos = $.extend({}, this.$element.offset(), {
          width: this.$element[0].offsetWidth
        , height: this.$element[0].offsetHeight
        })

        actualWidth = $tip[0].offsetWidth
        actualHeight = $tip[0].offsetHeight

        placement = maybeCall(this.options.placement, this, [ $tip[0], this.$element[0] ])

        switch (placement) {
          case 'below':
            tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'above':
            tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'left':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset}
            break
          case 'right':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset}
            break
        }

        $tip
          .css(tp)
          .addClass(placement)
          .addClass('in')
      }
    }

  , setContent: function () {
      var $tip = this.tip()
      $tip.find('.twipsy-inner')[this.options.html ? 'html' : 'text'](this.getTitle())
      $tip[0].className = 'twipsy'
    }

  , hide: function() {
      var that = this
        , $tip = this.tip()

      $tip.removeClass('in')

      function removeElement () {
        $tip.remove()
        
      }
		removeElement();
      $.support.transition && this.$tip.hasClass('fade') ?
        $tip.bind(transitionEnd, removeElement) :
        removeElement()
    }

  , fixTitle: function() {
      var $e = this.$element
      if ($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
        $e.attr('data-original-title', $e.attr('title') || '').removeAttr('title')
      }
    }

  , hasContent: function () {
      return this.getTitle()
    }

  , getTitle: function() {
      var title
        , $e = this.$element
        , o = this.options

        this.fixTitle()

        if (typeof o.title == 'string') {
          title = $e.attr(o.title == 'title' ? 'data-original-title' : o.title)
        } else if (typeof o.title == 'function') {
          title = o.title.call($e[0])
        }

        title = ('' + title).replace(/(^\s*|\s*$)/, "")

        return title || o.fallback
    }

  , tip: function() {
      return this.$tip = this.$tip || $('<div class="twipsy" />').html(this.options.template)
    }

  , validate: function() {
      if (!this.$element[0].parentNode) {
        this.hide()
        this.$element = null
        this.options = null
      }
    }

  , enable: function() {
      this.enabled = true
    }

  , disable: function() {
      this.enabled = false
    }

  , toggleEnabled: function() {
      this.enabled = !this.enabled
    }

  , toggle: function () {
      this[this.tip().hasClass('in') ? 'hide' : 'show']()
    }

  }


 /* TWIPSY PRIVATE METHODS
  * ====================== */

   function maybeCall ( thing, ctx, args ) {
     return typeof thing == 'function' ? thing.apply(ctx, args) : thing
   }

 /* TWIPSY PLUGIN DEFINITION
  * ======================== */

  $.fn.twipsy = function (options) {
    $.fn.twipsy.initWith.call(this, options, Twipsy, 'twipsy')
    return this
  }

  $.fn.twipsy.initWith = function (options, Constructor, name) {
    var twipsy
      , binder
      , eventIn
      , eventOut

    if (options === true) {
      return this.data(name)
    } else if (typeof options == 'string') {
      twipsy = this.data(name)
      if (twipsy) {
        twipsy[options]()
      }
      return this
    }

    options = $.extend({}, $.fn[name].defaults, options)

    function get(ele) {
      var twipsy = $.data(ele, name)

      if (!twipsy) {
        twipsy = new Constructor(ele, $.fn.twipsy.elementOptions(ele, options))
        $.data(ele, name, twipsy)
      }

      return twipsy
    }

    function enter() {
      var twipsy = get(this)
      twipsy.hoverState = 'in'

      if (options.delayIn == 0) {
        twipsy.show()
      } else {
        twipsy.fixTitle()
        setTimeout(function() {
          if (twipsy.hoverState == 'in') {
            twipsy.show()
          }
        }, options.delayIn)
      }
    }

    function leave() {
      var twipsy = get(this)
      twipsy.hoverState = 'out'
      if (options.delayOut == 0) {
        twipsy.hide()

      } else {
        setTimeout(function() {
          if (twipsy.hoverState == 'out') {
            twipsy.hide()
          }
        }, options.delayOut)
      }
    }

    if (!options.live) {
      this.each(function() {
        get(this)
      })
    }

    if (options.trigger != 'manual') {
      binder   = options.live ? 'live' : 'bind'
      eventIn  = options.trigger == 'hover' ? 'mouseenter' : 'focus'
      eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur'
      this[binder](eventIn, enter)[binder](eventOut, leave)
    }

    return this
  }

  $.fn.twipsy.Twipsy = Twipsy

  $.fn.twipsy.defaults = {
    animate: true
  , delayIn: 0
  , delayOut: 0
  , fallback: ''
  , placement: 'above'
  , html: false
  , live: false
  , offset: 0
  , title: 'title'
  , trigger: 'hover'
  , template: '<div class="twipsy-arrow"></div><div class="twipsy-inner"></div>'
  }

  $.fn.twipsy.rejectAttrOptions = [ 'title' ]

  $.fn.twipsy.elementOptions = function(ele, options) {
    var data = $(ele).data()
      , rejects = $.fn.twipsy.rejectAttrOptions
      , i = rejects.length

    while (i--) {
      delete data[rejects[i]]
    }

    return $.extend({}, options, data)
  }

}( window.jQuery || window.ender );

/* ===========================================================
 * bootstrap-popover.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#popover
 * ===========================================================
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
 * =========================================================== */


!function( $ ) {

 "use strict"

  var Popover = function ( element, options ) {
    this.$element = $(element)
    this.options = options
    this.enabled = true
    this.fixTitle()
  }

  /* NOTE: POPOVER EXTENDS BOOTSTRAP-TWIPSY.js
     ========================================= */

  Popover.prototype = $.extend({}, $.fn.twipsy.Twipsy.prototype, {

    setContent: function () {
      var $tip = this.tip()
      $tip.find('.title')[this.options.html ? 'html' : 'text'](this.getTitle())
      $tip.find('.content p')[this.options.html ? 'html' : 'text'](this.getContent())
      $tip[0].className = 'popover'
    }

  , hasContent: function () {
      return this.getTitle() || this.getContent()
    }

  , getContent: function () {
      var content
       , $e = this.$element
       , o = this.options

      if (typeof this.options.content == 'string') {
        content = $e.attr(this.options.content)
      } else if (typeof this.options.content == 'function') {
        content = this.options.content.call(this.$element[0])
      }

      return content
    }

  , tip: function() {
      if (!this.$tip) {
        this.$tip = $('<div class="popover" />')
          .html(this.options.template)
      }
      return this.$tip
    }

  })


 /* POPOVER PLUGIN DEFINITION
  * ======================= */

  $.fn.popover = function (options) {
    if (typeof options == 'object') options = $.extend({}, $.fn.popover.defaults, options)
    $.fn.twipsy.initWith.call(this, options, Popover, 'popover')
    return this
  }

  $.fn.popover.defaults = $.extend({} , $.fn.twipsy.defaults, {
   
   content: 'data-content'
  , template: '<div class="arrow"></div><div class="inner"><h3 class="title"></h3><div class="content"><p></p></div></div>'
  })

  $.fn.twipsy.rejectAttrOptions.push( 'content' )

}( window.jQuery || window.ender );

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

