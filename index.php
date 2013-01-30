<?php
include'func.inc.php';
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $code = $_GET['code'];
    redirect($code);
    die();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<meta name="robots" content="follow,index" />
<link href="favicon.ico" rel="shortcut icon" />
<title>dragit.in | Image Host and URL Shortener</title>
<meta name="description" content="dragit.in allows quick sharing of images and links for social networks and online communities." />
<meta name="keywords" content="images, funny pictures, image host, image upload, image sharing, image resize" />
<meta name="viewport" content="width=device-width">
<link href='http://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet' type='text/css'>
<!-- jQuery UI styles -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" id="theme">
<!-- jQuery Image Gallery styles -->
<link rel="stylesheet" href="css/jquery.image-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the jQuery UI progress bars -->
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Generic page styles -->
<link rel="stylesheet" href="css/style.css">
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#url').focus();
    });

    function go(url) {
        $.post('url.php', { url: url }, function(data) {
            if (data=='error_no_url') {
                $('#message').html('<p>No URL Specified</p>');
            } else if (data=='error_invalid_url') {
                $('#message').html('<p>Invalid URL Given</p>');
            } else if (data=='error_is_min') {
                $('#message').html('<p>Already Shortened!</p>');
            } else {
                $('#url').val(data);
                $('#url').select();
                $('#message').html('<p>Your shortened URL has been delivered!</p>');
            }
        });
    }
</script>

<!-- Analytics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37655285-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<script type="text/javascript">
function SelectAll()
{
    document.getElementById(this.id).focus();
    document.getElementById(this.id).select();
}
</script>

</head>
<body>

<div id="wrapper">
    <div id="main">
        <!-- The file upload form used as target for the file upload widget -->
        <form id="fileupload" action="UploadHandler.php" method="POST" enctype="multipart/form-data">
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript><input type="hidden" name="redirect" value="http://blueimp.github.com/jQuery-File-Upload/"></noscript>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div id="logo"><img src="assets/draglogo.png" /></div>
                <div class="span7">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Add Images</span>
                        <input type="file" name="files[]" multiple>
                    </span>
                    <!--
                    <button type="submit" class="btn btn-primary start">
                        <i class="icon-upload icon-white"></i>
                        <span>Start upload</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="icon-ban-circle icon-white"></i>
                        <span>Cancel upload</span>
                    </button>
                    -->
                    <button type="button" class="btn btn-danger delete">
                        <i class="icon-trash icon-white"></i>
                        <span>Delete</span>
                    </button>
                    <input type="checkbox" class="toggle" /> All
                </div>
                <!-- The global progress information 
                <div class="span5 fileupload-progress fade">
                    <!-- The global progress bar 
                    <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="bar" style="width:0%;"></div>
                    </div>
                    
                    <!-- The extended global progress information
                    <div class="progress-extended">&nbsp;</div>
                    
                </div>
                -->
            </div>
            <!-- The loading indicator is shown during file processing -->
            <!--<div class="fileupload-loading"></div>-->
            <div id="dragit_note">Host pics by dragging them to this window, or use "Add Images"</div>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
            </tbody></table>
        </form>
    </div>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span><br /></td>
        <td class="name"><span>{%=file.name%}</span>
        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <div class="bar"></div>
        </div>
        {% if (file.error) { %}
            <span class="error" ><span class="label label-important">Error</span> {%=file.error%}</span>
        {% } else { %}
            <td colspan="1"></td>
        {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="error" colspan="1"><span class="label label-important">Upload Failed:</span> {%=file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="http://dragit.in/{%=file.name%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a><br />
                <span class="size">{%=o.formatFileSize(file.size)%}</span><br />

                <span class="delete">
                    <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="icon-trash icon-white"></i>
                    <span>Delete</span>
                    </button>
                    <input type="checkbox" name="delete" value="1">
                </span>
            {% } %}</td>

            <td class="image_urls">
                <div id="linksRow1">
                <div id="copyLinkButton">Direct Link <input type="text" id="imageLink" onclick="this.select();" size="35" value="http://dragit.in/{%=file.name%}"/></div>
                <div id="copyHTMLButton">HTML Tag <input type="text" id="HTMLLink" onclick="this.select();" size="35" value='<a href="http://dragit.in/{%=file.name%}"><img src="http://dragit.in/{%=file.name%}" alt="" title="Dragit.in Hosted" /></a>'/></div><br />
                </div>

                <div id="linksRow1">
                <div id="copyForumButton">Forum Tag <input type="text" id="forumLink" onclick="this.select();" size="35" value="[img]http://dragit.in/{%=file.name%}[/img]"/></div>
                <div id="copyForumURLButton">Forum Link <input type="text" id="forumURLLink" onclick="this.select();" size="34" value="[URL=http://dragit.in/{%=file.name%}][img]http://dragit.in/{%=file.name%}[/img][/URL]"/></div>
                </div>
            </td>
        {% } %}
    
    </tr>

            
{% } %}

</script>

<!-- Jquery Addins -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<!-- jQuery Image Gallery -->
<script src="http://blueimp.github.com/jQuery-Image-Gallery/js/jquery.image-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The File Upload jQuery UI plugin -->
<script src="js/jquery.fileupload-jui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->
<div id="footer">
    <div id="url_shorten">
        <div id="preMessage">or enter URL to shorten</div>
        
        <input type="text" class="urlfield" name="url" id="url" size="63" onkeydown="if (event.keyCode == 13 || event.which == 13) { go($('#url').val()); }" />
        <div class="urlSubmit"><input type="button" class="urlbutton" value="Shorten" onclick="go($('#url').val());" /></div>
        
        <div id="message"></div>    
        
        <div class="bottomAd">
            <script type="text/javascript"><!--
            google_ad_client = "ca-pub-6700098113284988";
            google_ad_slot = "6820720582";
            google_ad_width = 468;
            google_ad_height = 60;
            //-->
            </script>
            <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
        </div>

    </div>

    <div class="boxAd">
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-6700098113284988";
        google_ad_slot = "6801237380";
        google_ad_width = 300;
        google_ad_height = 250;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
    </div>
<div>

<div id="siteterms"><a href="terms.php">Terms</a> <a href="privacy.php">Privacy</a></div>

</body> 
</html>
