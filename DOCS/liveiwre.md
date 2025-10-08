

# Livewire Components Documentation

This project contains a modular set of Livewire components located in:

```
app/Livewire/
```

Each folder represents a functional area or feature of the application.
All files are Livewire components (`php` classes) responsible for dynamic, reactive parts of the UI.

---

## Directory Structure

```
app/Livewire/
├── Actions/
├── Banares/
├── BanaresOld/
├── cart/
├── Delivery/
├── Forms/
├── Notifications/
├── Orders/
├── Permissions/
├── Products/
├── Search/
├── Sections/
├── Statices/
```

---

## Components by Feature

### Actions

Reusable general actions for authenticated users.

| File         | Purpose                            |
| ------------ | ---------------------------------- |
| `Logout.php` | Handles user logout functionality. |

---

### Banares

Livewire components for managing **Banares (banners?)** in the admin dashboard.

| File         | Purpose                  |
| ------------ | ------------------------ |
| `Create.php` | Create a new Banares.    |
| `Index.php`  | List and manage Banares. |

---

### BanaresOld

Legacy or previous version of Banares components.
Kept likely for backward compatibility or reference.

| File         | Purpose                          |
| ------------ | -------------------------------- |
| `Create.php` | Old version of Banares creation. |
| `Index.php`  | Old version of Banares listing.  |

---

### cart

Components related to the shopping cart on the frontend.

| File               | Purpose                              |
| ------------------ | ------------------------------------ |
| `AddToCartBtn.php` | Button to add a product to the cart. |
| `index.php`        | View and manage the cart contents.   |

---

### Delivery

Components for managing delivery tasks.

| File        | Purpose                     |
| ----------- | --------------------------- |
| `Index.php` | List and manage deliveries. |

---

### Forms

Reusable form components for login and other forms.

| File            | Purpose               |
| --------------- | --------------------- |
| `LoginForm.php` | Login form component. |

---

### Notifications

Components for user/admin notifications.

| File                    | Purpose                                  |
| ----------------------- | ---------------------------------------- |
| `NotificationBadge.php` | Badge showing unread notification count. |
| `Notifications.php`     | List and manage notifications.           |

---

### Orders

Components for managing customer orders.

| File                  | Purpose                               |
| --------------------- | ------------------------------------- |
| `Index.php`           | List all orders.                      |
| `OrderDetails.php`    | Show order details (current version). |
| `OrderDetailsOLD.php` | Legacy order details view.            |

---

### Permissions

Components for managing user or role permissions.

| File        | Purpose                    |
| ----------- | -------------------------- |
| `Index.php` | List and edit permissions. |

---

### Products

Components for managing products in the catalog.

| File             | Purpose                                                 |
| ---------------- | ------------------------------------------------------- |
| `Create.php`     | Create a new product.                                   |
| `Edit.php`       | Edit an existing product.                               |
| `Index.php`      | List all products.                                      |
| `Index_copy.php` | Likely a backup or experimental version of `Index.php`. |

---

### Search

Search functionality.

| File         | Purpose                  |
| ------------ | ------------------------ |
| `Search.php` | Live search bar or page. |

---

### Sections

Components for managing main and sub-sections.

| File         | Purpose                   |
| ------------ | ------------------------- |
| `Create.php` | Create a new section.     |
| `Delete.php` | Delete a section.         |
| `Edit.php`   | Edit an existing section. |
| `Index.php`  | List sections.            |
| `Show.php`   | Show section details.     |

---

### Statices

Components for statistical dashboards or reports.

| File                     | Purpose                                  |
| ------------------------ | ---------------------------------------- |
| `StaticesController.php` | Livewire controller for statistics page. |

---

## Notes

* Each feature folder encapsulates all its related components, making it easier to maintain.
* Files ending in `OLD.php` or `copy.php` appear to be legacy or backup versions; review if still needed.
* Keep consistent naming conventions when adding new components.
* Consider cleaning up unused legacy components if no longer in use.

