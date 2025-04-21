// Hacer toggle del dropdown (necesario que esté en el ámbito global)
function toggleDropdown(event) {
    event.stopPropagation(); // Evita que se cierre inmediatamente al hacer clic
    let dropdown = document.getElementById('dropdown-menu');
    dropdown.classList.toggle('show');
}

// Cerrar el menú desplegable al hacer clic fuera (también fuera del DOMContentLoaded)
document.addEventListener('click', function (event) {
    let dropdown = document.getElementById('dropdown-menu');
    if (dropdown && dropdown.classList.contains('show')) {
        if (!event.target.closest('.profile-dropdown')) {
            dropdown.classList.remove('show');
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Menú Toggle (hamburguesa)
    let menuBtn = document.querySelector('#menu-btn');
    let navbar = document.querySelector('.header .navbar');

    menuBtn.onclick = () => {
        menuBtn.classList.toggle('fa-times');
        navbar.classList.toggle('active');
    };

    // Cerrar el menú al hacer scroll
    window.onscroll = () => {
        menuBtn.classList.remove('fa-times');
        navbar.classList.remove('active');
    };

    // Swiper sliders generales
    if (typeof Swiper !== 'undefined') {
        new Swiper(".home-slider", {
            loop: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        new Swiper(".reviews-slider", {
            loop: true,
            spaceBetween: 20,
            autoHeight: true,
            grabCursor: true,
            breakpoints: {
                640: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        // 🆕 Swiper para la galería de detalle del paquete
        new Swiper(".gallerySwiper", {
            loop: true,
            spaceBetween: 2,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            slidesPerView: 2,
        });
    }

    // Lógica para los paquetes dinámicos
    let loadMoreBtn = document.querySelector('.load-more-packages .btn');
    let boxes = document.querySelectorAll('.packages .box-container .box');
    let currentItem = 3;

    const initializePackages = () => {
        boxes.forEach((box, index) => {
            if (index < currentItem) {
                box.style.display = 'inline-block';
            } else {
                box.style.display = 'none';
            }
        });
        checkLoadMoreButton();
    };

    const checkLoadMoreButton = () => {
        if (loadMoreBtn) {
            if (currentItem >= boxes.length) {
                loadMoreBtn.style.display = 'none';
            } else {
                loadMoreBtn.style.display = 'inline-block';
            }
        } else {
            console.warn('Botón "Cargar más" no encontrado.');
        }
    };

    if (loadMoreBtn) {
        loadMoreBtn.onclick = () => {
            currentItem += 3;
            initializePackages();
        };
    }

    window.addEventListener('load', initializePackages);

   
  
});

// Para la fecha en booking.

document.addEventListener('DOMContentLoaded', function () {
    const arrivalsInput = document.getElementById('arrivals');
    const leavingInput = document.getElementById('leaving');
    const durationSelect = document.getElementById('duracion_paquete_id');

    // Función para calcular y establecer la fecha de fin
    function setLeavingDate() {
        const arrivalsDate = new Date(arrivalsInput.value);
        const selectedDuration = durationSelect.selectedOptions[0]?.dataset?.duration;

        // Solo actualizamos si tenemos una fecha de inicio y una duración seleccionada
        if (arrivalsDate && selectedDuration) {
            // Asegurarse de que la duración sea un número entero
            const durationDays = parseInt(selectedDuration);
            if (!isNaN(durationDays)) {
                // Sumar los días seleccionados a la fecha de inicio
                arrivalsDate.setDate(arrivalsDate.getDate() + durationDays);

                // Establecer la nueva fecha de fin en el campo correspondiente
                leavingInput.value = arrivalsDate.toISOString().split('T')[0]; // Formato yyyy-mm-dd
            }
        }
    }

    // Detectar cuando cambie la fecha de inicio
    arrivalsInput.addEventListener('change', setLeavingDate);

    // Detectar cuando cambie la duración
    durationSelect.addEventListener('change', setLeavingDate);
});
