$(setUp);

function setUp(){
  $(".comments-link").click(loadComments);
}
var idThread;
function loadComments(){
  idThread=$(this).attr("name");
  $('.comments-link[name='+idThread+']').unbind();
  $.getJSON("thread_comments.php", {'idThread': idThread}, showComments);
  $("#newCom"+idThread).attr("style","display: block");
  $("#newCom"+idThread).children("button").click(sendComments);
  return false;
}
function showComments(comments){
  $.each(comments, commentReceived);
}
function commentReceived(index,value){
  var comment=$('<li class="comment"></li>');
  var list=$('<ul></ul>');
  list.append('<li class="comment-author"><a href="view_profile.php?userId='+value.idOwner+'">'+ value.userName +'</a></li>');
  
  //prevents XSS
  var li = $(document.createElement('li'));
  li.attr({
    class: "comment-author"
  });
  var span = $(document.createElement('span'));
  span.text(value.commentText);
  li.append(span);
  list.append(li);

  comment.append(list);
  comment.hide();
  $('.comments-link[name='+value.idEventThread+']').parent().parent().find(".comments").append(comment);
  comment.show(1000);
}
function sendComments(){
  var text=$("#newCom"+idThread).children("textarea").val();
  $("#newCom"+idThread).children("textarea").val("");
  var id = $(this).attr("name");
  $(this).parent().parent().find(".comments").html("");
  $.getJSON("thread_comments.php", {'idThread': id, 'commentText': text}, showComments);
  return false;
}
