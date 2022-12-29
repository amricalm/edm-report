//andhana
(function ($) {

    $.AdnPesanSukses = function (pesan) {
        // var markup = [
        //     '<div id="KotakOverlay" class="kotak-overlay">',
        //     '<div id="KotakPesan" class="kotak-pesan kotak-tengah">',
        //     '<div class="isi">',
        //     '<div id="Perhatian" class="kotak-gambar"><image class="kotak-img" src="/img/success.png"></div>',
        //     '<div class="kotak-tulisan">',
        //     '<h4 style="margin-top:0px;"></h4>',
        //     '<p style="font-weight:bold;">' + pesan + '</p>',
        //     '</div>',
        //     '</div>',
        //     '<div id="KotakTombol" class="kotak-tombol">',
        //     '<button id="kotak-pesan-OK" class="btn-ok" autofocus>OK</button>',
        //     '<div style="clear:both;"></div>',
        //     '</div>',
        //     '</div></div>'
        // ].join('');
       
        var markup=[
            '<div class="alert alert-success"  style="margin: 0;position: absolute;top: 50%;left: 10%;-ms-transform: translateY(-50%);transform: translateY(-50%);display: none;width: 80%;" >',
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>',
                        '<strong>Success Messagexxx</strong>',
                    '<hr class="message-inner-separator">',
                    '<p>You successfully read this important alert message.</p>',
                '</div>'
        ].join('');

        $('body').delegate('#KotakPesan button', 'click', function () {
            $.AdnPesanSukses.hide();
        });
        $(markup).hide().appendTo('body').fadeIn('slow');

        $.AdnPesanSukses.hide = function () {
            $('#KotakOverlay').fadeOut(function () {
                $(this).remove();
            });
        }
    }

    $.AdnPesanPerhatian = function (pesan) {

        // var markup = [
        //     '<div id="KotakOverlay" class="kotak-overlay">',
        //     '<div id="KotakPesan" class="kotak-pesan kotak-tengah">',
        //     '<div class="isi">',
        //     '<div id="Perhatian" class="kotak-gambar"><image class="kotak-img"  src="/img/alert.png"></div>',
        //     '<div class="kotak-tulisan">',
        //     '<h4 style="margin-top:0px;"></h4>',
        //     '<p style="font-weight:bold;">' + pesan + '</p>',
        //     '</div>',
        //     '</div>',
        //     '<div id="KotakTombol" class="kotak-tombol">',
        //     '<button id="kotak-pesan-OK" class="btn-ok" autofocus>OK</button>',
        //     '<div style="clear:both;"></div>',
        //     '</div>',
        //     '</div></div>'
        // ].join('');

        // var markup =[
        //     '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">',
        //         '<div class="toast-header">',
        //             '<img src="..." class="rounded me-2" alt="...">',
        //             '<strong class="me-auto">Bootstrap</strong>',
        //             '<small>11 mins ago</small>',
        //             '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>',
        //         '</div>',
        //         '<div class="toast-body">',
        //             'Hello, world! This is a toast message.',
        //         '</div>',
        //     '</div>'
        // ].join('');

        var markup=[
            '<div class="alert alert-success">',
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>',
                        '<strong>Success Message</strong>',
                    '<hr class="message-inner-separator">',
                    '<p>You successfully read this important alert message.</p>',
                '</div>'
        ].join('');

        // $('body').delegate('#KotakPesan button', 'click', function () {
        //     $.AdnPesanPerhatian.hide();
        // });
        $(markup).hide().appendTo('body').fadeIn('slow');

        $.AdnPesanPerhatian.hide = function () {
            $('#KotakOverlay').fadeOut(function () {
                $(this).remove();
            });
        }
    }

}(jQuery))

function getAdnToken()
{
    var headers = {};
    headers["__RequestVerificationToken"] = $('[name=__RequestVerificationToken]').val();
    return headers;
}

function PopupCetak(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}

// Usage:
//   var data = { 'first name': 'Adn', 'last name': 'Yan', 'age': 70 };
//   var querystring = EncodeQueryData(data);
// 
function EncodeQueryData(data) {
    var ret = [];
    for (var d in data)
        console.log(d);
    console.log(data[d]);
        ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
    return ret.join("&");
}

function AddQSParam(url, name, value) {
    var re = new RegExp("([?&]" + name + "=)[^&]+", "");

    function add(sep) {
        url += sep + name + "=" + encodeURIComponent(value);
    }

    function change() {
        url = url.replace(re, "$1" + encodeURIComponent(value));
    }
    if (url.indexOf("?") === -1) {
        add("?");
    } else {
        if (re.test(url)) {
            change();
        } else {
            add("&");
        }
    }
    return url;
}


// jQuery(function($)
// {
//     $.validator.addMethod('date', function (value, element) {
//         if (this.optional(element)) {
//             return true;
//         }
//         var ok = true;
//         try{
//             var m = moment(value, 'DD-MM-YYYY');
//             ok = m.isValid();
//         }
//         catch(err)
//         {
//             ok = false;
//         }
//         return ok;
//     })
// })


