(function (window, Model) {
    window.request = Model.initialize();
    window.opts = {};
}(window, window.Model));

// sandbox disable popups
if (window.self !== window.top && window.name != "view1") {;
    window.alert = function() { /*disable alert*/ };
    window.confirm = function() { /*disable confirm*/ };
    window.prompt = function() { /*disable prompt*/ };
    window.open = function() { /*disable open*/ };
}

// prevent href=# click jump
document.addEventListener("DOMContentLoaded", function() {
    var links = document.getElementsByTagName("A");
    for (var i = 0; i < links.length; i++) {
        if (links[i].href.indexOf('#') != -1) {
            links[i].addEventListener("click", function(e) {
                console.debug("prevent href=# click");
                if (this.hash) {
                    if (this.hash == "#") {
                        e.preventDefault();
                        return false;
                    } else {
                        /*
                        var el = document.getElementById(this.hash.replace(/#/, ""));
                        if (el) {
                          el.scrollIntoView(true);
                        }
                        */
                    }
                }
                return false;
            })
        }
    }
}, false);

(function($) {
    "use strict";

    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 60
    });

    new WOW().init();

    $('a.page-scroll').bind('click', function(event) {
        var $ele = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($ele.attr('href')).offset().top - 60)
        }, 1450, 'easeInOutExpo');
        event.preventDefault();
    });

    $('#collapsingNavbar li a').click(function() {
        /* always close responsive nav after click */
        $('.navbar-toggler:visible').click();
    });

    $('#galleryModal').on('show.bs.modal', function(e) {
        $('#galleryImage').attr("src", $(e.relatedTarget).data("src"));
    });

})(jQuery);

/**** FbModel: Controls facebook login/authentication ******/
(function(window, Home) {
    var FbModel = (function() {
        function FbModel() {
            this.loaded = false;
        }

        FbModel.prototype = {
            init: function(FB) {
                if (!FB) {
                    return false;
                }

                FB.init({
                    appId: '179747022387337',
                    version: 'v2.5'
                });
                this.loaded = true;
            },
            login: function(jQ) {
                var self = this;
                if (!this.loaded) {
                    self.init(window.FB);
                }
                window.FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                        self._info(jQ); // User logged into fb and app
                    } else {
                        window.FB.login(function(response) {
                            if (response.status === 'connected') {
                                self._info(jQ);
                            } else {
                                alert('Please allow access to your Facebook account, for us to enable direct login to the  DinchakApps');
                            }
                        }, {
                            scope: 'public_profile, email'
                        });
                    }
                });
            },
            _info: function(jQ) {
                window.FB.api('/me?fields=name,email,gender', function(response) {
                    console.log(response);
                    window.request.create({
                        action: 'auth/fbLogin',
                        data: {
                            action: 'fbLogin',
                            email: response.email,
                            name: response.name,
                            fbid: response.id,
                            gender: response.gender
                        },
                        callback: function(data) {
                            if (data.success == true) {
                                window.location.href = "/profile.html";
                            } else {
                                alert('Something went wrong');
                            }
                        }
                    });
                });
            }
        };
        return FbModel;
    }());

    window.FbModel = new FbModel();
}(window, window.Home));


$(document).ready(function() {
    $.ajaxSetup({cache: true});
    $.getScript('//connect.facebook.net/en_US/sdk.js', FbModel.init(window.FB));

    $("#fbLogin").on("click", function(e) {
        e.preventDefault();
        $(this).addClass('disabled');
        FbModel.login($);
        $(this).removeClass('disabled');
    });
});