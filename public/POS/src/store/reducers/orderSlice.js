import { createSlice } from "@reduxjs/toolkit";
import {
  saveOrderOffline,
  getOfflineOrders,
  removeOrder,
} from "../../services/indexedDB";
import { sendOrderToBackend } from "../../api/baseURL";

const initialState = {
  offlineOrders: [], // optional: track offline orders in Redux
};

const orderSlice = createSlice({
  name: "order",
  initialState,
  reducers: {
    setOfflineOrders: (state, action) => {
      state.offlineOrders = action.payload;
    },
  },
});

export const { setOfflineOrders } = orderSlice.actions;

// Thunk: Save order (online/offline)
export const saveOrder = (order) => async (dispatch) => {
  if (navigator.onLine) {
    try {
      const returnedInvoice = await sendOrderToBackend(order);
      return returnedInvoice;
    } catch (err) {
      await saveOrderOffline(order);
      const offlineOrders = await getOfflineOrders();
      dispatch(setOfflineOrders(offlineOrders));
    }
  } else {
    await saveOrderOffline(order);
    const offlineOrders = await getOfflineOrders();
    dispatch(setOfflineOrders(offlineOrders));
  }
};

// Thunk: Sync offline orders when online
export const syncOfflineOrders = () => async (dispatch) => {
  const offlineOrders = await getOfflineOrders();
  for (const order of offlineOrders) {
    try {
      await sendOrderToBackend(order);
      await removeOrder(order.id);
    } catch (err) {
      console.log("Sync failed, will retry later", err);
    }
  }
  const updatedOfflineOrders = await getOfflineOrders();
  dispatch(setOfflineOrders(updatedOfflineOrders));
};

export default orderSlice.reducer;
