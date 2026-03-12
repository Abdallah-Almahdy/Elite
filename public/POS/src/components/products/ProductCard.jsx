export default function ProductCard({ product, onAdd, userPreference }) {
  const addProduct = () => {
    const defaultUnit = product.Units?.[0];

    const newObj = {
      id: product?.id,
      name: product?.name,
      weight: product.is_weight ? product.quantity : 0.0,
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

    onAdd(newObj);
  };

  return (
    <button
      key={product?.id}
      onClick={addProduct}
      className={`border border-gray-300 rounded-xl shadow-sm p-2 flex flex-col bg-white hover:shadow-md transition ${userPreference === "textWrap" ? `h-24 lg:h-20` : userPreference === "largeWrap" ? `h-40` : `h-[7rem]`}`}
    >
      {/* Product Image */}
      {userPreference !== "textWrap" && (
        <img
          src={product?.image}
          alt={product?.name}
          className={`w-full  object-contain mb-2 mx-auto text-xs ${userPreference === "largeWrap" ? `h-20` : `h-12`}`}
        />
      )}

      {/* Product Name */}
      <h1
        className={` font-semibold text-center text-gray-700 line-clamp-2 ${userPreference === "textWrap" ? `h-[55%]` : `h-[30%]`} ${userPreference === "smallWrap" ? `text-xs` : `text-sm`}`}
      >
        {product?.name} ({product?.Units?.[0]?.name})
      </h1>

      {/* Price */}
      <h2
        className={`text-green-600 font-bold mt-1 text-start ${userPreference === "smallWrap" ? `text-xs` : `text-sm`}`}
      >
        {typeof product?.Units?.[0]?.sallprice === "string"
          ? Number(product?.Units?.[0]?.sallprice).toFixed(2)
          : 0.0}
        {product?.unit_name}
      </h2>
    </button>
  );
}
