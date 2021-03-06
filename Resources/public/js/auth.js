auth = {};

auth.fbLogin = function() {
	FB.login(function(response) {
		if (response.session) {
			if (response.perms) {
				window.location.reload();
			} else {
				// user is logged in, but did not grant any permissions
			}
		} else {
			// user is not logged in
		}
	}, {
		scope : 'read_stream,publish_stream,offline_access,email'
	});
};

auth.logout = function() {
	FB.logout(function(response) {
		window.location.reload();
		// Redirect to log 
	});
};