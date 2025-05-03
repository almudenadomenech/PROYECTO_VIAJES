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
    
        const guestCount = parseInt(document.querySelector('input[name="guest"]').value) || 1;

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
            const guestCount = parseInt(document.querySelector('input[name="guest"]').value) || 1;
        
            if (!isNaN(price)) {
                const total = price * guestCount;
                document.getElementById('total-price').value = `€${total.toFixed(2)}`;
                document.getElementById('discounted-price').value = `€${total.toFixed(2)}`;
                document.getElementById('precio_con_descuento').value = total.toFixed(2); // actualiza precio oculto
            }
        }
        
    }
    

    // Detectar cuando cambie la fecha de inicio
    arrivalsInput.addEventListener('change', setLeavingDate);

    // Detectar cuando cambie la duración
    durationSelect.addEventListener('change', setLeavingDate);

    // Recalcular precio si cambia el número de personas
    document.querySelector('input[name="guest"]').addEventListener('input', setLeavingDate);

});


// PASARELA DE PAGO

function applyDiscount() {
    const discountInput = document.getElementById('discount-code');
    const discountCode = discountInput.value.trim();
    const validCode = discountInput.getAttribute('data-valid-code');

    let totalPrice = parseFloat(document.getElementById('total-price').value.replace('€', '').replace(',', '.'));

    if (isNaN(totalPrice) || totalPrice <= 0) {
        alert('Por favor selecciona un paquete válido antes de aplicar descuento.');
        return;
    }

    if (discountCode === validCode) {
        let discountPercent = 0.15; // 15% de descuento
        const discountAmount = totalPrice * discountPercent;
        const finalPrice = totalPrice - discountAmount;

        document.getElementById('discount-amount').value = '€' + discountAmount.toFixed(2);
        document.getElementById('final-price').value = '€' + finalPrice.toFixed(2);
        document.getElementById('discounted-price').value = '€' + finalPrice.toFixed(2);

        // ✅ Actualizar el precio oculto inmediatamente
        document.getElementById('precio_con_descuento').value = finalPrice.toFixed(2);

        alert('¡Código de descuento aplicado correctamente!');
    } else {
        alert('Código inválido. No se aplicó descuento.');

        document.getElementById('discount-amount').value = '€0.00';
        document.getElementById('final-price').value = '€' + totalPrice.toFixed(2);
        document.getElementById('discounted-price').value = '€' + totalPrice.toFixed(2);

        // ✅ Restaurar el precio oculto original
        document.getElementById('precio_con_descuento').value = totalPrice.toFixed(2);
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
       <div style="
        background: #f9f9f9;
        padding: 60px 40px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        max-width: 400px;
        width: 90%;
        font-family: 'Segoe UI', sans-serif;
        position: relative;
    ">
        <div class="spinner" style="
            margin: 0 auto 30px;
            border: 6px solid #ddd;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        "></div>
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 10px;">Procesando pago...</h2>
        <p style="font-size: 18px; color: #666;">Por favor espera unos segundos</p>
    </div>
`;

    document.body.appendChild(processingModal);

    // Capturar el precio con descuento antes de enviar
    const finalPrice = document.getElementById('final-price').value.replace('€', '').replace(',', '.');
    document.getElementById('precio_con_descuento').value = finalPrice;

   // Simular tiempo de procesamiento
setTimeout(function () {
    document.body.removeChild(processingModal);

    // Mostrar modal de confirmación manualmente
    const successModal = document.getElementById('modal-message');
    const overlay = document.getElementById('modal-overlay');

    if (successModal && overlay) {
        successModal.style.display = 'block';
        overlay.style.display = 'block';
    }

}, 6000);
}

function submitAfterConfirmation() {
    closeModal(); // Cierra el modal visual
    document.querySelector('.booking-form').submit(); // Ahora sí envía el formulario
}






    
    


