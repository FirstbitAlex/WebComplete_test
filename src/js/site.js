jQuery(document).ready(($) => {
	const postList = $('#post-list');
	const loadMore = $('#load-more');
	const filterItems = $('.filter-nav__item');

	const updateUrlParam = (slug) => {
		const params = new URLSearchParams(window.location.search);

		if (!slug) {
			params.delete('article-topic');
		} else {
			params.set('article-topic', slug);
		}

		const newUrl = window.location.pathname + (params.toString() ? `?${params.toString()}` : '');
		history.pushState(null, '', newUrl);
	};

	// filters
	filterItems.on('click', (e) => {
		e.preventDefault();

		const $this = $(e.currentTarget);
		const slug = $this.data('term-slug');

		const options = {
			action: 'get_articles',
			slug,
			loadmore_ppp: 0,
			offset: 0,
		};

		$.ajax({
			url: articleAjax.ajax_url,
			type: 'POST',
			data: options,
			dataType: 'json',
			success: (response) => {
				filterItems.removeClass('active');
				$this.addClass('active');

				postList.html(response.post_list);
				loadMore.data('offset', response.offset);

				$('.section--load-more').toggleClass('show', !!response.is_show_load_more);

				updateUrlParam(slug);
			},
		});
	});

	// load more
	loadMore.on('click', (e) => {
		e.preventDefault();

		const $this = $(e.currentTarget);
		const options = { ...$this.data(), action: 'get_articles' };

		$.ajax({
			url: articleAjax.ajax_url,
			type: 'POST',
			data: options,
			dataType: 'json',
			success: (response) => {
				postList.append(response.post_list);
				$this.data('offset', response.offset);

				if (!response.is_show_load_more) {
					$('.section--load-more').removeClass('show');
				}
			},
		});
	});
});
