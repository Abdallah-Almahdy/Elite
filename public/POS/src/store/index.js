import { configureStore } from "@reduxjs/toolkit";
import productSlice from "./reducers/productSlice";
import orderSlice from "./reducers/orderSlice";
import userSlice from "./reducers/userSlice";
import draftSlice from "./reducers/draftSlice";

export const store = configureStore({
  reducer: {
    product: productSlice,
    order: orderSlice,
    user: userSlice,
    draft: draftSlice,
  },
});
