document.addEventListener("DOMContentLoaded", function(event){

    document.getElementById('menu-toggle').addEventListener('click', function () {
        const navAside = document.querySelector('.nav-aside');
        navAside.classList.toggle('active');
    });

    // document.getElementById("btn-cerrar-nav").addEventListener('click', function(){
    //     const navAside = document.querySelector('.nav-aside');
    //     navAside.classList.toggle('active');
    // });

});