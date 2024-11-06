document.addEventListener("DOMContentLoaded", function(event){

    function checkWindowSize() {
        const elementosAside = document.getElementById("elementos-aside");
        const mostrarAside = document.getElementById("btn-mostrar-aside");
    
        if (window.innerWidth < 720) {
            elementosAside.classList.add("hidden");
            mostrarAside.style.display = "block";
        } else {
            elementosAside.classList.remove("hidden");
            mostrarAside.style.display = "none";
        }
    }

    document.getElementById("btn-mostrar-aside").addEventListener("click", function () {
        const elementosAside = document.getElementById("elementos-aside");
        
        if(elementosAside.classList.contains("hidden")){
            document.querySelector(".nav-aside").style.height= "100vh";
            elementosAside.classList.remove("hidden");
            elementosAside.classList.add("show");
        }else{
            document.querySelector(".nav-aside").style.height= "";
            elementosAside.classList.remove("show");
            elementosAside.classList.add("hidden");
        }
        
    });
    
    // Ejecuta la función al cargar la página
    checkWindowSize();
    
    // Ejecuta la función cada vez que se redimensiona la ventana
    window.addEventListener("resize", checkWindowSize);

});