# API Documentation

## Overview
This API allows for managing expenses, income, categories, and lending records. All endpoints are located in the `includes/api/` directory and return JSON responses.

## Authentication

### JWT Authentication (Recommended)
The API supports JWT (JSON Web Token) authentication with 90-day token expiry. Include the token in the `Authorization` header:

```
Authorization: Bearer <access_token>
```

### Session Authentication (Legacy)
For backward compatibility, the API also supports PHP session-based authentication via `$_SESSION['detsuid']`.

### Authentication Flow
1. Call the Login API with email and password
2. Receive an `access_token` in the response
3. Store the token in localStorage (for web apps) or secure storage
4. Include the token in all subsequent API requests via the Authorization header

## Response Format
All endpoints return JSON responses with the following structure:
```json
{
    "status": "success" | "error",
    "message": "Description of the result"
}
```

---

## JWT Authentication Endpoints

### 1. Login API
- **URL**: `/includes/api/login.php`
- **Method**: `POST`
- **Content-Type**: `application/json` or `application/x-www-form-urlencoded`
- **Authentication**: None required
- **Parameters**:
    - `email` (string, required): User's email address
    - `password` (string, required): User's password
- **Request Example (JSON)**:
    ```json
    {
        "email": "user@example.com",
        "password": "yourpassword"
    }
    ```
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Login successful",
        "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com"
        }
    }
    ```
- **Error Response**:
    ```json
    {
        "status": "error",
        "message": "Invalid credentials"
    }
    ```

### 2. Dashboard API
- **URL**: `/includes/api/dashboard.php`
- **Method**: `GET`
- **Authentication**: Required (JWT or Session)
- **Parameters**: None
- **Response**:
    ```json
    {
        "status": "success",
        "data": {
            "user": {
                "name": "John Doe",
                "email": "user@example.com"
            },
            "today_expense": 150.00,
            "yesterday_expense": 200.00,
            "monthly_expense": 3500.00,
            "total_expense": 15000.00,
            "total_income": 25000.00,
            "balance": 10000.00,
            "chart": {
                "labels": ["2024-01-01", "2024-01-02"],
                "data": [100, 150]
            },
            "categories": [
                {
                    "category": "Food",
                    "total_expense": 500.00
                }
            ]
        }
    }
    ```

### 3. List Transactions API
- **URL**: `/includes/api/transactions.php`
- **Method**: `GET`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `page` (int, optional): Page number (default: 1)
    - `limit` (int, optional): Records per page (default: 10, max: 100)
    - `type` (string, optional): Filter by type - "all", "expense", or "income" (default: "all")
- **Response**:
    ```json
    {
        "status": "success",
        "data": {
            "transactions": [
                {
                    "id": 1,
                    "type": "Expense",
                    "category": "Food",
                    "amount": 150.00,
                    "description": "Groceries",
                    "date": "2024-01-15"
                }
            ],
            "pagination": {
                "current_page": 1,
                "total_pages": 5,
                "total_records": 50,
                "limit": 10
            }
        }
    }
    ```

### 4. List Lending API
- **URL**: `/includes/api/lending-list.php`
- **Method**: `GET`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `page` (int, optional): Page number (default: 1)
    - `limit` (int, optional): Records per page (default: 10, max: 100)
    - `status` (string, optional): Filter by status - "all", "pending", or "received" (default: "all")
- **Response**:
    ```json
    {
        "status": "success",
        "data": {
            "lending": [
                {
                    "id": 1,
                    "name": "John Smith",
                    "date_of_lending": "2024-01-15",
                    "amount": 500.00,
                    "description": "Emergency loan",
                    "status": "pending",
                    "created_at": "2024-01-15 10:30:00"
                }
            ],
            "summary": {
                "total_pending": 1500.00,
                "total_received": 3000.00
            },
            "pagination": {
                "current_page": 1,
                "total_pages": 3,
                "total_records": 25,
                "limit": 10
            }
        }
    }
    ```

### 5. Report API
- **URL**: `/includes/api/report.php`
- **Method**: `GET`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `type` (string, required): Report type - "expense", "income", "pending", or "received"
    - `start_date` (date, optional): Start date (YYYY-MM-DD format, default: 30 days ago)
    - `end_date` (date, optional): End date (YYYY-MM-DD format, default: today)
- **Response (Expense/Income Report)**:
    ```json
    {
        "status": "success",
        "report_type": "expense",
        "date_range": {
            "start": "2024-01-01",
            "end": "2024-01-31"
        },
        "data": [
            {
                "id": 1,
                "date": "2024-01-15",
                "category": "Food",
                "amount": 150.00,
                "description": "Groceries"
            }
        ],
        "summary": {
            "total_records": 25,
            "total_amount": 3500.00
        }
    }
    ```
- **Response (Pending/Received Report)**:
    ```json
    {
        "status": "success",
        "report_type": "pending",
        "date_range": {
            "start": "2024-01-01",
            "end": "2024-01-31"
        },
        "data": [
            {
                "id": 1,
                "name": "John Smith",
                "date": "2024-01-15",
                "amount": 500.00,
                "description": "Emergency loan",
                "status": "pending"
            }
        ],
        "summary": {
            "total_records": 5,
            "total_pending": 2500.00
        }
    }
    ```

---

## Data Management Endpoints

### 6. Add Expense
- **URL**: `/includes/api/add-expense.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `dateexpense` (date, required): Date of expense (YYYY-MM-DD format)
    - `category` (int, required): Category ID
    - `costitem` (number, required): Cost of the item
    - `category-description` (string, required): Description of the expense
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Expense added successfully"
    }
    ```

### 7. Add Income
- **URL**: `/includes/api/add-income.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `incomeDate` (date, required): Date of income (YYYY-MM-DD format)
    - `category` (int, required): Category ID
    - `incomeAmount` (number, required): Amount of income
    - `description` (string, required): Description of the income
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Income added successfully"
    }
    ```

### 8. Add Category
- **URL**: `/includes/api/add-category.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `category-name` (string, required): Name of the new category
    - `mode` (string, required): Type of category - "expense" or "income"
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Category added successfully"
    }
    ```

### 9. Get Categories
- **URL**: `/includes/api/get-categories.php`
- **Method**: `GET`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `mode` (string, optional): Filter by mode - "expense" or "income"
- **Response**:
    ```json
    {
        "status": "success",
        "data": [
            {
                "CategoryId": 1,
                "CategoryName": "Food",
                "Mode": "expense"
            }
        ]
    }
    ```

### 10. Update Expense
- **URL**: `/includes/api/update-expense.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `expenseid` (int, required): ID of the expense to update
    - `dateexpense` (date, required): Updated date of expense
    - `category` (int, required): Updated category ID
    - `cost` (number, required): Updated cost
    - `description` (string, required): Updated description
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Expense updated successfully"
    }
    ```

### 11. Update Income
- **URL**: `/includes/api/update-income.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `incomeid` (int, required): ID of the income to update
    - `incomeDate` (date, required): Updated date of income
    - `category` (int, required): Updated category ID
    - `incomeAmount` (number, required): Updated amount
    - `description` (string, required): Updated description
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Income updated successfully"
    }
    ```

### 12. Delete Transaction
- **URL**: `/includes/api/delete-transaction.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `id` (int, required): ID of the transaction to delete
    - `type` (string, required): Type of transaction - "expense" or "income"
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Expense deleted successfully"
    }
    ```

### 13. Add Lending Record
- **URL**: `/includes/api/lending.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `name` (string, required): Name of the borrower/lender
    - `date` (date, required): Date of lending (YYYY-MM-DD format)
    - `amount` (number, required): Amount lent
    - `description` (string, required): Description of the transaction
    - `status` (string, required): Status - "pending" or "received"
