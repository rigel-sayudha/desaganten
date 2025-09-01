@echo off
echo ========================================
echo COMPREHENSIVE WHITE SCREEN DIAGNOSIS
echo ========================================
echo.

echo PROBLEM: White screen on http://127.0.0.1:8000/user/surat
echo.

echo ========================================
echo STEP 1: BASIC CONNECTIVITY TEST
echo ========================================
echo.
echo Testing if server responds to basic requests...
echo.
echo Test URLs:
echo 1. http://127.0.0.1:8000/test-user-surat (JSON response)
echo 2. http://127.0.0.1:8000/test-view-simple (Simple HTML view)
echo 3. http://127.0.0.1:8000/ (Home page)
echo 4. http://127.0.0.1:8000/login (Login page)
echo.

echo ========================================
echo STEP 2: AUTHENTICATION TEST
echo ========================================
echo.
echo The route /user/surat requires authentication.
echo You must login first before accessing it.
echo.
echo Test users for login:
echo - terryindra@gmail.com
echo - rigeldonovan@gmail.com  
echo - admin@admin.com
echo.

echo ========================================
echo STEP 3: DEBUG VIEW TEST
echo ========================================
echo.
echo After login, test this URL:
echo http://127.0.0.1:8000/user/surat
echo.
echo Should show DEBUG page with:
echo ✅ User info (blue box)
echo ✅ Surat count (green box)  
echo ✅ Surat list or "No surat" (orange/red box)
echo ✅ Debug info (purple box)
echo ✅ Yellow HTML test box
echo ✅ Console logs in browser
echo.

echo ========================================
echo STEP 4: BROWSER TROUBLESHOOTING
echo ========================================
echo.
echo If still white screen, check:
echo 1. Open browser Developer Tools (F12)
echo 2. Check Console tab for JavaScript errors
echo 3. Check Network tab for failed requests
echo 4. Try different browser (Chrome, Firefox, Edge)
echo 5. Clear browser cache and cookies
echo 6. Disable browser extensions
echo.

echo ========================================
echo STEP 5: SERVER LOG CHECK
echo ========================================
echo.
echo Check Laravel logs for errors:
echo Get-Content storage/logs/laravel.log -Tail 50
echo.

echo Starting browser tests...
echo.

echo Opening test page 1: JSON response test
start http://127.0.0.1:8000/test-user-surat
timeout /t 3 > nul

echo Opening test page 2: Simple view test  
start http://127.0.0.1:8000/test-view-simple
timeout /t 3 > nul

echo Opening test page 3: Home page
start http://127.0.0.1:8000/
timeout /t 3 > nul

echo Opening test page 4: Login page
start http://127.0.0.1:8000/login
timeout /t 2 > nul

echo.
echo ========================================
echo MANUAL TESTING SEQUENCE:
echo ========================================
echo.
echo 1. ✅ Verify JSON test works (should show JSON data)
echo 2. ✅ Verify simple view works (should show styled HTML page)
echo 3. ✅ Verify home page loads
echo 4. ✅ Login with test user
echo 5. ✅ Navigate to: http://127.0.0.1:8000/user/surat
echo 6. ✅ Should see colorful DEBUG page, NOT white screen
echo.
echo If step 5 still shows white screen:
echo - Check browser console for errors
echo - Check if redirected to login
echo - Check server logs
echo.
echo Press any key when ready to continue testing...
pause > nul
