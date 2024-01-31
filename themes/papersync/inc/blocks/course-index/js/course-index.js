/**
 * Fetches the course index data from the server.
 *
 * @param {string} action
 * @param {number} id
 */
// eslint-disable-next-line no-unused-vars
function doFetch(action, id) {
	const elements = document.getElementsByClassName('course-index-element');
	/* eslint-disable no-undef */
	const ajaxUrl = papersyncCourseIndex.ajaxurl
		? papersyncCourseIndex.ajaxurl
		: ajaxurl;
	/* eslint-enable no-undef */
	for (let i = 0; i < elements.length; i++) {
		elements[i].classList.add('pika');
	}
	fetch(ajaxUrl + '?action=' + action + '&id=' + id)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				// document.getElementById('course-index').innerHTML = data.html;
				fetch(
					ajaxUrl + '?action=index_course&id=' + data.data.ancestor_id
				)
					.then((response) => response.json())
					.then((data2) => {
						if (data2.success) {
							document.getElementById('course-index').innerHTML =
								data2.data.html;
						}
					});
			} else {
				for (let i = 0; i < elements.length; i++) {
					elements[i].classList.remove('pika');
				}
			}
		});
}