- **Response**:
    ```json
    {
        "status": "success",
        "message": "New lending record created successfully"
    }
    ```

### 14. Delete Lending Record
- **URL**: `/includes/api/delete-lending.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `id` (int, required): ID of the lending record to delete
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Lending record deleted successfully"
    }
    ```
- **Error Responses**:
    - 400: Invalid lending ID
    - 404: Lending record not found
    - 401: Unauthorized

---

## Import/Export Endpoints

### 15. Export CSV
- **URL**: `/includes/api/export-csv.php`
- **Method**: `GET`
- **Authentication**: Required (JWT or Session)
- **Parameters**:
    - `type` (string, optional): Data type - "all", "expense", "income", or "lending" (default: "all")
    - `start_date` (date, optional): Start date filter
    - `end_date` (date, optional): End date filter
- **Response**: Downloads a CSV file with transactions
- **CSV Format**: 
    ```
    Date,Particulars,Expense,Income,Category,Is_Lending
    2024-01-15,"Monthly salary",0,5000,Salary,no
    2024-01-16,"Groceries",150,0,Food,no
    ```

### 16. Import CSV
- **URL**: `/includes/api/import-csv.php`
- **Method**: `POST`
- **Authentication**: Required (JWT or Session)
- **Content-Type**: `multipart/form-data`
- **Parameters**:
    - `csv-file` (file, required): CSV file to import
- **CSV Format Expected**:
    ```
    Date,Particulars,Expense,Income,Category,Is_Lending
    2024-01-15,"Description",expense_amount,income_amount,category_name,yes_or_no
    ```
- **Response**:
    ```json
    {
        "status": "success",
        "message": "25 records imported successfully",
        "imported": 25,
        "errors": []
    }
    ```

---

## JavaScript Integration

### Global AJAX Setup
Include the `auth.js` file to automatically add JWT tokens to all AJAX requests:

```html
<script src="js/auth.js"></script>
```

### Using AuthManager
```javascript
// Check if user is authenticated
if (AuthManager.isAuthenticated()) {
    console.log('User is logged in');
}

// Get current user
var user = AuthManager.getUser();

// Login
AuthManager.login('email@example.com', 'password', 
    function(response) {
        console.log('Login successful', response);
    },
    function(error) {
        console.log('Login failed', error.message);
    }
);

// Logout
AuthManager.logout();

// Make authenticated API call
AuthManager.apiCall('api/dashboard.php', 'GET', null, 
    function(response) {
        console.log('Dashboard data', response.data);
    }
);
```

---

## Error Handling

All endpoints return appropriate error messages when:
- User is not authenticated (401 Unauthorized)
- Token is expired (401 Unauthorized)
- Required parameters are missing (400 Bad Request)
- Database operations fail (500 Internal Server Error)
- Invalid data is provided (400 Bad Request)

**Error Response Examples**:
```json
{
    "status": "error",
    "message": "Unauthorized - Invalid or expired token"
}
```

```json
{
    "status": "error",
    "message": "Missing required fields"
}
```

---

## Token Details

- **Algorithm**: HS256 (HMAC with SHA-256)
- **Expiry**: 90 days from issue date
- **Payload Contains**:
    - `user_id`: User's ID
    - `email`: User's email
    - `name`: User's name
    - `iat`: Issued at timestamp
    - `exp`: Expiration timestamp

---

## Database Tables

The API interacts with the following tables:
- `users` - User accounts
- `tblcategory` - Expense and income categories
- `tblexpense` - Expense records
- `tblincome` - Income records
- `lending` - Lending/borrowing records

---

## CORS Support

All API endpoints support CORS with the following headers:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization`

Preflight OPTIONS requests are handled automatically.
