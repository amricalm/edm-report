var arrDialogRef = [];
var curDialogRef = null;

// on load of application
$(function () {
    console.log("Siap...");
    $("#wait-grid").css("display", "none");

    $('.number').autoNumeric('init',{
        aSep: '.', aDec: ',' , vMin:'0'
    });

    $('a.dialog').ajaxStart(function () {
        $("#wait").css("display", "block");
    });
    $('a.dialog').ajaxComplete(function () {
        $("#wait").css("display", "none");
    });

    curDialogRef = $("#barang-grid-container");

    $(document).ajaxStart(function () {
        console.log('ajaxStart');
        $("#wait-grid").css("display", "block");
    });
    $(document).ajaxComplete(function () {
        var delay = 1000; 

        setTimeout(function () {
            $("#wait-grid").css("display", "none");
        }, delay);
        
    });

    var currentLinkRef;
    $(document).on("click", "a.dialog,button.dialog", function (event) {
        console.log('click dialog');
        event.preventDefault();
        //currentLinkRef = this;
        //var url = $(this).attr("href");
       // open_container(url, $(this).attr('data-adnmode'));

  
        $('#modal-lookup').modal('show');


        //var currentLinkRef = this;
        //var url = $(this).attr("href");
        //var title = $(this).attr("title");
        //var dialog = $("<div></div>");
        //console.log('dialog');
        //$(dialog)
        //    //.load(url)
        //    .dialog({
        //        title: title,
        //        autoOpen: false,
        //        resizable: false,
        //        height: 'auto',
        //        width: '50%',
        //        minWidth: '400',
        //        minHeight: '400',
        //        show: { effect: 'drop', direction: "up" },
        //        modal: true,
        //        draggable: true,
        //        open: function (event, ui) {

        //            var me = this;

        //            // storing reference
        //            arrDialogRef.push({
        //                Source: curDialogRef,// parent dialog reference
        //                Destination: me, // child or open dialog refernce
        //                Element: currentLinkRef, // current link reference
        //            });

        //            curDialogRef = me;
        //        },
        //        close: function (event, ui) {

        //            // releasing resource of dialog on close event
        //            var me = this;
        //            $.each(arrDialogRef, function (i, val) {

        //                if (val.Destination == me) {
        //                    curDialogRef = val.Source;
        //                    arrDialogRef.splice(i, 1);
        //                    return false;
        //                }
        //            });

        //            $(this).empty().dialog('destroy').remove();
        //        }
        //    });

        //$(dialog).dialog('open');
    });
    $(document).on("click", "a.delete", function (event) {

        event.preventDefault();
        ShowConfirmYesNo($(this).data('id'));
        //var url = $(this).attr("href");

        //showMessageBox({
        //    title: "Question",
        //    content: "Are you sure want to delete this record?",
        //    btn1text: "Yes",
        //    btn2text: "No",
        //    functionText: "makeAjaxCall",
        //    parameterList: {
        //        url: url,
        //        callback: 'updateSection'
        //    }
        //});
    });

    $('#myModal').on('show.bs.modal', function (e) {
        var me = this;
        arrDialogRef.push({
            Source: curDialogRef,// parent dialog reference
            Destination: me, // child or open dialog refernce
            Element: currentLinkRef, // current link reference
        });
        curDialogRef = me;
    });
    $('#myModal').on('hide.bs.modal', function (e) {
        var me = this;
        $.each(arrDialogRef, function (i, val) {

            if (val.Destination == me) {
                curDialogRef = val.Source;
                arrDialogRef.splice(i, 1);
                return false;
            }
        });
    });

    updateSection(); // update the section of product list on load

});

function onAjaxFailure(response) {
    console.log(response);

    var data = $.parseJSON(response.responseJSON);
    var content = "<ul>";
    for (var key in data) {
        content += "<ol>" + data[key] + "</ol>";
    }

    content += "</ul>"

    showMessageBox({
        title: "Error",
        content: content,
        btn1text: "Ok"
    });
}

function showMessageBox(params) {

    var btn1css;
    var btn2css;

    if (params.btn1text) {
        btn1css = "showcss";
    } else {
        btn1css = "hidecss";
    }

    if (params.btn2text) {
        btn2css = "showcss";
    } else {
        btn2css = "hidecss";
    }

    $("#lblMessage").html(params.content);

    $("#dialog").dialog({
        resizable: false,
        title: params.title,
        modal: true,
        width: 'auto',
        height: 'auto',
        bgiframe: false,
        hide: { effect: 'scale', duration: 400 },
        buttons: [
            {
                text: params.btn1text,
                "class": btn1css,
                click: function () {
                    if (params.functionText) { window[params.functionText](params.parameterList); } // Call function after clicking on button.
                    $("#dialog").dialog('close');
                }
            },
            {
                text: params.btn2text,
                "class": btn2css,
                click: function () {
                    $("#dialog").dialog('close');
                }
            }
        ]
    });
}

