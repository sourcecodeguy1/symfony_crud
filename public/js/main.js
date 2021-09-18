const posts = document.getElementById("posts");

if(posts){
    posts.addEventListener('click',  e => {
       if(e.target.className === 'btn btn-danger delete-post'){
           if(confirm('Are you sure you want to delete this record?')){
               const id = e.target.getAttribute('data-id');

               fetch(`/post/delete/${id}`,{
                   method: 'DELETE'
               }).then(res => window.location.reload());
           }
       }
    });
}