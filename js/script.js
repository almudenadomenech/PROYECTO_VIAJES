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

function applyDiscount() {
    const discountInput = document.getElementById('discount-code');
    const discountCode = discountInput.value.trim();
    const validCode = discountInput.getAttribute('data-valid-code'); // Código correcto desde el HTML

    let totalPrice = parseFloat(document.getElementById('total-price').value.replace('€', '').replace(',', '.'));
    
    // Validar precio base
    if (isNaN(totalPrice) || totalPrice <= 0) {
        alert('Por favor selecciona un paquete válido antes de aplicar descuento.');
        return;
    }

    // Comprobar si el código es correcto
    if (discountCode === validCode) {
        let discountPercent = 0.15; // 15% de descuento
        const discountAmount = totalPrice * discountPercent;
        const finalPrice = totalPrice - discountAmount;

        // Mostrar descuentos
        document.getElementById('discount-amount').value = '€' + discountAmount.toFixed(2);
        document.getElementById('final-price').value = '€' + finalPrice.toFixed(2);
        document.getElementById('discounted-price').value = '€' + finalPrice.toFixed(2);

        alert('¡Código de descuento aplicado correctamente!');
    } else {
        // Código inválido ➔ Mostrar todo normal sin descuento
        alert('Código inválido. No se aplicó descuento.');

        document.getElementById('discount-amount').value = '€0.00';
        document.getElementById('final-price').value = '€' + totalPrice.toFixed(2);
        document.getElementById('discounted-price').value = '€' + totalPrice.toFixed(2);
    }
}



// Función para simular el pago
function simulatePayment() {
    // Crear modal de procesamiento
    let processingModal = document.createElement('div');
    processingModal.style.position = 'fixed';
    processingModal.style.top = '0';
    processingModal.style.left = '0';
    processingModal.style.width = '100%';
    processingModal.style.height = '100%';
    processingModal.style.background = 'rgba(0,0,0,0.6)';
    processingModal.style.display = 'flex';
    processingModal.style.alignItems = 'center';
    processingModal.style.justifyContent = 'center';
    processingModal.style.zIndex = '9999';
    processingModal.id = 'processing-modal';

    processingModal.innerHTML = `
        <div style="background:#fff; padding:30px 40px; border-radius:10px; text-align:center;">
            <h2>Procesando pago...</h2>
            <p>Por favor espera unos segundos</p>
        </div>
    `;

    document.body.appendChild(processingModal);

    // Simular tiempo de procesamiento
    setTimeout(function() {
        // Eliminar modal de procesamiento
        document.body.removeChild(processingModal);
        
        // Crear modal de éxito
        let successModal = document.createElement('div');
        successModal.style.position = 'fixed';
        successModal.style.top = '0';
        successModal.style.left = '0';
        successModal.style.width = '100%';
        successModal.style.height = '100%';
        successModal.style.background = 'rgba(0,0,0,0.6)';
        successModal.style.display = 'flex';
        successModal.style.alignItems = 'center';
        successModal.style.justifyContent = 'center';
        successModal.style.zIndex = '9999';

        successModal.innerHTML = `
            <div style="background:#fff; padding:30px 40px; border-radius:10px; text-align:center;">
                <h2>¡Pago realizado con éxito!</h2>
                <p>Serás redirigido en unos segundos...</p>
            </div>
        `;

        document.body.appendChild(successModal);

        // Redirigir automáticamente después de 3 segundos
        setTimeout(function() {
            window.location.href = 'booking.php'; 
        }, 3000);
    }, 3000);
}
