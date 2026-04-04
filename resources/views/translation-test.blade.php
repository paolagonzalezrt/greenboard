<!-- Test Page para Verificar Traducción -->
<!DOCTYPE html>
<html>
<head>
    <title>Translation Test</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .test { margin: 20px 0; padding: 10px; border: 1px solid #ccc; }
        .pass { color: green; }
        .fail { color: red; }
    </style>
</head>
<body>
    <h1>🌍 Translation System Test</h1>
    
    <div class="test">
        <h3>Current Locale</h3>
        <p id="locale">Loading...</p>
    </div>

    <div class="test">
        <h3>Login Translations for Current Locale</h3>
        <ul id="translations">
            <li>Loading...</li>
        </ul>
    </div>

    <div class="test">
        <h3>Change Locale Links</h3>
        <ul>
            <li><a href="<?php echo url('/lang/es'); ?>">Spanish (ES)</a></li>
            <li><a href="<?php echo url('/lang/en'); ?>">English (EN)</a></li>
            <li><a href="<?php echo url('/lang/de'); ?>">German (DE)</a></li>
        </ul>
    </div>

    <div class="test">
        <h3>Browser Console</h3>
        <p>Open DevTools (F12) to see console messages</p>
    </div>

    <script>
        // Get locale from Laravel backend
        fetch('/api/locales')
            .then(r => r.json())
            .then(data => {
                document.getElementById('locale').textContent = 'Current: ' + data.current;
                console.log('API Response:', data);
            })
            .catch(e => {
                console.error('API Error:', e);
                document.getElementById('locale').textContent = 'Error loading locale';
            });
    </script>
</body>
</html>
