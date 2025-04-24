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
        const selectedOption = durationSelect.selectedOptions[0];
    
        const selectedDuration = selectedOption?.dataset?.duration;
        const selectedPrice = selectedOption?.dataset?.precio;
    
        // Establecer fecha de salida
        if (arrivalsInput.value && selectedDuration) {
            const arrivalsDate = new Date(arrivalsInput.value);
            const durationDays = parseInt(selectedDuration);
        
            if (!isNaN(arrivalsDate.getTime()) && !isNaN(durationDays)) {
                arrivalsDate.setDate(arrivalsDate.getDate() + durationDays);
                leavingInput.value = arrivalsDate.toISOString().split('T')[0];
            } else {
                console.warn("Fecha de inicio o duración inválida");
            }
        }
        
    
        // Establecer precio total
        if (selectedPrice) {
            const price = parseFloat(selectedPrice);
            if (!isNaN(price)) {
                document.getElementById('total-price').value = `€${price.toFixed(2)}`;
                document.getElementById('discounted-price').value = `€${price.toFixed(2)}`; // Inicial igual
            }
        } else {
            document.getElementById('total-price').value = "€0.00";
            document.getElementById('discounted-price').value = "€0.00";
        }
    }
    

    // Detectar cuando cambie la fecha de inicio
    arrivalsInput.addEventListener('change', setLeavingDate);

    // Detectar cuando cambie la duración
    durationSelect.addEventListener('change', setLeavingDate);
});


// PASARELA DE PAGO

// Función para aplicar el descuento
function applyDiscount() {
    const discountCode = document.getElementById('discount-code').value;
    let totalPrice = parseFloat(document.getElementById('total-price').value.replace('€', '').replace(',', '.'));
    let discount = 0;

    // Lógica para aplicar descuento (puedes modificar esto según tus necesidades)
    if (discountCode === 'DESCUENTO10') {
        discount = 0.10; // 10% de descuento
    } else if (discountCode === 'DESCUENTO20') {
        discount = 0.20; // 20% de descuento
    }

    // Aplicar el descuento
    const discountedPrice = totalPrice - (totalPrice * discount);

    // Mostrar el precio después del descuento
    document.getElementById('discounted-price').value = '€' + discountedPrice.toFixed(2);
}

// Función para simular el pago
function processPayment() {
    const discountedPrice = parseFloat(document.getElementById('discounted-price').value.replace('€', '').replace(',', '.'));
    
    if (discountedPrice <= 0) {
        alert("Por favor, asegúrate de que el precio total sea mayor a 0.");
        return;
    }

    // Simular un proceso de pago
    const paymentSuccess = Math.random() > 0.2; // Simulamos un 80% de éxito en el pago

    if (paymentSuccess) {
        alert("¡Pago realizado con éxito! Gracias por tu compra.");
        // Aquí podrías redirigir al usuario o realizar otras acciones, como enviar los datos al servidor.
    } else {
        alert("Hubo un problema al procesar el pago. Inténtalo de nuevo más tarde.");
    }
}

