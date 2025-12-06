# API Documentation

## Overview
This API allows for managing expenses, income, and lending records. All endpoints expect POST requests and return JSON responses.

## Endpoints

### 1. Add Lending Record
- **URL**: `/api/lending.php`
- **Method**: `POST`
- **Parameters**:
    - `name` (string, required): Name of the borrower/lender.
    - `date` (date, required): Date of lending.
    - `amount` (number, required): Amount lent.
    - `description` (string, required): Description of the transaction.
    - `status` (string, required): Status (e.g., 'pending', 'received').
- **Response**:
    ```json
    {
        "status": "success",
        "message": "New lending record created successfully"
    }
    ```

### 2. Add Expense
- **URL**: `/api/add-expense.php`
- **Method**: `POST`
- **Parameters**:
    - `dateexpense` (date, required): Date of expense.
    - `category` (int, required): Category ID.
    - `costitem` (number, required): Cost of the item.
    - `category-description` (string, required): Description.
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Expense added successfully"
    }
    ```

### 3. Add Income
- **URL**: `/api/add-income.php`
- **Method**: `POST`
- **Parameters**:
    - `incomeDate` (date, required): Date of income.
    - `category` (int, required): Category ID.
    - `incomeAmount` (number, required): Amount of income.
    - `description` (string, required): Description.
- **Response**:
    ```json
    {
        "status": "success",
        "message": "Income added successfully"
    }
    ```
