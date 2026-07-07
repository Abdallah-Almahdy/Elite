import { FaPlus } from "react-icons/fa6";
import { FiMinus } from "react-icons/fi";
import { ImBin } from "react-icons/im";
import Select from "react-select";
import CreatableSelect from "react-select/creatable";
import { useProducts } from "../../contexts/ProductsContext";

export default function OrderRow({ quantityRefs }) {
  const {
    selectedProducts,
    handleDelete,
    incrementQuantity,
    decrementQuantity,
    ChangeQuantity,
    handleChangeUnit,
  } = useProducts();

   

  // Function To Go to Increment Quantity
  const incQuantity = (objID) => {
    incrementQuantity(objID);
  };

  // Function To Go to Decrement Quantity
  const decQuantity = (objID) => {
    decrementQuantity(objID);
  };

  // Function To Go to Decrement Quantity
  const chngQuantity = (rowKey, val) => {
    ChangeQuantity(rowKey, val);
  };

  // Function To Go to Delete a Product
  const deleteProduct = (objID) => {
    handleDelete(objID);
  };

  return (
    <>
      {selectedProducts?.length > 0 ? (
        selectedProducts?.map((product, index) => {
          const usedByOtherUnits = selectedProducts
        .filter(p => p.id === product.id)
        .reduce((sum, p) => 
          product?.is_weight
            ? sum + (p.weight || 0)
            : sum + (p.number || 0)
        , 0);

  
        const availableStock = product.stock - usedByOtherUnits;
          const unitOptions = product?.Units?.map((u) => ({
            value: u?.id,
            label: `${u?.name} - ${u?.sallprice}${product?.unit_name}`,
            unitData: u,
          }));

          return (
            <tr
              className={`h-[38px] ${(index + 1) % 2 === 0 ? `bg-blue-100 bg-opacity-50` : ``}`}
              key={product?.rowKey}
            >
              <td className=" px-1  border border-gray-300">{index + 1}</td>
              <td className="px-1  border border-gray-300">
                {product?.code || ""}
              </td>
              <td className="px-1 border border-gray-300 font-semibold">{product?.name}</td>
              <td className=" border border-gray-300 w-44">
                <div className="flex gap-x-2 justify-center items-center">
                  <button
                    onClick={() => decQuantity(product?.rowKey)}
                    disabled={!product?.canDecrement}
                    className={`bg-red-500 hover:bg-red-400 px-2 py-1 rounded ${!product?.canDecrement ? `bg-opacity-40 cursor-not-allowed` : ``}`}
                  >
                    <FiMinus className="text-sm" />
                  </button>

                  <input
                    type="number"
                    // disabled={product?.is_weight}
                    // value={
                    //    product?.number
                    // }
                    value={
                      product?.is_weight ? product?.weight : product?.number
                    }
                    onChange={(e) => {
                      chngQuantity(
                        product?.rowKey,
                        e?.target?.value,
                      );
                    }}
                    className={`w-[70px] px-1 border border-gray-300 rounded text-center text-sm appearance-none`}
                  />

                  <button
                    onClick={() => incQuantity(product?.rowKey)}
                    // disabled={product?.is_weight}
                    // className={`bg-green-500  px-2 py-1 rounded ${product?.quantity == 0 ? `bg-opacity-40 cursor-not-allowed` : ` hover:bg-green-400`} ${product?.is_weight ? `bg-opacity-40 cursor-not-allowed` : ` hover:bg-green-400`}`}
                    className={`bg-green-500  px-2 py-1 rounded ${availableStock == 0 ? `bg-opacity-40 cursor-not-allowed` : ` hover:bg-green-400`}`}
                  >
                    <FaPlus className="text-sm" />
                  </button>
                </div>
              </td>
              <td className="border border-gray-300">
                <div className="flex items-center gap-1 relative">
                  <CreatableSelect
                    ref={(el) => (quantityRefs.current[product?.id] = el)}
                    options={unitOptions}
                    placeholder="اختر الوحدة"
                    isSearchable
                    value={
                      product?.selectedUnit
                        ? Object.values(product?.selectedUnit)[
                            Object.values(product?.selectedUnit).length - 1
                          ]
                        : null
                    }
                    onChange={(option) => {
                      handleChangeUnit(product?.id, option, product?.rowKey);
                    }}
                    menuPortalTarget={document.body}
                    menuPosition="fixed"
                    styles={{
                      container: (base) => ({
                        ...base,
                        flex: 1,
                        menuPortal: (base) => ({
                          ...base,
                          zIndex: 9999,
                        }),
                      }),
                    }}
                  />
                </div>
              </td>
              <td className="px-1 border border-gray-300">
                {product?.price?.toFixed(2)}
              </td>
              <td className="px-1 border border-gray-300">
                {product?.total?.toFixed(2)}
              </td>
              <td className=" border border-gray-300">
                <button
                  onClick={() => deleteProduct(product.rowKey)}
                  className="border border-red-600 border-opacity-80 p-1 rounded hover:bg-red-100 hover:transition-transform hover:scale-110 hover:duration-300"
                >
                  <ImBin className="text-red-600  text-opacity-80 text-xl" />
                </button>
              </td>
            </tr>
          );
        })
      ) : (
        <tr className="h-[20rem]">
          <td
            className="text-xl font-bold text-center text-gray-400"
            colSpan={7}
          >
            لا توجد بيانات للعرض
          </td>
        </tr>
      )}
    </>
  );
}
