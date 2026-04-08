import { createContext, useContext, useMemo, useState } from "react";
import { useCalculateTotals } from "../utils/useCalculateTotals";

const SelectedProductsContext = createContext(null);

export const SelectedProductsProvider = ({ children }) => {
  const savedData =
    JSON.parse(sessionStorage?.getItem("Selected Products")) || [];
  const [selectedProducts, setSelectedProducts] = useState(savedData || []);
  const { total } = useCalculateTotals(selectedProducts);

  return (
    <SelectedProductsContext.Provider
      value={{
        selectedProducts,
        setSelectedProducts,
        total,
      }}
    >
      {children}
    </SelectedProductsContext.Provider>
  );
};

// custom hook (clean usage)
export const useSelectedProducts = () => {
  const context = useContext(SelectedProductsContext);
  if (!context) {
    throw new Error(
      "useSelectedProducts must be used inside SelectedProductsProvider",
    );
  }
  return context;
};
