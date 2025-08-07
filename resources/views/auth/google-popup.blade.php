<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login - Processing...</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .submessage {
            font-size: 0.9rem;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        <div class="message">Processing Google Login...</div>
        <div class="submessage">Please wait while we verify your credentials.</div>
    </div>

    <script>
        // This script runs in the popup window and communicates with the parent
        document.addEventListener('DOMContentLoaded', function() {
            // Get data passed from controller (if any)
            const success = @json($success ?? null);
            const message = @json($message ?? null);
            const redirect = @json($redirect ?? null);
            
            // If we have data from the controller, send it to parent immediately
            if (success !== null) {
                if (window.opener) {
                    window.opener.postMessage({
                        type: 'GOOGLE_LOGIN_RESULT',
                        success: success,
                        message: message,
                        redirect: redirect
                    }, window.location.origin);
                }
                
                // Close the popup
                setTimeout(() => {
                    window.close();
                }, 500);
                return;
            }
            
            // Check if we have URL parameters indicating this is a popup request
            const urlParams = new URLSearchParams(window.location.search);
            
            // If this is a popup request, handle the callback
            if (urlParams.has('popup')) {
                // Make an AJAX request to the callback endpoint
                fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    // Send result to parent window
                    if (window.opener) {
                        window.opener.postMessage({
                            type: 'GOOGLE_LOGIN_RESULT',
                            success: data.success,
                            message: data.message,
                            redirect: data.redirect || null
                        }, window.location.origin);
                    }
                    
                    // Close the popup
                    setTimeout(() => {
                        window.close();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Send error to parent window
                    if (window.opener) {
                        window.opener.postMessage({
                            type: 'GOOGLE_LOGIN_RESULT',
                            success: false,
                            message: 'An unexpected error occurred. Please try again.'
                        }, window.location.origin);
                    }
                    
                    // Close the popup
                    setTimeout(() => {
                        window.close();
                    }, 500);
                });
            } else {
                // If not a popup request, this shouldn't happen, but close anyway
                setTimeout(() => {
                    window.close();
                }, 2000);
            }
        });
    </script>
</body>
</html>
