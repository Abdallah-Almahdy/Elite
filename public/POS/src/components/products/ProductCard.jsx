import { useState, useEffect } from "react";
import { useSelectedProducts } from "../../contexts/SelectedProductsContext";

export default function ProductCard({ product, onAdd, userPreference }) {
        const [displayQuantity, setDisplayQuantity] = useState(product?.quantity);
        const {selectedProducts} = useSelectedProducts()
       useEffect(() => {
  const selectedItem = selectedProducts.find(
    (item) => item.id === product.id
  );

   const totalUsedForProduct = selectedProducts
        .filter(p => p.id === product.id)
        .reduce((sum, p) => 
          product?.is_weight
            ? sum + (p.weight || 0)
            : sum + (p.number || 0)
        , 0);

  const usedQuantity = selectedItem ? selectedItem.number : 0;

  setDisplayQuantity(product.quantity - totalUsedForProduct);
}, [product.quantity, selectedProducts]);

  const addProduct = () => {
    const defaultUnit = product.Units?.[0];

    const newObj = {
      id: product?.id,
      name: product?.name,
      // weight: product.is_weight ? product.quantity : 0.0,
      weight: product.is_weight ? 1 : 0.0,
      stock: product.quantity,
      is_weight: product.is_weight,
      is_stock: product.is_stock,
      price: Number(defaultUnit?.sallprice || 0),
      total: Number(defaultUnit?.sallprice || 0),
      quantity: product?.quantity || 0,
      code: defaultUnit?.barcodes?.[0]?.code || "",
      canDecrement: product?.decrementAbility,
      image: product?.image,
      rowKey: `${product?.id}-${defaultUnit?.id}`,
      unit: {
        id: defaultUnit?.id,
        name: defaultUnit?.name || "",
        price: Number(defaultUnit?.sallprice || 0),
        code: defaultUnit?.barcodes?.[0]?.code || "",
      },
      number: product?.number,
      Units: product?.Units,
      unit_name: product?.unit_name,
      selectedUnit: {
        [Number(defaultUnit?.id)]: {
          value: defaultUnit?.id,
          label: defaultUnit?.name,
          unitData: defaultUnit,
        },
      },
      baseUnit: {
        value: defaultUnit?.id,
        label: defaultUnit?.name,
        unitData: Number(defaultUnit?.sallprice || 0),
      },
      chosenUnit: {
        value: defaultUnit?.id,
        label: defaultUnit?.name,
        unitData: Number(defaultUnit?.sallprice || 0),
      },
    };
    
     setDisplayQuantity(prev => {
  const newQty = prev - 1;
  return newQty < 0 ? 0 : newQty;
});
    onAdd(newObj);
  };
  const usedByOtherUnits = selectedProducts
        .filter(p => p.id === product.id)
        .reduce((sum, p) => 
          product?.is_weight
            ? sum + (p.weight || 0)
            : sum + (p.number || 0)
        , 0);

  
        const availableStock = product.stock - usedByOtherUnits;


  return (
    <button
      key={product?.id}
      onClick={addProduct}
      className={`border border-gray-300 rounded-xl shadow-sm p-2 flex flex-col bg-white hover:shadow-md transition ${userPreference === "textWrap" ? `h-24 lg:h-20` : userPreference === "largeWrap" ? `h-40` : `h-[7rem]`}`}
    >
      <div className="w-full flex relative">
        {/* Product Image */}
      {userPreference !== "textWrap" && (
        <img
          src={product?.image}
          alt={product?.name}
          className={`w-[95%] object-contain  mx-auto text-xs ${userPreference === "largeWrap" ? `h-20 mb-2` : `h-12 mb-0`}`}
        />
      )}

  <span
  className={`absolute -top-3 -left-3 flex items-center justify-center 
  w-6 h-6 text-xs font-bold rounded-full shadow-sm
  ${
    displayQuantity === 0
      ? "bg-red-500 text-white"
      : product?.quantity > 10
      ? "bg-green-500 text-white"
      : "bg-yellow-500 text-white"
  }`}
>
  {displayQuantity}
</span>   

  </div>

      {/* Product Name */}
      <h1
        className={`font-bold text-gray-700 line-clamp-1 ${userPreference === "textWrap" ? `h-[55%] text-start` : `h-[30%] text-center`} ${userPreference === "smallWrap" ? `text-sm` : `text-base`}`}
      >
        {product?.name} ({product?.Units?.[0]?.name})
      </h1>

      {/* Price */}
      <h2
        className={`text-green-600 font-bold  text-start ${userPreference === "smallWrap" ? `text-xs` : `text-sm`} ${userPreference === 'textWrap' ? `mt-3` :`mt-1`}`}
      >
        {typeof product?.Units?.[0]?.sallprice === "string"
          ? Number(product?.Units?.[0]?.sallprice).toFixed(2)
          : 0.0}
        {product?.unit_name}
      </h2>
    </button>
  );
}
