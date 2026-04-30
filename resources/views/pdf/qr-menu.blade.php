<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 210mm; height: 297mm; background: #fff; }
        .page {
            position: absolute;
            top: 0; left: 0;
            width: 210mm; height: 297mm;
        }
        img {
            position: absolute;
            width: 160mm;
            height: 160mm;
            top: 50%;
            left: 50%;
            margin-top: -80mm;
            margin-left: -80mm;
        }
    </style>
</head>
<body>
    <div class="page">
        <img src="{{ $qrDataUri }}" alt="QR">
    </div>
</body>
</html>