function updateSection() {
    console.log('updateSection...');
    var source = null;
    var updateContainerId = "";
    var ref = getParentDialogRef();
    if (ref && ref.Destination) {
        source = ref.Destination;
        updateContainerId = source;
    }
    else if (curDialogRef) {
        source = curDialogRef;
        updateContainerId = $("#barang-grid");
    }

    var url = $(source).find("form").attr("action");
    if (url) {

        //$.get(url, function (data) {
        //    $(updateContainerId).html(data);
        //});
    }
}

function getParentDialogRef() {
    if (arrDialogRef && arrDialogRef.length > 0) {
        return arrDialogRef[arrDialogRef.length - 1];
    }

    return null;
}

function closeDialog() {
    var ref = getParentDialogRef();
    if (ref) {
        $(ref.Destination).modal('hide');
    }
}

function updateContainer(response) {
    var ref = getParentDialogRef();

    if (ref) {

        $(ref.Element).parent().find("input[type='hidden']").val(response.Id);
        $(ref.Element).parent().find("input[type='text']").val(response.Name);

        closeDialog();
    }
}

function updateSearchContainer(response) {
    var ref = getParentDialogRef();
    if (ref) {
        $(ref.Destination).html(response);
    }
}

function makeAjaxCall(params) {

    $.ajax({
        type: 'POST',
        url: params.url,
        data: params.parameters,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                eval(params.callback + '(response)');
            }
        }
    })
}

function open_container(url,adnmode)
{
    var size = 'small';
    var content = '';
    var title = 'Update Barang';
    var footer = '<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button><button id="proses" type="button" class="btn btn-primary">Simpan</button>';

    if (adnmode == 'ADD') { title = 'Tambah Barang'; }

    setModalBox(title, url, footer, size);
    $('#myModal').modal('show');
}
function setModalBox(title,url,footer,$size)
{

    if (url) {
        $.get(url, function (data) {
            $('#modal-bodyku').html(data);
        });
    }

    $('#myModalLabel').html(title);
    $('#modal-footerq').html(footer);
    if ($size == 'large')
    {
        $('#myModal').attr('class', 'modal fade bs-example-modal-lg')
                     .attr('aria-labelledby','myLargeModalLabel');
        $('.modal-dialog').attr('class','modal-dialog modal-lg');
    }
    if($size == 'standart')
    {
        $('#myModal').attr('class', 'modal fade')
                     .attr('aria-labelledby','myModalLabel');
        $('.modal-dialog').attr('class','modal-dialog');
    }
    if($size == 'small')
    {
        $('#myModal').attr('class', 'modal fade bs-example-modal-sm')
                     .attr('aria-labelledby','mySmallModalLabel');
        $('.modal-dialog').attr('class','modal-dialog modal-sm');
    }


}

function getSelectedText(elementId) {
    var elt = document.getElementById(elementId);

    if (elt.selectedIndex == -1)
        return null;

    return elt.options[elt.selectedIndex].text;
}

function AdnToNum(str) {
    var nilai = 0;
    if (str!=undefined) {
        var nilai = parseFloat(str.toString().replace(/\./g, ""));
        if (isNaN(nilai)) nilai = 0;
    }
    return nilai;
}

function AdnNumToString(num)
{
    var nf = new NumberFormat(num);
    nf.setPlaces(0);
    nf.setSeparators(true, '.', ',');
    var nilai = nf.toFormatted();
    return nilai;
}

function AdnFormatNum(str)
{
    if (str===null)
        str = 0;
    var nilai = parseFloat(str.toString().replace(/\./g, ""));
    if (isNaN(nilai)) nilai = 0;

    var nf = new NumberFormat(nilai);
    nf.setPlaces(0);
    nf.setSeparators(true, '.', ',');
    var nilai = nf.toFormatted();
    return nilai;
}

function AdnIsEmpty(obj) {
    if(Object.keys(obj).length === 0 && obj.constructor === Object)
    {
        return true;// Objek Kosong
    }
    else
    {
        return false;
    }
}

function AdnToString(str){
    var hasil = '';
    if (str===null)
    {
        str ='';
    }
    if (str.toString()!=='NULL')
    {
        hasil = str.trim();
    }
    return hasil;

}

function clearForm(myFormElement) {

    var elements = myFormElement.elements;
  
    myFormElement.reset();
  
    for(i=0; i<elements.length; i++) {
  
        field_type = elements[i].type.toLowerCase();
  
        switch(field_type) {
    
            case "text":
            case "password":
            case "textarea":
                    case "hidden":
        
                elements[i].value = "";
                break;
        
            case "radio":
            case "checkbox":
                if (elements[i].checked) {
                    elements[i].checked = false;
                }
                break;
        
            case "select-one":
            case "select-multi":
                        elements[i].selectedIndex = -1;
                break;
        
            default:
                break;
            }
      }
}

function AdnIsNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
