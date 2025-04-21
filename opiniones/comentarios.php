<form action="guardar_comentario.php" method="POST">
    <input type="hidden" name="package_id" value="<?= $package_id ?>"> <!-- El ID del paquete -->
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>"> <!-- ID del usuario logueado -->
    
    <!-- Puntuación (rating) -->
    <div class="inputBox">
        <span>Puntuación</span>
        <select name="rating" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    
    <!-- Comentario -->
    <div class="inputBox">
        <span>Comentario</span>
        <textarea name="comment" required></textarea>
    </div>
    
    <button type="submit">Dejar comentario</button>
</form>
