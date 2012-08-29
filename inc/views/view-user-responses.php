<h1>Video Submissions</h1>
<div id="generic-content">
  <?php if(isset($response) && $response['type'] == 'error') : ?>
  <div class="error">Error: <?=$response['message']?></div>
  <?php elseif(isset($response) && $response['type'] == 'success') : ?>
  <div class="success"><?=$response['message']?></div>
  <?php endif; ?>
  
  <object width="480" height="385">
    <param name="movie" value="http://www.youtube.com/p/AB8BC8FAAA1F8598?fs=1"></param>
    <param name="allowFullScreen" value="true"></param>
    <param name="allowscriptaccess" value="always"></param>
    <embed src="http://www.youtube.com/p/AB8BC8FAAA1F8598?fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed>
  </object>
  
  <br/>
  
  <script type="text/javascript" src="https://ktwo-youtube.appspot.com/js/ytd-embed.js"></script>
  <script type="text/javascript">
  var ytdInitFunction = function() {
    var ytd = new Ytd();
    ytd.setAssignmentId("2");
    ytd.setCallToAction("callToActionId-2");
    var containerWidth = 350;
    var containerHeight = 550;
    ytd.setYtdContainer("ytdContainer-2", containerWidth, containerHeight);
    ytd.ready();
  };
  if (window.addEventListener) {
    window.addEventListener("load", ytdInitFunction, false);
  } else if (window.attachEvent) {
    window.attachEvent("onload", ytdInitFunction);
  }
  </script>
  <!-- Call to action button -->
  <a id="callToActionId-2" href="javascript:void(0);"><img src="https://ktwo-youtube.appspot.com/images/calltoaction.png"></a>
  <div id="ytdContainer-2"></div>

</div>