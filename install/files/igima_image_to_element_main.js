$(document).ready(function(){
    
});
function IgimaImageToElementGetFiles()
{
    $('#imagetoelement-create-elements').addClass('adm-btn-save');
    $('.igima-image-to-element #img-container').on('click','.delete-img-list',function(e) {
        $(this).closest('tr').remove();
        e.preventDefault();
    });
    var dropBox = $('.igima-image-to-element #drop-box');
    var fileInput = $('.igima-image-to-element #file-field');
    var imgList = $('.igima-image-to-element #img-list');
    fileInput.on({
        change: function() {
            IgimaImageToElementDisplayFiles(this.files);
        }
    });
    dropBox.on({
        dragenter: function() {
            return false;
        },
        dragover: function() {
            $(this).addClass('highlighted');
            return false;
        },
        dragleave: function() {
            $(this).removeClass('highlighted');
            return false;
        },
        drop: function(e) {
            $(this).removeClass('highlighted');
            var dt = e.originalEvent.dataTransfer;
            IgimaImageToElementDisplayFiles(dt.files);
            return false;
        }
    });
    $("#imagetoelement-create-elements").click(function() {
        
        imgList.find('tr').each(function() {
            var uploadItem = this;
            var pBar = $(uploadItem).find('.progress');
            var fileName = $(uploadItem).find('.element-name');
            if (parseInt($(pBar).attr('rel')) !== 100)
            {
                new IgimaImageToElementUploaderObject({
                    file:       uploadItem.file,
                    url:        '/bitrix/admin/igima_imagetoelement_upload.php',
                    fieldName:  'my-pic',
                    fileName:   fileName.val(),
                    iblockId:   $('.igima-image-to-element #drop-box').attr('data-iblock'),
                    sid:   $('.igima-image-to-element #drop-box').attr('data-sid'),
                    charset: $('.igima-image-to-element #drop-box').attr('data-charset'),

                    onprogress: function(percents) {
                        IgimaImageToElementUpdateProgress(pBar, percents);
                    },
                
                    oncomplete: function(done) {
                        if(done) {
                            pBar.css('background-color','#73E600');
                            IgimaImageToElementUpdateProgress(pBar, 100);
                        } else {
                            pBar.html(BX.message('IGIMAIMAGETOELEMENT_GET_MENU_ERROR'));
                            pBar.css('background-color','#FF3300');
                        }
                    }
                });
            }
        });
    });
}
function IgimaImageToElementDisplayFiles(files) {
    var imgList = $('.igima-image-to-element table#img-list');
    imgList.show();
    $.each(files, function(i, file) {      
      if (!file.type.match(/image.*/)) {
        return true;
      }           
      var li = $('<tr/>').appendTo(imgList);
      var tdimg = $('<td/>').appendTo(li);
      var img = $('<img/>').appendTo(tdimg);
      var tdinp = $('<td/>').appendTo(li);
      $('<input type="text" name="element-name" class="element-name" />').val(file.name).appendTo(tdinp);
      var tdspan = $('<td/>').appendTo(li);
      $('<span/>').addClass('progress').text(BX.message('IGIMAIMAGETOELEMENT_GET_MENU_READY_TO_GO')).appendTo(tdspan);
      $('<td><a href="#" class="delete-img-list"><img src="/bitrix/images/igima.imagetoelement/delete-icon.png" alt="" /></a></td>').appendTo(li);
      li.get(0).file = file;
      var reader = new FileReader();
      reader.onload = (function(aImg) {
        return function(e) {
          aImg.attr('src', e.target.result);
          aImg.attr('width', 90);
        };
      })(img);
      reader.readAsDataURL(file);
    });
  }
  function IgimaImageToElementUpdateProgress(bar, value) {
        var width = bar.width();
        var bgrValue = -width + (value * (width / 100));
        bar.attr('rel', value).css('background-position', bgrValue+'px center').text(BX.message('IGIMAIMAGETOELEMENT_GET_MENU_UPLOADING')+' - '+value+'%');
    }
var IgimaImageToElementUploaderObject = function(params) {

    if(!params.file || !params.url) {
        return false;
    }

    this.xhr = new XMLHttpRequest();
    this.reader = new FileReader();

    this.progress = 0;
    this.uploaded = false;
    this.successful = false;
    this.lastError = false;
    
    var self = this;    

    self.reader.onload = function() {
        self.xhr.upload.addEventListener("progress", function(e) {
            if (e.lengthComputable) {
                self.progress = (e.loaded * 100) / e.total;
                if(params.onprogress instanceof Function) {
                    params.onprogress.call(self, Math.round(self.progress));
                }
            }
        }, false);

        self.xhr.upload.addEventListener("load", function(){
            self.progress = 100;
            self.uploaded = true;
        }, false);

        self.xhr.upload.addEventListener("error", function(){            
            self.lastError = {
                code: 1,
                text: 'Error uploading on server'
            };
        }, false);

        self.xhr.onreadystatechange = function () {
            var callbackDefined = params.oncomplete instanceof Function;
            if (this.readyState === 4) {
                if(this.status === 200) {
                    if(!self.uploaded) {
                        if(callbackDefined) {
                            params.oncomplete.call(self, false);
                        }
                    } else {
                        self.successful = true;
                        if(callbackDefined) {
                            params.oncomplete.call(self, true, this.responseText);
                        }
                    }
                } else {
                    self.lastError = {
                        code: this.status,
                        text: 'HTTP response code is not OK ('+this.status+')'
                    };
                    if(callbackDefined) {
                        params.oncomplete.call(self, false);
                    }
                }
            }
        };

        self.xhr.open("POST", params.url);

        var boundary = "xxxxxxxxx";
        var mime = params.file.name.split('.');
        self.xhr.setRequestHeader("Content-Type", "multipart/form-data, boundary="+boundary);
        self.xhr.setRequestHeader("Cache-Control", "no-cache");
        self.xhr.setRequestHeader("iblockId", params.iblockId);
        self.xhr.setRequestHeader("sid", params.sid);
        self.xhr.setRequestHeader("elName", encodeURIComponent(params.fileName));
        self.xhr.setRequestHeader("charset", params.charset);
        var body = "--" + boundary + "\r\n";
        body += "Content-Disposition: form-data; name='"+(params.fieldName || 'file')+"'; filename='" + encodeURIComponent(params.file.name) + "'\r\n";
        body += "Content-Type: image/"+mime[1]+"\r\n\r\n";
        body += self.reader.result + "\r\n";
        body += "--" + boundary + "--";
        if(self.xhr.sendAsBinary) {
            // firefox
            self.xhr.sendAsBinary(body);
        } else {
            // chrome (W3C spec.)
            self.xhr.send(body);
        }

    };

    self.reader.readAsBinaryString(params.file);
};