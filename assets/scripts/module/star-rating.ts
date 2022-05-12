// Condition pour que le code agisse seulement sur une page où il y a l'id rating-system
if(document.querySelector('#rating-system') !== null){    
    let queryAll = document.querySelectorAll('[data-value]');

    queryAll.forEach(element => {
        element.addEventListener('click', function() {
            queryAll.forEach(star => {
                if(star.classList.contains("text-warning")){
                    star.classList.remove("text-warning");
                }                
            });
            
            let valueStar = element.getAttribute('data-value');
            
            let myNote = document.querySelector('#add_comment_note');

            myNote.setAttribute('value', valueStar);

            queryAll.forEach(star => {
                if (parseInt(star.getAttribute('data-value')) <= parseInt(valueStar)) {
                    star.classList.add("text-warning");
                }
            });
        });
    });
}
