var AuthManager = (function() {
    var TOKEN_KEY = 'access_token';
    var USER_KEY = 'user_data';
    
    function getToken() {
        return localStorage.getItem(TOKEN_KEY);
    }
    
    function setToken(token) {
        localStorage.setItem(TOKEN_KEY, token);
    }
    
    function getUser() {
        var userData = localStorage.getItem(USER_KEY);
        return userData ? JSON.parse(userData) : null;
    }
    
    function setUser(user) {
        localStorage.setItem(USER_KEY, JSON.stringify(user));
    }
    
    function clearAuth() {
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
    }
    
    function isAuthenticated() {
        return !!getToken();
    }
    
    function logout() {
        clearAuth();
        window.location.href = 'index.php';
    }
    
    function setupAjaxDefaults() {
        if (typeof $ !== 'undefined' && $.ajaxSetup) {
            $.ajaxSetup({
                beforeSend: function(xhr) {
                    var token = getToken();
                    if (token) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + token);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 401) {
                        clearAuth();
                        alert('Session expired. Please login again.');
                        window.location.href = 'index.php';
                    }
                }
            });
        }
    }
    
    function login(email, password, successCallback, errorCallback) {
        $.ajax({
            url: 'api/login.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ email: email, password: password }),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    setToken(response.access_token);
                    setUser(response.user);
                    if (successCallback) successCallback(response);
                }
            },
            error: function(xhr) {
                if (errorCallback) {
                    var response = xhr.responseJSON || { message: 'Login failed' };
                    errorCallback(response);
                }
            }
        });
    }
    
    function apiCall(url, method, data, successCallback, errorCallback) {
        var ajaxConfig = {
            url: url,
            type: method || 'GET',
            dataType: 'json',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            },
            success: function(response) {
                if (successCallback) successCallback(response);
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    clearAuth();
                    window.location.href = 'index.php';
                    return;
                }
                if (errorCallback) {
                    var response = xhr.responseJSON || { message: 'Request failed' };
                    errorCallback(response);
                }
            }
        };
        
        if (data) {
            if (method === 'GET') {
                ajaxConfig.data = data;
            } else {
                ajaxConfig.contentType = 'application/json';
                ajaxConfig.data = JSON.stringify(data);
            }
        }
        
        $.ajax(ajaxConfig);
    }
    
    $(document).ready(function() {
        setupAjaxDefaults();
    });
    
    return {
        getToken: getToken,
        setToken: setToken,
        getUser: getUser,
        setUser: setUser,
        clearAuth: clearAuth,
        isAuthenticated: isAuthenticated,
        logout: logout,
        login: login,
        apiCall: apiCall,
        setupAjaxDefaults: setupAjaxDefaults
    };
})();
