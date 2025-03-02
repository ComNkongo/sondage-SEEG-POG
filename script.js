Document.getElementById(« uploadForm »).addEventListener(« submit », async (event) => {
    Event.preventDefault() ;

    Let fileInput = document.getElementById(« fileInput ») ;
    Let formData = new FormData() ;
    formData.append(« file », fileInput.files[0]) ;

    try {
        let response = await fetch(http://localhost:3000/upload, {
            method : « POST »,
            body : formData
        }) ;

        Let result = await response.json() ;
        Document.getElementById(« status »).innerText = result.message ;
    } catch (error) {
        Console.error(« Erreur : », error) ;
        Document.getElementById(« status »).innerText = « Erreur lors de l’envoi » ;
    }
}) ;
