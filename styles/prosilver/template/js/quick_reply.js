;(function($, window, document) {
	var message = $('span#marttiphpbb-replytemplate-message').data('message');
	$('div#message-box textarea').text(message);
})(jQuery, window, document);
