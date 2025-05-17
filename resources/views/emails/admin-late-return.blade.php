<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Libro No Devuelto</title>
</head>
<body>
    <p>Estimado administrador,</p>
    <p>El estudiante <strong>{{ $booking->user->name }}</strong> aún no ha devuelto el libro <strong>{{ $booking->book->title }}</strong>, cuya fecha de devolución era <strong>{{ $booking->last_give_back_date->format('d/m/Y') }}</strong>.</p>
    <p>Por favor, tome las acciones necesarias.</p>
    <p>Gracias.</p>
</body>
</html>
