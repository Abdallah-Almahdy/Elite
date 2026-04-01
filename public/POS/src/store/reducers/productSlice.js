import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
import { saveProductsOffline } from "../../services/indexedDB";

const initialState = {
  products: [],
  categories: [],
  searchedResults: [],
  searchedResultsInGrid: [],
  barcodeProduct: [],
  maxPage: 1,
  loading: false,
  error: null,
};

export const fetchCategory = createAsyncThunk(
  "sections/fetchCategory",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/sections");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const searchProductsByName = createAsyncThunk(
  "products/searchProductsByName",
  async (searchName, { rejectWithValue }) => {
    try {
      const response = await api.get(`/product/searchByname?name=${searchName}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const searchProductsByNameInGrid = createAsyncThunk(
  "products/searchProductsByNameInGrid",
  async (searchName, { rejectWithValue }) => {
    try {
      const response = await api.get(`/product/searchByname?name=${searchName}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const searchProductsByBarcode = createAsyncThunk(
  "products/searchProductsByBarcode",
  async (barcodeValue, { rejectWithValue }) => {
    try {
      const response = await api.get(`/product/searchByBarcode?barcode=${barcodeValue}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchCategoryProducts = createAsyncThunk(
  "sections/fetchCategoryProducts",
  async ({id, pageNum, itemNum}, { rejectWithValue }) => {
    try {
      const response = await api.get(`/sections/${id}/products?page=${pageNum}&num=${itemNum}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const saveProductsOfflineThunk = createAsyncThunk(
  "products/saveProductsOffline",
  async (products) => {
    await saveProductsOffline(products); 
    return products; 
  },
);

export const productSlice = createSlice({
  name: "product",
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchCategory.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchCategory.fulfilled, (state, action) => {
        state.loading = false;
        state.categories = action?.payload?.data;
      })
      .addCase(fetchCategory.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchCategoryProducts.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchCategoryProducts.fulfilled, (state, action) => {
        state.loading = false;
        state.products = action?.payload?.data;
        state.maxPage = action?.payload?.meta?.last_page;
      })
      .addCase(fetchCategoryProducts.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(searchProductsByName.fulfilled, (state, action) => {
        state.loading = false;
        state.searchedResults = action?.payload?.data;
      })
      .addCase(searchProductsByName.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(searchProductsByNameInGrid.fulfilled, (state, action) => {
        state.loading = false;
        state.searchedResultsInGrid = action?.payload?.data;
      })
      .addCase(searchProductsByNameInGrid.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(searchProductsByBarcode.fulfilled, (state, action) => {
        state.loading = false;
        state.barcodeProduct = action?.payload?.data;
      })
      .addCase(searchProductsByBarcode.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
  },
});

export default productSlice.reducer;
