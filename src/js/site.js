jQuery(document).ready(function ($) {

	function getQueryParam(name) {
		var params = new URLSearchParams(window.location.search);
		return params.get(name);
	}

	function updateUrlParam(slug) {
		var params = new URLSearchParams(window.location.search);
		if (!slug) params.delete('article-topic');
		else params.set('article-topic', slug);
		var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
		history.pushState(null, '', newUrl);
	}

	function loadArticles(slug, options) {
		options = options || {};
		var data = {
			action: 'filter_articles',
			term_slug: slug || '',
		};

		if (options.initial) {
			data.initial = 1; // first 9 posts
		} else {
			// offset
			data.offset = options.offset || 0;
			data.posts_per_page = options.posts_per_page || 3;
		}

		if (!options.initial) {
			$('#load-more').text('Loading...');
		}

		return $.ajax({
			url: articleAjax.ajax_url,
			type: 'POST',
			dataType: 'html',
			data: data
		});
	}

	// for successful response
	function handleResponseAndUpdate(response, append) {
		var $tmp = $('<div>').html(response);
		var $items = $tmp.find('article');
		var returnedCount = $items.length;

		if (append) {
			$('#post-list').append($items);
		} else {
			$('#post-list').html($items);
		}

		var postsPerPageRequested = (append ? 3 : 9);
		if (returnedCount < postsPerPageRequested) {
			$('#load-more').hide();
		} else {
			$('#load-more').show();
		}
	}

	// init by load page
	var initialSlug = getQueryParam('article-topic');
	if (initialSlug) {
		// load first 9 posts by category
		loadArticles(initialSlug, { initial: true })
			.done(function (resp) {
				handleResponseAndUpdate(resp, false);
				updateUrlParam(initialSlug);
				$('#load-more').attr('data-page', 1);
			})
			.fail(function () {
				$('#post-list').html('<p>Loading error</p>');
				$('#load-more').hide();
			});
	}

	// filters
	var filters = $('.filter-nav__item');
	$(document).on('click', '.filter-nav__item', function (e) {
		e.preventDefault();
		var slug = $(this).data('term-slug') || '';
		var _this = $(this);
		// update URL
		updateUrlParam(slug);
		// reset counter
		$('#load-more').data('page', 1);
		// first 9 posts
		loadArticles(slug, { initial: true })
			.done(function (resp) {
				handleResponseAndUpdate(resp, false);
				filters.removeClass('active');
				_this.addClass('active');
			})
			.fail(function () {
				$('#post-list').html('<p>Error load</p>');
				$('#load-more').hide();
			});
	});

	// load more
	$(document).on('click', '#load-more', function (e) {
		e.preventDefault();
		var $btn = $(this);

		// how mach card is show
		var currentCount = $('#post-list').find('article').length || 0;
		var slug = $('.filter-nav__item.active').data('term-slug') || getQueryParam('article-topic') || '';

		loadArticles(slug, { offset: currentCount, posts_per_page: 3 })
			.done(function (resp) {
				// append
				handleResponseAndUpdate(resp, true);
				$btn.text('Load More');
			})
			.fail(function () {
				$btn.text('Error');
			});
	});
});
