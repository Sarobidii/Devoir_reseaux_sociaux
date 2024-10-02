function getXMLHttpRequest() {
	    var xhr = null;
        
	    if (window.XMLHttpRequest || window.ActiveXObject) { 
	    	if (window.ActiveXObject) {
	    		try {
	    			xhr = new ActiveXObject("Msxml2.XMLHTTP");
	    		} catch(e) {
	    			xhr = new ActiveXObject("Microsoft.XMLHTTP"); //Internet Explorer
	    		}
	    	} else {
	    		xhr = new XMLHttpRequest(); //Chrome & Firefox
	    	}
        } else {
        	alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        	return null;
        }

        return xhr;
    }
    function contenuReaction(variable, id_publication, type) {
        var xhr = getXMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    let element = document.querySelector('.like-button_' + id_publication);
                    if (element) {
                        element.innerHTML = xhr.responseText;
                    } else {
                        console.error('Élément non trouvé pour id_publication:', id_publication);
                    }
                } else {
                    console.error('Erreur dans la requête AJAX :', xhr.statusText);
                }
            }
        };

        xhr.open("GET", "ajax.php?id_reaction=" + variable + "&id_publication=" + id_publication+ "&type=" + type, true);
        xhr.send(null);
    }
    function nombreReaction(id_publication, type) {
        var xhr = getXMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    let element = document.querySelector('.nombre_reactions_' + id_publication);
                    if (element) {
                        element.innerHTML = xhr.responseText;
                    } else {
                        console.error('Élément non trouvé pour id_publication:', id_publication);
                    }
                } else {
                    console.error('Erreur dans la requête AJAX :', xhr.statusText);
                }
            }
        };

        xhr.open("GET", "ajax1.php?id_publication=" + id_publication + "&type=" + type, true);
        xhr.send(null);
    }
    function changerContenu(id_publication, type){
        var xhr = getXMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    let element = document.querySelector('.like-button_' + id_publication);
                    if (element) {
                        element.innerHTML = xhr.responseText;
                    } else {
                        console.error('Élément non trouvé pour id_publication:', id_publication);
                    }
                } else {
                    console.error('Erreur dans la requête AJAX :', xhr.statusText);
                }
            }
        };

        xhr.open("GET", "ajax2.php?id_publication=" + id_publication+ "&type=" + type, true);
        xhr.send(null);
    }