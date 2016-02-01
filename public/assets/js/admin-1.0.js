(function(window) {

    var Model = (function() {
        function Model(opts) {
            this.api = window.location.origin + '/';
            this.ext = '.json';
        }

        Model.prototype = {
            create: function(opts) {
                var self = this,
                    link = this._clean(this.api) + this._clean(opts.action) + this._clean(this.ext);
                $.ajax({
                    url: link,
                    type: 'POST',
                    data: opts.data,
                }).done(function(data) {
                    if (opts.callback) {
                        opts.callback.call(self, data);
                    }
                }).fail(function() {
                    console.log("error");
                }).always(function() {
                    //console.log("complete");
                });
            },
            read: function(opts) {
                var self = this,
                    link = this._clean(this.api) + this._clean(opts.action) + this._clean(this.ext);
                $.ajax({
                    url: link,
                    type: 'GET',
                    data: opts.data,
                }).done(function(data) {
                    if (opts.callback) {
                        opts.callback.call(self, data);
                    }
                }).fail(function() {
                    console.log("error");
                }).always(function() {
                    //console.log("complete");
                });

            },
            _clean: function(entity) {
                return entity || "";
            }
        };
        return Model;
    }());

    Model.initialize = function(opts) {
        return new Model(opts);
    };

    window.Model = Model;
}(window));

(function(window, Model) {
    window.request = Model.initialize();
    window.opts = {};
}(window, window.Model));

$(function() {
    $('#side-menu').metisMenu();
});

$(function() {
    $('select[value]').each(function() {
        $(this).val(this.getAttribute("value"));
    });
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1)
            height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});

$(document).ready(function() {

    //initialize beautiful datetime picker
    $("input[type=date]").datepicker();
    $("input[type=date]").datepicker("option", "dateFormat", "yy-mm-dd");
    var dateFormat = $("input[type=date]").datepicker("option", "dateFormat");
    $("input[type=date]").datepicker("option", "dateFormat", "yy-mm-dd");

    $('#created_stats').submit(function(e) {
        $('#stats').html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></p>');
        e.preventDefault();
        var data = $(this).serializeArray();
        request.read({
            action: "admin/dataAnalysis",
            data: data,
            callback: function(data) {
                $('#stats').html('');
                if (data.data) {
                    Morris.Bar({
                        element: 'stats',
                        data: toArray(data.data),
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Total']
                    });
                }
            }
        });
    });

    $("#searchModel").change(function() {
        var self = $(this);
        $('#searchField').html('');
        request.read({
            action: "admin/fields/" + this.value,
            callback: function(data) {
                var d = $.parseJSON(data);
                $.each(d, function(field, property) {
                    $('#searchField').append('<option value="' + field + '">' + field + '</option>');
                })
            }
        });
    });

    $(document).on('change', '#searchField', function(event) {
        var fields = ["created", "modified"],
            date = $.inArray(this.value, fields);
        if (date !== -1) {
            $("input[name=value]").val('');
            $("input[name=value]").datepicker();
            $("input[name=value]").datepicker("option", "dateFormat", "yy-mm-dd");
        };
    });

});

function today() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }

    today = yyyy + '-' + mm + '-' + dd;
    return today;
}

function readImage() {
    if (this.files && this.files[0]) {
        var FR = new FileReader();
        FR.onload = function(e) {
            $('#img').attr("src", e.target.result);
        };
        FR.readAsDataURL(this.files[0]);
    }
}

$('#img').click(function(e) {
    var offset = $(this).offset();
    var x = e.pageX - offset.left,
        y = e.pageY - offset.top;
    
    if($('input[value=src]').is(':checked')) {
        $("input[name=src_x]").val(x);
        $("input[name=src_y]").val(y);
    }

    if($('input[value=txt]').is(':checked')) {
        $("input[name=txt_x]").val(x);
        $("input[name=txt_y]").val(y);
    }

    if($('input[value=usr]').is(':checked')) {
        $("input[name=usr_x]").val(x);
        $("input[name=usr_y]").val(y);
    }
});
