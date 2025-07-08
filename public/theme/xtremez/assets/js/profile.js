$(function () {
	// Delegated event for edit buttons
	$("body").on("click", ".btn-edit", function () {
		const $btn = $(this);
		const $input = $btn.prev();

		if ($input.prop("readonly")) {
			$input.prop("readonly", false).focus();
			$btn.html('<i class="bi bi-check-lg"></i>');
		} else {
			$input.prop("readonly", true);
			$btn.html('<i class="bi bi-pencil"></i>');
		}
	});

	// Delegated radio change
	$("body").on("change", ".gender-toggle input[type=radio]", function () {
		const $group = $(this).closest(".gender-toggle");
		$group.find("label").removeClass("active");
		$group.find('label[for="' + this.id + '"]').addClass("active");
	});

	// Delegated label click fallback
	$("body").on("click", ".gender-toggle label", function () {
		const $group = $(this).closest(".gender-toggle");
		$group.find("label").removeClass("active");
		$(this).addClass("active");
	});
});

$(function () {
	const isMobile = window.innerWidth < 992;
	const contentContainer = $("#profileContent");
	const mobileAccordion = $("#mobileProfileAccordion");
	const defaultTab = "profile";

	// Load HTML component into given target
	function loadTabContent(tabId, $target, callback = null) {
		const path = `components/profile/${tabId}.html`;
		$.ajax({
			url: path,
			method: "GET",
			success: function (data) {
				$target.html(data);
				if (typeof callback === "function") callback();
			},
			error: function () {
				$target.html('<div class="p-3 text-danger">Failed to load content.</div>');
			},
		});
	}


	function handleSidebarClick($el) {
		const tabId = $el.data("tab");
		const heading = $el.data("heading");

		$(".profile-link").removeClass("active");
		$el.addClass("active");
		$(".section-title").text(heading);
		loadTabContent(tabId, contentContainer);

		localStorage.setItem("activeProfileTab", tabId);
	}


	function buildAccordion() {
		$(".profile-link").each(function (index) {
			const tabId = $(this).data("tab");
			const heading = $(this).data("heading");
			const collapseId = `collapse-${tabId}`;
			const isActive = getActiveTab() === tabId;

			const accordionItem = $(`
				<div class="accordion-item">
					<h2 class="accordion-header" id="heading-${tabId}">
						<button class="accordion-button ${!isActive ? "collapsed" : ""}" type="button"
							data-bs-toggle="collapse" data-bs-target="#${collapseId}"
							aria-expanded="${isActive}" aria-controls="${collapseId}">
							${heading}
						</button>
					</h2>
					<div id="${collapseId}" class="accordion-collapse collapse ${isActive ? "show" : ""}"
						aria-labelledby="heading-${tabId}" data-bs-parent="#mobileProfileAccordion">
						<div class="accordion-body p-0" data-loaded="false"></div>
					</div>
				</div>
			`);

			mobileAccordion.append(accordionItem);
		});
	}

	function getActiveTab() {
		return localStorage.getItem("activeProfileTab") || defaultTab;
	}

	function registerAccordionEvents() {
		mobileAccordion.on("show.bs.collapse", function (e) {
			const $collapse = $(e.target);
			const tabId = $collapse.attr("id").replace("collapse-", "");
			const $body = $collapse.find(".accordion-body");

			if ($body.data("loaded") === false) {
				loadTabContent(tabId, $body);
				$body.data("loaded", true);
			}
		});

		mobileAccordion.on("hide.bs.collapse", function (e) {
			const $collapse = $(e.target);
			const $body = $collapse.find(".accordion-body");
			$body.empty().data("loaded", false);
		});
	}

	if (isMobile) {
		buildAccordion();
		registerAccordionEvents();

		const activeId = getActiveTab();
		const $initialCollapse = $(`#collapse-${activeId}`);
		if ($initialCollapse.length) {
			const $body = $initialCollapse.find(".accordion-body");
			loadTabContent(activeId, $body);
			$body.data("loaded", true);
		}
	} else {
		// Desktop: load content into main container
		const initialTab = getActiveTab();
		const $initialLink = $(`.profile-link[data-tab="${initialTab}"]`);
		if ($initialLink.length) handleSidebarClick($initialLink);

		// Register sidebar click events
		$(".profile-link").on("click", function (e) {
			e.preventDefault();
			handleSidebarClick($(this));
		});
	}
});

