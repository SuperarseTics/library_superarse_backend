<!DOCTYPE html>
<html>
<head>
    <title>Recordatorio de Devolución de Libro</title>
</head>
<body>
    <p>Hola {{ $booking->user->name }},</p>
    <p>Te recordamos que la fecha para la devolución del libro <strong>{{ $booking->book->title }}</strong> ha pasado. Por favor, acércate a la biblioteca para realizar la entrega lo antes posible.</p>
    <p>Gracias.</p>
</body>
</html>
