

function adminPreviewPost(postId) {
    $.get(appRoot + 'admin/posts/preview/' + postId,  function(response){
        $("#post-preview").html(response);
    })
}