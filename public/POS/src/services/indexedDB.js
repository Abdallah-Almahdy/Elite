import { openDB } from "idb";

// Initialize DB
export const initDB = async () => {
  return openDB("pos-db", 4, {
    upgrade(db) {
      if (!db.objectStoreNames.contains("invoice")) {
        db.createObjectStore("invoice", { keyPath: "id", autoIncrement: true });
      }

      // ⭐ NEW — DRAFTS STORE
      if (!db.objectStoreNames.contains("drafts")) {
        db.createObjectStore("drafts", { keyPath: "id" });
      }

      if (!db.objectStoreNames.contains("products")) {
        db.createObjectStore("products", { keyPath: "id" });
      }

      if (!db.objectStoreNames.contains("users")) {
        db.createObjectStore("users", { keyPath: "id" });
      }
    },
  });
};

// Save order offline
export const saveOrderOffline = async (order) => {
  const db = await initDB();
  if (!order.id) order.id = Date.now();
  await db.put("invoice", order);
};

// Get all offline orders
export const getOfflineOrders = async () => {
  const db = await initDB();
  return db.getAll("invoice");
};

// Remove order after syncing
export const removeOrder = async (id) => {
  const db = await initDB();
  await db.delete("invoice", id);
};

// Save draft offline
export const saveDraftOffline = async (draft) => {
  const db = await initDB();
  if (draft.id) await db.put("drafts", draft);
};

// Get all offline drafts
export const getOfflineDrafts = async () => {
  const db = await initDB();
  return db.getAll("drafts");
};

// Remove draft after syncing
export const removeDRafts = async (id) => {
  const db = await initDB();
  await db.delete("drafts", id);
};

export const formDataToObject = (formData) => {
  const obj = {};

  for (let [key, value] of formData.entries()) {
    obj[key] = value;
  }

  return obj;
};
// Save draft offline
export const saveUserOffline = async (user) => {
  const db = await initDB();
  const userObject = user instanceof FormData ? formDataToObject(user) : user;

  if (!userObject.id) userObject.id = Date.now();

  await db.put("users", userObject);
};

// Get all offline drafts
export const getOfflineUsers = async () => {
  const db = await initDB();
  return db.getAll("users");
};

// Remove draft after syncing
export const removeUsers = async (id) => {
  const db = await initDB();
  await db.delete("users", id);
};

export const getAllDrafts = async () => {
  const db = await initDB();
  return db.getAll("drafts");
};

export const getDraftById = async (id) => {
  const db = await initDB();
  return db.get("drafts", id);
};

export const saveProductsOffline = async (products) => {
  const db = await initDB();
  const tx = db.transaction("products", "readwrite");
  const store = tx.objectStore("products");

  for (const product of products) {
    if (!product.id) {
      console.error("Product missing id:", product);
      continue;
    }
    await store.put(product); // store each product individually
  }

  await tx.done;
};

export const getAllProducts = async () => {
  const db = await initDB();
  return db.getAll("products");
};
