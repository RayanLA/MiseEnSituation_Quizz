$(function(){console.log('jQuery working!')});

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
    	//retrieve nom quizz
    	var nom = $('#nomQuizz').val();
    	//retrive url Image
    	getUrlImage(nom);

    });
});

$(function(){
  $('#urlInput').blur(function() {
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

function addQuestion(){

    var id = questionReponse.length;

    $(".questionContener")
    .append(
        '<div>'
            +'<div class=" input-group mb-3">'
                +'<div class="input-group-prepend">'
                    +'<span class="input-group-text" id="basic-addon1">Question '+(id+1)+': </span>'
                +'</div>'
                +'<input type="text" class="form-control" id="question_'+id+'" name="question_'+id+'">'
                +'<button class="btn btn-outline-secondary btn-outline-success addResponse" type="button" onclick="addResponse('+id+')">'
                +'<i class="fas fa-plus-circle"></i>'
                +'</button>'
            +'</div>'
            +'<div id="responseContener_'+id+'"></div>'
        +'</div>'
    );

    questionReponse[id] = 0;
}

function changeCorrect(e){
    if($(e).text()=="Faux"){
        $(e).text("Vrai");
        $(e).addClass("btn-success");
        $(e).removeClass("btn-danger");
        $('#correct_1').val('true');
    }else{
        $(e).text("Faux");
        $(e).addClass("btn-danger");
        $(e).removeClass("btn-success");
        $('#correct_1').val('false');

    }
}

function deleteQuestion(e, i, id){
    $(e).closest('.input-group').remove();//.remove();
    questionReponse[i]--;
}


function addResponse(i){
    questionReponse[i]++;
    var id = questionReponse[i];
    console.log(questionReponse);
    console.log('addResponse onto '+i+ " for question "+id);
    console.log('#responseContener_'+id);
    $('#responseContener_'+(i))
    .append(
        '<div class="input-group mb-3">'
            +'<input type="text" class="form-control" placeholder="Reponse '+id+'" name="q_'+i+'_'+id+'">'
            +'<div class="input-group-append">'
                +'<input id="correct_1" type="hidden" name="cq_'+i+'_'+id+'" value="false">'
                +'<button class="btn btn-danger VraiFaux" type="button" onclick="changeCorrect(this)">Faux</button>'
                +'<button class="btn btn-outline-secondary btn-outline-danger RemoveQuestion" type="button" onclick="deleteQuestion(this, '+i+','+id+')"> '
                +'<i class="far fa-times-circle"></i>'
                +'</button>'
            +'</div>'
        +'</div>'
    );
}

var questionReponse = [];
$(function(){
    if($('#creationQuizz').length != 0 ){
        $('#creationQuizz').ready(function(){
            addQuestion();
            addResponse(0);
        });
    }
});

