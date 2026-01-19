<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Test</title>

```
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

```
<div class="text-center space-y-4">
    <h1 class="text-4xl font-bold text-blue-600">
        Tailwind SUDAH JALAN 🎉
    </h1>

    <p class="text-gray-700 text-lg">
        Jika tulisan ini warnanya <span class="text-red-500 font-semibold">merah</span>, berarti Tailwind bekerja.
    </p>

    <button class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
        Tombol Test
    </button>
</div>
```

</body>
</html>
