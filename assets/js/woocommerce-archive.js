(function ($) {
	'use strict';

	function initProductArchive() {
		var $archive = $('[data-product-archive]').first();

		if (!$archive.length || typeof linkpvaProductArchive === 'undefined') {
			return;
		}

		var $filters = $archive.find('[data-product-filters]');
		var $grid = $archive.find('[data-product-grid]');
		var $resultCount = $archive.find('[data-product-result-count]');
		var $pagination = $archive.find('[data-product-pagination-wrap]');
		var $status = $archive.find('[data-product-status]');
		var $ordering = $archive.find('.woocommerce-ordering');
		var defaultOrderby = $ordering.find('select.orderby').val() || 'menu_order';
		var request = null;
		var requestSequence = 0;
		var minimumSkeletonTime = 1500;
		var lastSuccessfulHtml = $grid.html();

		function selectedValues(name) {
			return $filters.find('input[name="' + name + '[]"]:checked').map(function () {
				return this.value;
			}).get();
		}

		function currentState(page) {
			return {
				page: Math.max(1, parseInt(page || 1, 10)),
				product_category: selectedValues('product_category'),
				account_type: selectedValues('account_type'),
				account_age: selectedValues('account_age'),
				stock_status: selectedValues('stock_status'),
				orderby: $ordering.find('select.orderby').val() || defaultOrderby
			};
		}

		function updateUrl(state) {
			if (!window.history || !window.history.pushState) {
				return;
			}

			var url = new URL(window.location.href);
			var keys = ['product_category', 'account_type', 'account_age', 'stock_status'];

			keys.forEach(function (key) {
				url.searchParams.delete(key + '[]');
				state[key].forEach(function (value) {
					url.searchParams.append(key + '[]', value);
				});
			});

			if (state.orderby && state.orderby !== defaultOrderby) {
				url.searchParams.set('orderby', state.orderby);
			} else {
				url.searchParams.delete('orderby');
			}

			if (state.page > 1) {
				url.searchParams.set('product-page', state.page);
			} else {
				url.searchParams.delete('product-page');
			}

			url.searchParams.delete('paged');
			window.history.pushState({ linkpvaProductArchive: true }, '', url.toString());
		}

		function setLoading(loading) {
			$archive.toggleClass('is-loading', loading).attr('aria-busy', loading ? 'true' : 'false');
			if (loading) {
				$status.text(linkpvaProductArchive.strings.loading);
			}
		}

		function skeletonMarkup() {
			var count = Math.max(1, Math.min(9, parseInt($archive.attr('data-products-per-page') || 6, 10)));
			var cards = '';

			for (var index = 0; index < count; index++) {
				cards += '<div class="col-md-6 col-xl-4" aria-hidden="true">' +
					'<article class="linkpva-product-card linkpva-product-skeleton-card">' +
						'<div class="linkpva-product-visual skeleton-img"></div>' +
						'<div class="linkpva-product-body">' +
							'<span class="skeleton-line skeleton-category"></span>' +
							'<span class="skeleton-line skeleton-title"></span>' +
							'<span class="skeleton-line skeleton-feature"></span>' +
							'<span class="skeleton-line skeleton-feature is-short"></span>' +
							'<div class="linkpva-product-footer"><span class="skeleton-line skeleton-price"></span><span class="skeleton-line skeleton-link"></span></div>' +
						'</div>' +
					'</article>' +
				'</div>';
			}

			return cards;
		}

		function loadProducts(page, updateHistory) {
			var state = currentState(page);
			var requestId = ++requestSequence;
			var requestStarted = Date.now();

			function afterSkeletonDelay(callback) {
				var remaining = Math.max(0, minimumSkeletonTime - (Date.now() - requestStarted));
				window.setTimeout(function () {
					if (requestId === requestSequence) {
						callback();
					}
				}, remaining);
			}

			if (request && request.readyState !== 4) {
				request.abort();
			}

			setLoading(true);
			$grid.html(skeletonMarkup());
			request = $.ajax({
				url: linkpvaProductArchive.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'aventis_product_archive_ajax',
					nonce: linkpvaProductArchive.nonce,
					page: state.page,
					search: $archive.attr('data-search') || '',
					context: $archive.attr('data-context') || '{}',
					product_category: state.product_category,
					account_type: state.account_type,
					account_age: state.account_age,
					stock_status: state.stock_status,
					orderby: state.orderby
				}
			}).done(function (response) {
				afterSkeletonDelay(function () {
					if (!response || !response.success || !response.data) {
						$grid.html(lastSuccessfulHtml);
						$status.text(linkpvaProductArchive.strings.error);
						setLoading(false);
						return;
					}

					lastSuccessfulHtml = response.data.html || '';
					$grid.html(lastSuccessfulHtml);
					$resultCount.html(response.data.count_html || '');
					$pagination.html(response.data.pagination || '');
					$archive.attr('data-current-page', response.data.page || 1);
					$archive.attr('data-max-pages', response.data.max_pages || 1);
					$status.text(linkpvaProductArchive.strings.updated);
					setLoading(false);

					if (updateHistory !== false) {
						updateUrl(state);
					}
				});
			}).fail(function (_xhr, status) {
				if (status !== 'abort') {
					afterSkeletonDelay(function () {
						$grid.html(lastSuccessfulHtml);
						$status.text(linkpvaProductArchive.strings.error);
						setLoading(false);
					});
				}
			});
		}

		$filters.on('submit', function (event) {
			event.preventDefault();
			loadProducts(1, true);
		});

		$filters.on('change', 'input[type="checkbox"]', function () {
			loadProducts(1, true);
		});

		$filters.on('click', '[data-reset-product-filters]', function () {
			$filters.find('input[type="checkbox"]').prop('checked', false);
			loadProducts(1, true);
		});

		$ordering.on('submit', function (event) {
			event.preventDefault();
			loadProducts(1, true);
		});

		$ordering.on('change', 'select.orderby', function () {
			loadProducts(1, true);
		});

		$archive.on('click', '[data-product-pagination] a[data-page]', function (event) {
			event.preventDefault();
			loadProducts($(this).data('page'), true);
			$archive.get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
		});

		window.addEventListener('popstate', function () {
			var params = new URL(window.location.href).searchParams;

			['product_category', 'account_type', 'account_age', 'stock_status'].forEach(function (key) {
				var values = params.getAll(key + '[]');
				$filters.find('input[name="' + key + '[]"]').each(function () {
					this.checked = values.indexOf(this.value) !== -1;
				});
			});

			$ordering.find('select.orderby').val(params.get('orderby') || defaultOrderby);
			loadProducts(parseInt(params.get('product-page') || 1, 10), false);
		});

		var initialParams = new URL(window.location.href).searchParams;
		if (initialParams.getAll('product_category[]').length ||
			initialParams.getAll('account_type[]').length ||
			initialParams.getAll('account_age[]').length ||
			initialParams.getAll('stock_status[]').length ||
			initialParams.has('product-page')) {
			loadProducts(parseInt(initialParams.get('product-page') || 1, 10), false);
		}
	}

	$(initProductArchive);
})(jQuery);
