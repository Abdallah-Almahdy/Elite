

# Controllers Documentation

This project organizes controllers under:

```
app/Http/Controllers/
```

Each subfolder groups controllers by area of responsibility.

---

## Top-level Files

| File             | Purpose                                                  |
| ---------------- | -------------------------------------------------------- |
| `Controller.php` | Base controller class extended by all other controllers. |

---

## Folders

### 1. **Api**

Controllers that serve API endpoints for mobile apps or external clients.
Mostly stateless (token-based).

| Controller                   | Purpose                                       |
| ---------------------------- | --------------------------------------------- |
| `AuthController.php`         | API authentication endpoints.                 |
| `BanaresController.php`      | Manage banners through API.                   |
| `BanaresControllerOLD.php`   | Legacy version of above.                      |
| `ContactUsController.php`    | API for contact-us form submissions.          |
| `DeliveryController.php`     | Manage delivery through API.                  |
| `FavoritesController.php`    | Manage favorite products through API.         |
| `OrdersApiController.php`    | Handle orders through API.                    |
| `OrdersApiControllerOLD.php` | Legacy version of above.                      |
| `OrdersController.php`       | Alternative order API controller.             |
| `OrdersControllerOLD.php`    | Legacy version.                               |
| `ProductsController.php`     | Manage products through API.                  |
| `ProductsController.php`     | Legacy version.                               |
| `PrompCodeController.php`    | Manage promo codes through API.               |
| `RatesController.php`        | Submit and fetch product ratings through API. |
| `SectionsController.php`     | Manage sections through API.                  |
| `SectionsControllerOLD.php`  | Legacy version.                               |
| `AboutUsController.php`      | Manage About Us through API.                  |
| `NotifictionController.php`  | Manage Notifications through API.             |
| `ConfigController.php`       | Manage Configrations and versions through API.|


---

### 2. **Auth**

Controllers for authentication, password resets, verification, etc.
Web-based (session auth).

| Controller                                    | Purpose                                    |
| --------------------------------------------- | ------------------------------------------ |
| `AuthenticatedSessionController.php`          | Login & logout.                            |
| `ConfirmablePasswordController.php`           | Confirm password before sensitive actions. |
| `cusomerUserController.php`                   | Customer registration & login.             |
| `EmailVerificationNotificationController.php` | Resend verification emails.                |
| `EmailVerificationPromptController.php`       | Prompt for email verification.             |
| `NewPasswordController.php`                   | Set a new password (forgot password flow). |
| `PasswordController.php`                      | Change password while logged in.           |
| `PasswordResetLinkController.php`             | Request password reset link.               |
| `RegisteredUserController.php`                | Register a new user.                       |
| `VerifyEmailController.php`                   | Verify email address.                      |

---

### 3. **Back**

Controllers for admin dashboard (backend).

| Controller                                | Purpose                                    |
| ----------------------------------------- | ------------------------------------------ |
| `BanaresController.php`                   | Manage banners in admin.                   |
| `CompanisController.php`                  | Manage companies in admin.                 |
| `OrdersController.php`                    | Manage orders in admin.                    |
| `PermissionsController.php`               | Manage user permissions.                   |
| `ProductsController.php`                  | Manage products in admin.                  |
| `ProfileController.php`                   | Edit admin profile.                        |
| `PromocodesController.php`                | Manage promo codes.                        |
| `SectionsController.php`                  | Manage sections in admin.                  |
| `StaticsController.php`                   | General admin statistics.                  |
| `UserController.php`                      | Manage admin users.                        |
| `AboutUsController.php`                   | Manage About Us in admin.                  |
| `ContactUsController.php`                 | Manage Contact Us in admin.                |
| `CutomerController.php`                   | Manage Cutomer in admin.                   |
| `IgredientController.php`                 | Manage Igredients in admin.                |
| `KitchenController.php`                   | Manage Kitchens in admin.                  |
| `NotificationsController.php`             | Manage Notifications in admin.             |
| `PrintersController.php`                  | Manage Printers in admin.                  |
| `PrintJobController.php`                  | Manage PrintJobs in admin.                 |
| `RatesController.php`                     | Manage Rates in admin.                     |
| `RecipeController.php`                    | Manage Recipes in admin.                   |
| `UnitController.php`                      | Manage Unit admin.                         |
| `WarehouseController.php`                 | Manage Warehouse in admin.                 |
| `WarehouseTransactionsController.php`     | Manage WarehouseTransactions in admin.     |
| `WarehouseTransactionsTypesController.php`| Manage WarehouseTransactionsTypes in admin.|


#### Back/Statics

Statistics-related controllers for admin dashboard.

| Controller                      | Purpose                |
| ------------------------------- | ---------------------- |
| `Orders.php`                    | Order-related stats.   |
| `RatingsStaticsController.php`  | Product rating stats.  |
| `SectionsStaticsController.php` | Section-related stats. |
| `StaticsOrdersController.php`   | Detailed order stats.  |
| `UsersStaticsController.php`    | User stats.            |

#### Back/tools

Various utility controllers.

| Controller                    | Purpose                          |
| ----------------------------- | -------------------------------- |
| `CheckoutController.php`             | Checkout-related logic.          |
| `NotificationsController.php` | Admin notifications.             |
| `PaymentController.php`       | Payment integrations.            |
| `PaymobFinalController.php`          | Paymob-specific payment handler. |

---

### 4. **front**

Frontend controllers for customer-facing website.

| Controller                 | Purpose                                   |
| -------------------------- | ----------------------------------------- |
| `HomeScreenController.php` | Customer homepage, product browsing, etc. |

---

