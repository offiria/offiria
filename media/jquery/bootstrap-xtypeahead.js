/* =============================================================
 * bootstrap-typeahead.js v2.0.0
 * http://twitter.github.com/bootstrap/javascript.html#typeahead
 * =============================================================
 * Copyright 2012 Twitter, Inc.
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
 * ============================================================ */

!function ($) {

	"use strict";

	var Typeahead = function (element, options) {
		this.$element = $(element);
		this.$container = this.$element.closest('ul.dropdown-menu');
		this.$selectedinput = this.$container.find('.cl-selected-input');
		this.options = $.extend({}, $.fn.xtypeahead.defaults, options);
		this.matcher = this.options.matcher || this.matcher;
		this.sorter = this.options.sorter || this.sorter;
		this.highlighter = this.options.highlighter || this.highlighter;
		this.$menu = $(this.options.menu).appendTo(this.$container.find('li.tfk-suggested'));
		this.$menuselected = $(this.options.menuselected).appendTo(this.$container.find('li.tfk-selected'));
		this.source = this.options.source;
		this.onselect = this.options.onselect;
		this.onenterkey = this.options.onenterkey;
		this.strings = true;
		this.shown = false;
		this.listen();

		// START DEV
		if(this.options.preloadedsource.length > 0) {
			//TODO: reuse present render method
			var that = this, items, thumb;

			items = $(this.options.preloadedsource).map(function (i, item) {
				i = $(that.options.item).attr('data-value', JSON.stringify(item));
				thumb = item.thumb;
				if (!that.strings)
					item = item[that.options.property];
				i.find('a').html(item.value);

				if(typeof thumb != "undefined") {
					i.find('img')
						.addClass('tips')
						.attr({
							'src':thumb,
							'original-title':item.value
						});
				} else {
					i.find('img').remove();
				}

				if(item.selected == 1) {
					i.addClass('selected');
				}

				if(!that.options.thumbsonly) {
					i.addClass('result');
				}

				return i[0]
			});

			this.$menuselected.html(items);
			this.repopulateSelectionInput();

			// TODO: show method
			this.$menuselected.closest('li').show();
			this.$menuselected.show();
		}
		// END DEV
	};

	Typeahead.prototype = {
		constructor:Typeahead,
		select:function () {
			var val = JSON.parse(this.$menu.find('.active').attr('data-value'))
				, text

			if (!this.strings) text = val[this.options.property]
			else text = val

			// we're not posting input field values
			//this.$element.val(text)
			this.$element.val('');

			if (typeof this.onselect == "function") {
				this.onselect(val);
			} else {
				//TODO: get item template from options
				var selection = $('<li class="selected"><span class="cl-name">' + val.value + '</span><span class="cl-selected"></span></li>')
					.attr('data-value', JSON.stringify(val));

				if(typeof val.thumb != "undefined") {
					selection.prepend('<img class="tips" original-title="' + val.value + '" src="' + val.thumb + '">');
				}

				if(!this.options.thumbsonly) {
					selection.addClass('result');
				}

				selection.appendTo(this.$menuselected);

				this.$menuselected.closest('li').show();
				this.$menuselected.show();

				this.repopulateSelectionInput();
			}

			return this.hide()
		}
		,repopulateSelectionInput: function() {
			var that = this;
			var values = new Array();
			this.$menuselected.children('.selected').each(function(idx, el) {
				values.push($(el).data('value')[that.options.selectedproperty]);
			});

			this.$selectedinput.val(values.join(','));

			// Update the selection count
			this.recountSelection();
		}
		,recountSelection: function() {
			// TODO: onselect/postselect option
			var $countspan = this.$container.closest('.btn-group').find('.filter-count');

			$countspan.text(this.$menuselected.children('.selected').length);
		}
		,show:function () {
			this.shown = true;

			this.$menu.closest('li').show();
			this.$menu.show();

			this.$menuselected.closest('li').hide();
			this.$menuselected.hide();

			return this
		},
		hide:function () {
			this.$menu.closest('li').hide();
			this.$menu.hide();

			// Show the selection list if there are selected items in it
			if(this.$menuselected.children('.selected').length) {
				this.$menuselected.closest('li').show();
				this.$menuselected.show();
			}

			this.shown = false;

			return this
		},
		lookup:function (event) {
			var that = this
				, items
				, q
				, value

			this.query = this.$element.val()

			if (typeof this.source == "function") {
				value = this.source(this, this.query)
				if (value) this.process(value)
			} else {
				this.process(this.source)
			}
		},
		process:function (results) {
			var that = this
				, items
				, q

			// Get the selected items
			var existList = [];
			this.$menuselected.children('li').each(function (i, v) {
				existList.push(JSON.parse($(v).attr('data-value'))[that.options.selectedproperty])
			});

			// Exclude them from the source by comparing the existlist item with the result IDs
			var diff = new Array();
			diff = jQuery.grep(results, function (item) {
				return jQuery.inArray(item.id, existList) < 0;
			});

			results = diff;

			if (results.length && typeof results[0] != "string")
				this.strings = false

			this.query = this.$element.val()

			if (!this.query) {
				return this.shown ? this.hide() : this
			}

			items = $.grep(results, function (item) {
				if (!that.strings)
					item = item[that.options.property]
				if (that.matcher(item)) return item
			})

			if (!items.length) {
				return this.shown ? this.hide() : this
			}

			return this.render(items.slice(0, this.options.items)).show()
		},
		matcher:function (item) {
			return ~item.toLowerCase().indexOf(this.query.toLowerCase())
		},
		sorter:function (items) {
			var beginswith = []
				, caseSensitive = []
				, caseInsensitive = []
				, item
				, sortby

			while (item = items.shift()) {
				if (this.strings) sortby = item
				else sortby = item[this.options.property]

				if (!sortby.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(item)
				else if (~sortby.indexOf(this.query)) caseSensitive.push(item)
				else caseInsensitive.push(item)
			}

			return beginswith.concat(caseSensitive, caseInsensitive)
		},
		highlighter:function (item) {
			return item.replace(new RegExp('(' + this.query + ')', 'ig'), function ($1, match) {
				return '<strong>' + match + '</strong>'
			})
		},
		render:function (items) {
			var that = this;
			var thumb;

			items = $(items).map(function (i, item) {
				i = $(that.options.item).attr('data-value', JSON.stringify(item));
				thumb = item.thumb;
				if (!that.strings)
					item = item[that.options.property];
				i.find('a').html(that.highlighter(item));

				if(typeof thumb != "undefined") {
					i.find('img').attr("src", thumb);
				} else {
					i.find('img').remove();
				}

				i.addClass('result');

				return i[0]
			});

			items.first().addClass('active');
			this.$menu.html(items);

			return this
		},
		next:function (event) {
			var active = this.$menu.find('.active').removeClass('active')
				, next = active.next()

			if (!next.length) {
				next = $(this.$menu.find('li')[0])
			}

			next.addClass('active')
		},
		prev:function (event) {
			var active = this.$menu.find('.active').removeClass('active')
				, prev = active.prev()

			if (!prev.length) {
				prev = this.$menu.find('li').last()
			}

			prev.addClass('active')
		},
		listen:function () {
			var that = this;

			this.$element
				.on('blur', $.proxy(this.blur, this))
				.on('keypress', $.proxy(this.keypress, this))
				.on('keyup', $.proxy(this.keyup, this))

			if ($.browser.webkit || $.browser.msie) {
				this.$element.on('keydown', $.proxy(this.keypress, this))
			}

			this.$menu
				.on('click', $.proxy(this.click, this))
				.on('mouseenter', 'li', $.proxy(this.mouseenter, this));

			this.$menuselected
				.on('click', function (e) {
					e.stopPropagation();
					e.preventDefault();

					// TODO: let's not use toggle
					var target = that.$menuselected.find('.active');
					target.toggleClass('selected');
					that.repopulateSelectionInput();
				})
				.on('mouseenter', 'li', function (e) {
					that.$menuselected.find('.active').removeClass('active');
					$(e.currentTarget).addClass('active');
				});
		},
		keyup:function (e) {
			e.stopPropagation();
			e.preventDefault();

			switch (e.keyCode) {
				case 40: // down arrow
				case 38: // up arrow
					break;

				case 9: // tab
				case 13: // enter
					if (!this.shown) return;
					this.select();
					break;

				case 27: // escape
					this.hide();
					break;

				default:
					// Put some delay, we don't want to hit the system
					var self = this;
					this.delay(function(){
						self.lookup();
					}, 300 );
			}

		},
		keypress:function (e) {
			e.stopPropagation();

			//if (!this.shown) return;

			switch (e.keyCode) {
				case 9: // tab
				case 13: // enter
					e.preventDefault();
					if (!this.shown) {
						if (typeof this.onenterkey == "function") {
							this.onenterkey(e);
							this.hide();
							return
						} else {
							return
						}
					}
					break;
				case 27: // escape
					e.preventDefault();
					break;

				case 38: // up arrow
					e.preventDefault();
					this.prev();
					break;

				case 40: // down arrow
					e.preventDefault();
					this.next();
					break;
			}
		},
		delay: (function(){
			var timer = 0;
			return function(callback, ms){
				clearTimeout (timer);
				timer = setTimeout(callback, ms);
			};
		})(),
		blur:function (e) {
			var that = this;
			e.stopPropagation();
			e.preventDefault();
			/*setTimeout(function () {
			 that.hide()
			 }, 150)*/
		},
		click:function (e) {
			e.stopPropagation();
			e.preventDefault();
			this.select()
		},
		mouseenter:function (e) {
			this.$menu.find('.active').removeClass('active');
			$(e.currentTarget).addClass('active');
		}
	};

	/* TYPEAHEAD PLUGIN DEFINITION
	 * =========================== */

	$.fn.xtypeahead = function (option) {
		return this.each(function () {
			var $this = $(this)
				, data = $this.data('typeahead')
				, options = typeof option == 'object' && option
			if (!data) $this.data('typeahead', (data = new Typeahead(this, options)))
			if (typeof option == 'string') data[option]()
		})
	};

	$.fn.xtypeahead.defaults = {
		source:[],
		items:8,
		menu:'<ul class="cl-avatar clearfix"></ul>',
		menuselected:'<ul class="cl-avatar clearfix"></ul>',
		item:'<li><img src=""/><span class="cl-name"><a href="#"></a></span><span class="cl-selected"></span></li>',
		onselect:null,
		property:'value',
		selectedproperty:'id',
		thumbsonly:false,
		preloadedsource:[]
	};

	$.fn.xtypeahead.Constructor = Typeahead


	/* TYPEAHEAD DATA-API
	 * ================== */

	$(function () {
		$('body').on('focus.typeahead.data-api', '[data-provide="typeahead"]', function (e) {
			var $this = $(this)
			if ($this.data('typeahead')) return
			e.preventDefault()
			$this.xtypeahead($this.data())
		})
	})

}(window.jQuery);