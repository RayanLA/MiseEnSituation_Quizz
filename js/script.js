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

$(function(){
    $(".news-text").each(function () {
        len=$(this).text().length;
        str= $(this).text().substr(0,70);
        lastIndexOf = str.lastIndexOf(" "); 
        if(len>70) {
            $(this).text(str.substr(0, lastIndexOf) + '…');
        }
    });
    });

function getUrlImage(input){
    if(input!=""){
        var urlAPI = "https://www.googleapis.com/customsearch/v1?key=AIzaSyCeLjOtZ7FVDuS7nbUIG-ZjzJuwHV9R3QQ&cx=001962025405331380680%3Abxstdd8lquo&q="+encodeURI(input)+"&searchType=image"

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var json = JSON.parse(xhttp.responseText);
                console.log(json);
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

function deleteQuestionContener(id){
    console.log("delete");
    $("#questionContener_"+id).remove();
}


function addQuestion(){

    var id = questionReponse.length;

    $(".questionContener")
    .append(
        '<div  class="p-3  border rounded mb-4" id="questionContener_'+(id)+'">'
            +'<div class=" input-group mb-3">'
                +'<div class="input-group-prepend">'
                    +'<span class="input-group-text" id="basic-addon1">Question '+(id+1)+': </span>'
                +'</div>'
                +'<input type="text" class="form-control" id="question_'+id+'" name="question_'+id+'">'
                +'<button class="btn btn-outline-secondary btn-outline-danger" type="button" onclick="deleteQuestionContener('+id+')">'
                +'<i class="far fa-trash-alt"></i>'
                +'</button>'
                +'<button class="btn btn-outline-secondary btn-outline-success addResponse" type="button" onclick="addResponse('+id+')">'
                +'<i class="fas fa-plus-circle"></i>'
                +'</button>'
            +'</div>'
            +'<div id="responseContener_'+id+'"></div>'
        +'</div>'
    );

    questionReponse[id] = 0;
    return id;
}

function changeCorrect(e, i, id){
    if($(e).text()=="Faux"){
        $(e).text("Vrai");
        $(e).addClass("btn-success");
        $(e).removeClass("btn-danger");
        $('#cq_'+i+'_'+id).val('true');
    }else{
        $(e).text("Faux");
        $(e).addClass("btn-danger");
        $(e).removeClass("btn-success");
        $('#cq_'+i+'_'+id).val('false');

    }
}

function deleteQuestion(e, i, id){
    $(e).closest('.input-group').remove();//.remove();
    questionReponse[i]--;
    console.log('de '+(id+1)+' à '+(questionReponse[i]+1));
    for (var j = id+1; j <= questionReponse[i]+1; j++) {
        console.log('#q_'+i+'_'+j)
        $('#q_'+i+'_'+j).attr("placeholder", "Reponse "+(j-1));
        $('#q_'+i+'_'+j).attr("id", "q_"+i+"_"+(j-1));
    }
}


function addResponse(i){
    questionReponse[i]++;
    var id = questionReponse[i];
    $('#responseContener_'+(i))
    .append(
        '<div class="input-group mb-3">'
            +'<input type="text" class="form-control" id="q_'+i+'_'+id+'" placeholder="Reponse '+id+'" name="q_'+i+'_'+id+'">'
            +'<div class="input-group-append">'
                +'<input id="cq_'+i+'_'+id+'" type="hidden" name="cq_'+i+'_'+id+'" value="false">'
                +'<button class="btn btn-danger VraiFaux" type="button" id="bq_'+i+'_'+id+'" onclick="changeCorrect(this, '+i+', '+id+')">Faux</button>'
                +'<button class="btn btn-outline-secondary btn-outline-danger RemoveQuestion" type="button" onclick="deleteQuestion(this, '+i+','+id+')"> '
                +'<i class="far fa-times-circle"></i>'
                +'</button>'
            +'</div>'
        +'</div>'
    );
    return id;
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


function barChart(){
    /*Preparing data */
    var myData = []; var myLabel = []; 
    var i=0; var myColorBorder = [];
    var myColorBg = []; 

     var colorBorderSet = [  'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'];
    var bgColorSet    = [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ];

    createdQuizzStats.forEach(function(element, index, array){
        myData[i]  = element[0];
        myLabel[i] = element[1];
        myColorBorder[i] = colorBorderSet[i%6];
        myColorBg[i]     = bgColorSet[i%6];
        i++;
    });
   
    //bar
    if($("#barChart").length!=0){
        var ctxB = document.getElementById("barChart").getContext('2d');

        var _data = {
            labels: myLabel,
            datasets: [{
                label: 'Nombre de participants aux quizzes',
                data: myData,
                backgroundColor: myColorBg,
                borderColor: myColorBorder,
                borderWidth: 1
            }]
        };

        var options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }, 
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        var i = Number(tooltipItem.yLabel);
                        var description = "";
                        createdQuizzStats.forEach(function(element, index, array){
                            if(element[1].toString().localeCompare(tooltipItem.label)==0){
                                description = "Score moyen : "+parseFloat(element[3])+" et score maximal : "+element[2];
                            }
                        });
                        return " "+i+ (i>1 ? " participants ! ":" participant ! ")+description;
                    }
                }
            }, 
            plugins: {
              deferred: {
                yOffset: '50%', // defer until 50% of the canvas height are inside the viewport
                delay: 700     // delay of 500 ms after the canvas is considered inside the viewport
            }
        }
        };

        myBarChart = new Chart(ctxB, {type:'bar', data:_data,options});
    }
