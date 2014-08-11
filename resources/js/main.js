//Parse related keys
var PARSE_APP = "UwDU2ENsnfr30Hi3HSyl3bbXumswDmzyzxoSqtON";
var PARSE_JS = "vM0dMGC7gMEpedj3UhlsMNwn9u0dKihN5GvngSMT";

$(document).ready(function() {
	Parse.initialize(PARSE_APP, PARSE_JS);

	NoteObject = Parse.Object.extend("NoteObject");

	function getNotes() {
		var query = new Parse.Query(NoteObject);

		query.find({
			success:function(results) {
				console.dir(results);
				var s = "";
				for(var i=0, len=results.length; i<len; i++) {
					var note = results[i];
					s += "<p>";
					s += "<b>"+note.get("title")+"</b><br/>";
					s += "<b>Written "+note.createdAt + "<br/>";
					s += note.get("body");
					s += "</p>";
				}
				$("#notes").html(s);
			},
			error:function(error) {
				alert("Error when getting notes!");
			}
		});
	}

	$("#addNoteBtn").on("touchend", function(e) {
		e.preventDefault();

		//Grab the note details, no real validation for now
		var title = $("#noteTitle").val();
		var body = $("#noteBody").val();

		var note = new NoteObject();
		note.save({title:title, body:body}, {
			success:function(object) {
				console.log("Saved the object!");
				$("#noteTitle").val("");
				$("#noteBody").val("");
				getNotes();
			}, 
			error:function(object,error) {
				console.dir(error);
				alert("Sorry, I couldn't save it.");
			}
		});
	});

	//call getNotes immediately
	getNotes();

});