(function($) {
	$.fn.checkbox = function() {
		$(this).each(function() {
			var $el = $(this);
			var typeClass = $el.attr('type');
			$el.hide();
			$el.next('.'+typeClass+'-sign').remove();
			var $checkbox = $('<span class="'+typeClass+'-sign" />').insertAfter($el);
			$checkbox.click(function() {
				if($checkbox.closest('label').length) return;
				if($el.attr('type') == 'radio') {
					$el.prop('checked', true).trigger('change').trigger('click');
				} else {
					$el.prop('checked', !($el.is(':checked'))).trigger('change');
				}
			});
			$el.change(function() {
				$('input[name="'+$el.attr('name')+'"]').each(function() {
					if($(this).is(':checked')) {
						$(this).next('.'+$(this).attr('type')+'-sign').addClass('checked');
					} else {
						$(this).next('.'+$(this).attr('type')+'-sign').removeClass('checked');
					}
				});
			});
			if($el.is(':checked')) {
				$checkbox.addClass('checked');
			} else {
				$checkbox.removeClass('checked');
			}
		});
	}
	$.fn.combobox = function() {
		$(this).each(function() {
			var $el = $(this);
			$el.insertBefore($el.parent('.combobox-wrapper'));
			$el.next('.combobox-wrapper').remove();
			$el.css({
				'opacity': 0,
				'position': 'absolute',
				'left': 0,
				'right': 0,
				'top': 0,
				'bottom': 0
			});
			var $comboWrap = $('<span class="combobox-wrapper" />').insertAfter($el);
			var $text = $('<span class="combobox-text" />').appendTo($comboWrap);
			var $button = $('<span class="combobox-button" />').appendTo($comboWrap);
			$el.appendTo($comboWrap);
			$el.change(function() {
				$text.text($('option:selected', $el).text());
			});
			$text.text($('option:selected', $el).text());
			$el.comboWrap = $comboWrap;
		});
	}

	$(':input').each(function() {
		const $input = $(this);
		if (!$input.attr('aria-label')) {
			var nameValue = $input.attr('name');
			if($input.is('button') && $.trim($input.text()) === '' && !nameValue) {
				nameValue = $input.attr('type');
				$input.attr('aria-label', nameValue);
			}
			if (nameValue) {
				if($input.closest('label').length && $.trim($input.closest('label').text()) === '' && !$input.closest('label').attr('aria-label')) {
					$input.closest('label').attr('aria-label', nameValue);
					$input.attr('aria-label', nameValue);
				} else {
					$input.attr('aria-label', nameValue);
				}
			}
		}
	});

	$('label').each(function() {
		const $label = $(this);

		if ($.trim($label.text()) === '') {
			if ($label.attr('aria-label')) return;
			let $linkedInput = null;

			const forAttr = $label.attr('for');
			if (forAttr) {
				$linkedInput = $('#' + forAttr);
			} else {
				$linkedInput = $label.find(':input').first();
			}

			if ($linkedInput && $linkedInput.length > 0) {
				const inputName = $linkedInput.attr('name');
				if (inputName) {
					$label.attr('aria-label', inputName);
				}
			}
		}
	});

})(jQuery);