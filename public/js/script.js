$("input[type='checkbox']").change(function() {
    if ($(this).is(":checked")) {
        $("input[type='checkbox']").not(this).prop("checked", false);
    }
});

  /*
    Voir la Documentation
    https://developer.mozilla.org/en-US/docs/Web/API/SpeechRecognition
  */
  // Création de l'objet de la class webkitSpeechRecognition
  var rec = new webkitSpeechRecognition();
  rec.lang = 'fr-FR';
  rec.continuous = true;
  rec.interimResults = true;

  // Fonction jquery pour controler l'écoute
  
  $('#recButton').addClass("notRec");
  
  $('#recButton').click(function(){
      if($('#recButton').hasClass('notRec')){
          $('#recButton').removeClass("notRec");
          $('#recButton').addClass("Rec");
          listen();
        }
        else{
            $('#recButton').removeClass("Rec");
            $('#recButton').addClass("notRec");
            stopListen();
	}
});	

  // Fonction lancer l'écoute
  function listen() {
    rec.start();
    rec.onresult = function(e) {
      console.log(e.results);
      let result = e.results.item(e.resultIndex);
      if (result.isFinal === true) {
        console.log(result[0].transcript);
        document.getElementById('story_story').textContent += result[0].transcript;
      }
    }
  }

  // Fonction stopper l'écoute
  function stopListen() {
    rec.stop();
    alert("reconnaissance vocale désactivée")
  }


  
  