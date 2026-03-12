import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
import { saveProductsOffline } from "../../services/indexedDB";

const initialState = {
  products: [],
  loading: false,
  error: null,
};

export const fetchProducts = createAsyncThunk(
  "products/fetchProducts",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/GetAllProducts");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const saveProductsOfflineThunk = createAsyncThunk(
  "products/saveProductsOffline",
  async (products) => {
    await saveProductsOffline(products); // IndexedDB write
    return products; // optional, you can return what you saved
  },
);

export const productSlice = createSlice({
  name: "product",
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchProducts.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchProducts.fulfilled, (state, action) => {
        state.loading = false;
        state.products = action?.payload?.data;
      })
      .addCase(fetchProducts.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      });
  },
});

export default productSlice.reducer;
