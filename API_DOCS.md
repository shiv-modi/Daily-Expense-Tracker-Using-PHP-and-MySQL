# API Documentation

## Overview
This API allows for managing expenses, income, categories, and lending records. All endpoints are located in the `includes/api/` directory and return JSON responses.

## Authentication
All endpoints require an active session. User authentication is managed via `$_SESSION['detsuid']`.

## Response Format
All endpoints return JSON responses with the following structure:
```json
{
    "status": "success" | "error",
    "message": "Description of the result"
}
```

## Endpoints

### 1. Add Expense
- **URL**: `/includes/api/add-expense.php`
- **Method**: `POST`
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

### 2. Add Income
- **URL**: `/includes/api/add-income.php`
- **Method**: `POST`
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

### 3. Add Category
- **URL**: `/includes/api/add-category.php`
- **Method**: `POST`
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

### 4. Get Categories
- **URL**: `/includes/api/get-categories.php`
- **Method**: `GET`
- **Parameters**:
    - `mode` (string, optional): Filter by mode - "expense" or "income"
- **Response**:
    ```json
    {
        "status": "success",
        "categories": [
            {
                "categoryid": 1,
                "categoryname": "Food",
                "mode": "expense"
            }
        ]
    }
    ```

### 5. Update Expense
- **URL**: `/includes/api/update-expense.php`
- **Method**: `POST`
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

### 6. Update Income
- **URL**: `/includes/api/update-income.php`
- **Method**: `POST`
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

### 7. Delete Expense
- **URL**: `/includes/api/delete-expense.php`
- **Method**: `POST`
- **Parameters**:
    - `id` (int, required): ID of the expense to delete
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Expense deleted successfully"
    }
    ```

### 8. Delete Income
- **URL**: `/includes/api/delete-income.php`
- **Method**: `POST`
- **Parameters**:
    - `id` (int, required): ID of the income to delete
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Income deleted successfully"
    }
    ```

### 9. Export CSV
- **URL**: `/includes/api/export-csv.php`
- **Method**: `GET`
- **Parameters**: None (uses session for user ID)
- **Response**: Downloads a CSV file with all transactions
- **CSV Format**: 
    ```
    Date,Particulars,Expense,Income,Category,Is_Lending
    2024-01-15,"Monthly salary",0,5000,Salary,0
    2024-01-16,"Groceries",150,0,Food,0
    ```

### 10. Import CSV
- **URL**: `/includes/api/import-csv.php`
- **Method**: `POST`
- **Content-Type**: `multipart/form-data`
- **Parameters**:
    - `csv-file` (file, required): CSV file to import
- **CSV Format Expected**:
    ```
    Date,Particulars,Expense,Income,Category,Is_Lending
    2024-01-15,"Description",expense_amount,income_amount,category_name,0_or_1
    ```
- **Response**:
    ```json
    {
        "status": "success",
        "message": "CSV imported successfully. X records added."
    }
    ```

### 11. Add Lending Record
- **URL**: `/includes/api/lending.php`
- **Method**: `POST`
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

## Error Handling

All endpoints return appropriate error messages when:
- User is not authenticated (session expired)
- Required parameters are missing
- Database operations fail
- Invalid data is provided

Example error response:
```json
{
    "status": "error",
    "message": "Description of what went wrong"
}
```

## Database Tables

The API interacts with the following PostgreSQL tables:
- `users` - User accounts
- `tblcategory` - Expense and income categories
- `tblexpense` - Expense records
- `tblincome` - Income records
- `lending` - Lending/borrowing records

Note: All column names in PostgreSQL are lowercase (e.g., `userid`, `categoryname`, `expensedate`).
