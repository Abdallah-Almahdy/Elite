import { createContext, useContext } from "react";
import useProductsLogic from "../hooks/home/useProductsLogic";
const ProductsContext = createContext(null);

export const ProductsProvider = ({ children }) => {
  const productsLogic = useProductsLogic();

  return (
    <ProductsContext.Provider value={productsLogic}>
      {children}
    </ProductsContext.Provider>
  );
};

export const useProducts = () => {
  const context = useContext(ProductsContext);
  if (!context) {
    throw new Error("useProducts must be used inside ProductsProvider");
  }
  return context;
};