/*
    $("#barChart").removeAttr("style").removeAttr("width").removeAttr("height");*/

}

var quizzInfoScore = [];
var createdQuizzStats = [];
function donutChart(){
    quizzInfoScore.forEach(function(element, index, array){
        //index => element
        //doughnut
        if($("#"+"doughnutChart_"+index).length!=0){
           var ctxD = document.getElementById("doughnutChart_"+index).getContext('2d');
            var myDonutChart = new Chart(ctxD, {
                type: 'doughnut',
                data: {
                    labels: ["Bonne réponse ","Mauvaise réponse "],
                    datasets: [{
                        data: [element, 100-element],
                        backgroundColor: ["#64D96814", "#F7464A14"],
                        borderColor: ["#64D968", "#FF5A5E"]
                    }]
                },
                options: {
                    responsive: true,
                    animation:{
                        duration:1000, 
                    }, 
                    plugins: {
                        deferred: {
                            yOffset: '30%', // defer until 50% of the canvas height are inside the viewport
                            delay: 50     // delay of 500 ms after the canvas is considered inside the viewport
                        }
                    }   
                }        
            }); 
        }
        
    });
}

var myBarChart;
function chart(){
    
    donutChart();

    barChart();

}

$(function(){
    $("#quizzJoue").ready(function(){
        chart();
    });
});

function removeStats(){
    $(document).ready(function(){
        $("#two").remove();
        $('#one').addClass('active').addClass('show');
    });
}

function showStats(){
    $(document).ready(function(){
        $("#two").removeClass('hideImportant');
    });
}

function showStatRubrique(id){
    $(document).ready(function(){
        $("#"+id).removeClass('hide');
    });
}


function openModalShare(type, idC, idQ, nom){
    if(("C").localeCompare(type)==0){
        $("#messageShare")
        .html("La categorie <i>"+nom+"</i> est prête pour le partage !"
               /*+" L'URL de partage a été copié dans votre presse-papier :)"*/ );
        $("#urlShare").val("https://www.rayan-la-roze.fr/Quizz4A/invitation.php?c="+idC);
        /*var copyText = document.querySelector("#urlShare");
        copyText.select();
        document.execCommand('copy');*/
    }else{
        $("#messageShare")
        .html("Le quizz <i>"+nom+"</i> est prête pour le partage !"
               /*+" L'URL de partage a été copié dans votre presse-papier :)"*/ );
        $("#urlShare").val("https://www.rayan-la-roze.fr/Quizz4A/invitation.php?c="+idC+"&q="+idQ);
    }
    $("#shareModal").modal('toggle');
}



function redirectToQuizz(idC, idQ){
    $("#formForSearch").append('<form action="quizz.php" method="post">'
                    +'<input name="idQuizz" value="'+idQ+'" class="hide"/>'
                    +'<input name="idCategorie"  value="'+idC+'" class="hide"/>'
                    +'<button type="submit" id="formSearchButton">'
                  +'</form>');
    $('#formSearchButton').click();
}

function redirectToCategorie(id, name){
    $("#formForSearch").append('<form action="QuizzParCategorie.php" method="post">'
                    +'<input name="idCategorie" id="numquestion" value="'+id+'" class="hide"/>'
                    +'<input name="nomCategorie" value="'+name+'" class="hide">'
                    +'<button type="submit" id="formSearchButton">'
                  +'</form>');
    $('#formSearchButton').click();
}

function showSearchBar(){
    $("#buttonSearchBar").animate({
        opacity:0
    });
    $("#buttonSearchBar").animate({
        display: 'none'
    });
    $('#divSearchBox').animate({
        opacity: 1, 
        right: '-18px'
    }, 'fast');
}


function generateQuestionReponses(){
    //questionReponseData
    $(function(){
        var tab = new Array(questionReponseData);
        for (var i in tab) {
            for(var q in tab[i]){
                var idQ = addQuestion();
                $("#question_"+idQ).val((q).replaceAll("&quot;", '"'));
                for(var r in tab[i][q]){
                    var R = tab[i][q][r];
                    var idR = addResponse(idQ);
                    $('#q_'+idQ+'_'+idR).val((R.reponse).replaceAll("&quot;", '"'));
                    if((R.correct).localeCompare("1")==0) changeCorrect($('#bq_'+idQ+'_'+idR), idQ, idR);
                }
            }
        }
    });
}

function checkInput(i){
    if($("#checkbox_"+i).is(':checked')){
        $("#checkbox_"+i).prop( "checked", false );
        $("#grText_"+i).removeClass("bg-success");

    }else{
        $("#checkbox_"+i).prop( "checked", true );
        $("#grText_"+i).addClass("bg-success");

    }
}

$(function(){
    $('#deleteQuizz').click(function(){
        $('#deleteQuizzInput').val(1);
        $('#modifyQuizzSubmitButton').click();
    });
});