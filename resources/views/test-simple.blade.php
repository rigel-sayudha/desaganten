<!DOCTYPE html>
<html>
<head>
    <title>Simple Test View</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            background: #f0f0f0; 
        }
        .test-box { 
            background: white; 
            padding: 20px; 
            border: 2px solid #007cba; 
            border-radius: 8px; 
            margin: 20px 0; 
        }
        .success { border-color: green; background: #e6ffe6; }
        .info { border-color: blue; background: #e6f3ff; }
    </style>
</head>
<body>
    <div class="test-box success">
        <h1>✅ Simple View Test</h1>
        <p>If you can see this page, basic view rendering works!</p>
        <p><strong>Time:</strong> {{ now() }}</p>
        <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
    </div>
    
    <div class="test-box info">
        <h2>Next Steps:</h2>
        <ol>
            <li>This proves basic view rendering works</li>
            <li>The issue might be in the complex user.surat.index view</li>
            <li>Or authentication middleware redirecting</li>
            <li>Check browser console for JavaScript errors</li>
        </ol>
    </div>
    
    <div class="test-box">
        <h2>Testing Links:</h2>
        <ul>
            <li><a href="/login">Login Page</a></li>
            <li><a href="/user/surat">User Surat (requires login)</a></li>
            <li><a href="/test-user-surat">JSON Test Route</a></li>
        </ul>
    </div>
    
    <script>
        console.log('Simple test view loaded successfully');
        console.log('Current URL:', window.location.href);
    </script>
</body>
</html>
