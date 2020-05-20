$(function(){console.log('jQuerry running !');});

$(function(){
  $("descriptionQuizz").change(function(i){
   len=$(this).text().length;
   if(len>300)
   {
    $(this).text($(this).text().substr(0,300)+'...');
}
});
});

$(function(){
  $('#nomQuizz').blur(function() {
        console.log('out');
    	//retrieve nom quizz
    	var nom = $('#nomQuizz').val();
    	//retrive url Image
    	getUrlImage(nom);

    });
});

$(function(){
  $('#urlInput').blur(function() {
        console.log('out');
        $('#thumbnailImg').attr('src',$('#urlInput').val());
    });
});

function getUrlImage(input){
    console.log(input);
    if(input!=""){
        var urlAPI = "https://www.googleapis.com/customsearch/v1?key=AIzaSyCeLjOtZ7FVDuS7nbUIG-ZjzJuwHV9R3QQ&cx=001962025405331380680%3Abxstdd8lquo&q="+encodeURI(input)+"&searchType=image"

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var json = JSON.parse(xhttp.responseText);
                var urlImage = json.items[0].link;

                $(function(){
                    $('#thumbnailImg').attr('src',urlImage);
                    $('#urlInput').val(urlImage);
                });
           }
        };
        xhttp.timeout = function(){
            console.error("Could not retrieve image url ...");
        }

        xhttp.open("GET", urlAPI, true);
        xhttp.send();
    }
    return "";
}